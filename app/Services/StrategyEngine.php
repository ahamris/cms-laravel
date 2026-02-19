<?php

namespace App\Services;

use App\Models\IntentBrief;
use App\Models\ContentPlan;
use App\Models\ContentPlanItem;
use App\Models\Blog;
use Illuminate\Support\Facades\Log;

class StrategyEngine extends AIService
{
    /**
     * Generate content plan from intent brief
     */
    public function generateContentPlan(IntentBrief $intentBrief): ContentPlan
    {
        $intentBrief->update(['status' => 'processing']);

        try {
            // Generate strategy using AI
            $strategy = $this->generateStrategy($intentBrief);
            
            // Create content plan
            $contentPlan = ContentPlan::create([
                'intent_brief_id' => $intentBrief->id,
                'status' => 'pending_approval',
                'autopilot_mode' => $intentBrief->approval_level === 'auto_approve' ? 'guided' : 'assisted',
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'strategy_data' => $strategy,
            ]);

            // Generate plan items
            $this->generatePlanItems($contentPlan, $strategy);

            $intentBrief->update(['status' => 'completed']);

            return $contentPlan;

        } catch (\Exception $e) {
            Log::error('Strategy Engine Error', [
                'intent_brief_id' => $intentBrief->id,
                'error' => $e->getMessage()
            ]);
            $intentBrief->update(['status' => 'failed']);
            throw $e;
        }
    }

