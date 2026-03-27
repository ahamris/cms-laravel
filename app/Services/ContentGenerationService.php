<?php

namespace App\Services;

use App\Ai\Agents\ArticleStructuredAgent;
use App\Ai\Agents\BlogContentWriterAgent;
use App\Ai\Agents\CrmSupportAgent;
use App\Ai\Agents\PlanBlogContentAgent;
use App\Models\IntentBrief;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Responses\StructuredAgentResponse;

class ContentGenerationService
{
    /**
     * @return array{success: bool, data?: array<string, mixed>, error?: string}
     */
    public function generateAdminBlog(string $topic, string $keywords, string $tone, string $lengthGuide): array
    {
        $map = ResolvedAiProviders::providerModelMapForTask('content');
        if ($map === []) {
            return ['success' => false, 'error' => 'No AI service is configured.'];
        }

        try {
            $agent = BlogContentWriterAgent::make(tone: $tone, lengthGuide: $lengthGuide);
            $user = "Topic: {$topic}\n";
            if ($keywords !== '') {
                $user .= "Target keywords to include: {$keywords}\n";
            }
            $user .= 'Write a comprehensive blog post about this topic.';

            $response = $agent->prompt($user, provider: $map);
            if (! $response instanceof StructuredAgentResponse) {
                return ['success' => false, 'error' => 'Unexpected AI response type.'];
            }

            return ['success' => true, 'data' => $response->toArray()];
        } catch (\Throwable $e) {
            Log::error('BlogContentWriterAgent failed', ['message' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * @param  array<string, mixed>  $keywords
     * @return array{success: bool, data?: array{title: string, excerpt: string, body: string}, error?: string}
     */
    public function generatePlanBlog(string $title, string $brief, array $keywords, IntentBrief $intentBrief): array
    {
        $map = ResolvedAiProviders::providerModelMapForTask('content');
        if ($map === []) {
            return ['success' => false, 'error' => 'No AI service is configured.'];
        }

        $primary = (string) ($keywords['primary'] ?? 'N/A');

        try {
            $agent = PlanBlogContentAgent::make(
                tone: (string) $intentBrief->tone,
                audience: (string) $intentBrief->audience,
                primaryKeyword: $primary,
            );

            $user = "Title: {$title}\n\nBrief: {$brief}\n\nWrite a comprehensive blog post.";

            $response = $agent->prompt($user, provider: $map);
            if (! $response instanceof StructuredAgentResponse) {
                return ['success' => false, 'error' => 'Unexpected AI response type.'];
            }

            return ['success' => true, 'data' => $response->toArray()];
        } catch (\Throwable $e) {
            Log::error('PlanBlogContentAgent failed', ['message' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * @return array{success: bool, article?: array<string, mixed>, error?: string}
     */
    public function generateArticleStructured(
        string $topic,
        string $type,
        ?string $category,
        string $tone,
        string $language,
        int $length,
    ): array {
        $map = ResolvedAiProviders::providerModelMapForTask('content');
        if ($map === []) {
            return ['success' => false, 'error' => 'No AI service is configured.'];
        }

        try {
            $agent = ArticleStructuredAgent::make(
                type: $type,
                category: $category,
                tone: $tone,
                language: $language,
                length: $length,
            );

            $response = $agent->prompt("Write about: {$topic}", provider: $map);
            if (! $response instanceof StructuredAgentResponse) {
                return ['success' => false, 'error' => 'Unexpected AI response type.'];
            }

            return ['success' => true, 'article' => $response->toArray()];
        } catch (\Throwable $e) {
            Log::error('ArticleStructuredAgent failed', ['message' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * @return array{
     *     success: bool,
     *     draft?: string,
     *     summary?: string,
     *     suggested_status?: string,
     *     risk_flags?: array<int, string>,
     *     error?: string
     * }
     */
    public function crmAssist(string $threadText, ?int $contactId, string $tone, string $language): array
    {
        $map = ResolvedAiProviders::providerModelMapForTask('crm');
        if ($map === []) {
            return ['success' => false, 'error' => 'No AI service is configured.'];
        }

        try {
            $agent = CrmSupportAgent::make(
                contactId: $contactId,
                tone: $tone,
                language: $language,
            );

            $prompt = "Customer thread:\n\n{$threadText}\n\nProduce the structured assistance.";

            $response = $agent->prompt($prompt, provider: $map);
            if (! $response instanceof StructuredAgentResponse) {
                return ['success' => false, 'error' => 'Unexpected AI response type.'];
            }

            $data = $response->toArray();

            return [
                'success' => true,
                'draft' => (string) ($data['suggested_reply'] ?? ''),
                'summary' => (string) ($data['summary'] ?? ''),
                'suggested_status' => (string) ($data['suggested_status'] ?? ''),
                'risk_flags' => is_array($data['risk_flags'] ?? null)
                    ? array_values(array_map('strval', $data['risk_flags']))
                    : [],
            ];
        } catch (\Throwable $e) {
            Log::error('CrmSupportAgent failed', ['message' => $e->getMessage()]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