    /**
     * Generate marketing strategy using AI
     */
    protected function generateStrategy(IntentBrief $intentBrief): array
    {
        $systemPrompt = "You are an expert marketing strategist specializing in content marketing and SEO. 
Your task is to analyze a business intent brief and create a comprehensive 30-day content marketing strategy.

Analyze the following intent brief and provide a JSON response with:
1. SEO gap analysis (internal and external opportunities)
2. Topic clusters (main topic + 3-5 related subtopics)
3. Keyword intent classification (informational, transactional, navigational)
4. Channel suitability scoring (blog, LinkedIn, X/Twitter, etc.)
5. Optimal content mix for 30 days:
   - 1 pillar article (comprehensive, SEO-focused)
   - 3 supporting blog posts (cluster content)
   - 12 social media posts (repurposed from blog content)
   - 1 evergreen content update (refresh existing content)

Return ONLY valid JSON in this format:
{
  \"seo_analysis\": {
    \"gap_opportunities\": [\"keyword1\", \"keyword2\"],
    \"internal_links\": [\"suggestion1\", \"suggestion2\"]
  },
  \"topic_clusters\": [
    {
      \"main_topic\": \"topic name\",
      \"subtopics\": [\"subtopic1\", \"subtopic2\"]
    }
  ],
  \"keywords\": {
    \"primary\": \"main keyword\",
    \"secondary\": [\"keyword1\", \"keyword2\"],
    \"intent\": \"informational|transactional|navigational\"
  },
  \"channels\": {
    \"blog\": {\"score\": 9, \"reason\": \"reason\"},
    \"linkedin\": {\"score\": 7, \"reason\": \"reason\"},
    \"twitter\": {\"score\": 6, \"reason\": \"reason\"}
  },
  \"content_mix\": {
    \"pillar\": 1,
    \"supporting\": 3,
    \"social\": 12,
    \"evergreen\": 1
  }
}";

        $userMessage = "Business Goal: {$intentBrief->business_goal}\n";
        $userMessage .= "Target Audience: {$intentBrief->audience}\n";
        $userMessage .= "Topic/Problem: {$intentBrief->topic}\n";
        $userMessage .= "Tone: {$intentBrief->tone}\n";
        $userMessage .= "\nGenerate a comprehensive 30-day content marketing strategy.";

        $result = $this->callAI($systemPrompt, $userMessage, 0.7, 16384);

        if (!$result['success']) {
            throw new \Exception('Failed to generate strategy: ' . ($result['error'] ?? 'Unknown error'));
        }

        // Parse JSON response
        $content = $result['content'];
        $json = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Try to extract JSON from markdown code blocks
            if (preg_match('/```json\s*(.*?)\s*```/s', $content, $matches)) {
                $json = json_decode($matches[1], true);
            } elseif (preg_match('/\{.*\}/s', $content, $matches)) {
                $json = json_decode($matches[0], true);
            }
        }

        if (json_last_error() !== JSON_ERROR_NONE || !$json) {
            Log::error('Failed to parse AI strategy response', [
                'content' => $content,
                'error' => json_last_error_msg()
            ]);
            // Return default strategy structure
            return $this->getDefaultStrategy($intentBrief);
        }

        return $json;
    }

    /**
     * Generate plan items from strategy
     */
    protected function generatePlanItems(ContentPlan $contentPlan, array $strategy): void
    {
        $items = [];
        $currentDate = $contentPlan->start_date;

        // 1 Pillar article
        if (isset($strategy['topic_clusters'][0])) {
            $cluster = $strategy['topic_clusters'][0];
            $items[] = [
                'item_type' => 'pillar',
                'priority' => 10,
                'scheduled_at' => $currentDate->copy()->addDays(1)->setTime(9, 0),
                'content_data' => [
                    'title' => $cluster['main_topic'] ?? 'Pillar Article',
                    'keywords' => $strategy['keywords'] ?? [],
                    'brief' => "Comprehensive pillar article covering: " . ($cluster['main_topic'] ?? 'main topic'),
                ],
            ];
            $currentDate->addDays(3);
        }

        // 3 Supporting blog posts
        $subtopics = $strategy['topic_clusters'][0]['subtopics'] ?? [];
        for ($i = 0; $i < min(3, count($subtopics)); $i++) {
            $items[] = [
                'item_type' => 'supporting',
                'priority' => 7,
                'scheduled_at' => $currentDate->copy()->addDays($i * 7)->setTime(9, 0),
                'content_data' => [
                    'title' => $subtopics[$i] ?? "Supporting Article " . ($i + 1),
                    'keywords' => $strategy['keywords']['secondary'] ?? [],
                    'brief' => "Supporting article about: " . ($subtopics[$i] ?? 'subtopic'),
                ],
            ];
        }

        // 12 Social posts (spread across 30 days)
        for ($i = 0; $i < 12; $i++) {
            $items[] = [
                'item_type' => 'social',
                'priority' => 5,
                'scheduled_at' => $currentDate->copy()->addDays($i * 2.5)->setTime(12, 0),
                'content_data' => [
                    'platforms' => ['linkedin', 'twitter'],
                    'brief' => "Social post promoting content",
                ],
            ];
        }

        // 1 Evergreen update
        $items[] = [
            'item_type' => 'evergreen',
            'priority' => 6,
            'scheduled_at' => $currentDate->copy()->addDays(25)->setTime(14, 0),
            'content_data' => [
                'brief' => "Update existing evergreen content",
            ],
        ];

        // Create plan items
        foreach ($items as $itemData) {
            ContentPlanItem::create(array_merge([
                'content_plan_id' => $contentPlan->id,
                'status' => 'planned',
            ], $itemData));
        }
    }

    /**
     * Get default strategy if AI fails
     */
    protected function getDefaultStrategy(IntentBrief $intentBrief): array
    {
        return [
            'seo_analysis' => [
                'gap_opportunities' => [],
                'internal_links' => [],
            ],
            'topic_clusters' => [
                [
                    'main_topic' => $intentBrief->topic,
                    'subtopics' => [
                        $intentBrief->topic . ' - Guide',
                        $intentBrief->topic . ' - Best Practices',
                        $intentBrief->topic . ' - Case Study',
                    ],
                ],
            ],
            'keywords' => [
                'primary' => $intentBrief->topic,
                'secondary' => [],
                'intent' => 'informational',
            ],
            'channels' => [
                'blog' => ['score' => 8, 'reason' => 'Primary content channel'],
                'linkedin' => ['score' => 7, 'reason' => 'Professional audience'],
                'twitter' => ['score' => 6, 'reason' => 'Quick updates'],
            ],
            'content_mix' => [
                'pillar' => 1,
                'supporting' => 3,
                'social' => 12,
                'evergreen' => 1,
            ],
        ];
    }
}

