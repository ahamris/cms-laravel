<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AIService
{
    /**
     * Call Groq AI API
     */
    protected function callGroqAI(string $systemPrompt, string $userMessage, float $temperature = 0.7, int $maxTokens = 8192): array
    {
        try {
            $apiKey = \App\Models\AIServiceSetting::getApiKey('groq');
            if (empty($apiKey)) {
                return ['success' => false, 'error' => 'Groq API key not configured. Please configure it in Admin → Settings → AI Settings.'];
            }

            $model = \App\Models\AIServiceSetting::getModel('groq', 'llama-3.3-70b-versatile');
            
            $payload = [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$apiKey}",
            ])->timeout(120)->post('https://api.groq.com/openai/v1/chat/completions', $payload);

            if ($response->failed()) {
                Log::error('Groq API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                if ($response->status() === 429) {
                    return ['success' => false, 'error' => 'API rate limit exceeded. Please wait a moment and try again.'];
                }
                
                return ['success' => false, 'error' => 'Groq API request failed'];
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            if (!$content) {
                return ['success' => false, 'error' => 'No response from Groq AI'];
            }

            return ['success' => true, 'content' => $this->cleanAIResponse($content)];

        } catch (\Exception $e) {
            Log::error('Groq AI Error', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Call Gemini AI API
     */
    protected function callGeminiAI(string $systemPrompt, string $userMessage, float $temperature = 0.7, int $maxTokens = 8192): array
    {
        try {
            $apiKey = \App\Models\AIServiceSetting::getApiKey('gemini');
            if (empty($apiKey)) {
                return ['success' => false, 'error' => 'Gemini API key not configured. Please configure it in Admin → Settings → AI Settings.'];
            }

            $model = \App\Models\AIServiceSetting::getModel('gemini', 'gemini-2.0-flash');
            
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\n" . $userMessage]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => $temperature,
                    'maxOutputTokens' => $maxTokens,
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(120)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", $payload);

            if ($response->failed()) {
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                if ($response->status() === 429) {
                    return ['success' => false, 'error' => 'API rate limit exceeded. Please wait a moment and try again.'];
                }
                
                return ['success' => false, 'error' => 'Gemini API request failed'];
            }

            $data = $response->json();
            $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$content) {
                return ['success' => false, 'error' => 'No response from Gemini AI'];
            }

            return ['success' => true, 'content' => $this->cleanAIResponse($content)];

        } catch (\Exception $e) {
            Log::error('Gemini AI Error', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Call Ollama API (self-hosted; no API key)
     */
    protected function callOllamaAI(string $systemPrompt, string $userMessage, float $temperature = 0.7, int $maxTokens = 8192): array
    {
        try {
            $baseUrl = \App\Models\AIServiceSetting::getBaseUrl('ollama');
            if (empty($baseUrl)) {
                return ['success' => false, 'error' => 'Ollama base URL not configured. Please set it in Admin → Settings → AI Settings.'];
            }

            $model = \App\Models\AIServiceSetting::getModel('ollama', 'llama3.2');
            $baseUrl = rtrim($baseUrl, '/');

            $payload = [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'stream' => false,
                'options' => [
                    'temperature' => $temperature,
                    'num_predict' => $maxTokens,
                ],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(120)->post("{$baseUrl}/api/chat", $payload);

            if ($response->failed()) {
                Log::error('Ollama API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return ['success' => false, 'error' => 'Ollama request failed: ' . ($response->json()['error'] ?? $response->body())];
            }

            $data = $response->json();
            $content = $data['message']['content'] ?? null;

            if (!$content) {
                return ['success' => false, 'error' => 'No response from Ollama'];
            }

            return ['success' => true, 'content' => $this->cleanAIResponse($content)];

        } catch (\Exception $e) {
            Log::error('Ollama AI Error', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Call AI with fallback (Groq, Gemini, Ollama by priority)
     */
    protected function callAI(string $systemPrompt, string $userMessage, float $temperature = 0.7, int $maxTokens = 8192): array
    {
        // Get active services ordered by priority
        $activeServices = \App\Models\AIServiceSetting::getActiveServices();

        if ($activeServices->isEmpty()) {
            return [
                'success' => false,
                'error' => 'No AI service is configured. Please configure at least one AI service in Admin → Settings → AI Settings.',
            ];
        }

        foreach ($activeServices as $service) {
            if ($service->service === 'groq') {
                $result = $this->callGroqAI($systemPrompt, $userMessage, $temperature, $maxTokens);
                if ($result['success']) {
                    return $result;
                }
                Log::warning('Groq API failed, trying next service', ['error' => $result['error'] ?? 'Unknown']);
            } elseif ($service->service === 'gemini') {
                $result = $this->callGeminiAI($systemPrompt, $userMessage, $temperature, $maxTokens);
                if ($result['success']) {
                    return $result;
                }
                Log::warning('Gemini API failed, trying next service', ['error' => $result['error'] ?? 'Unknown']);
            } elseif ($service->service === 'ollama') {
                $result = $this->callOllamaAI($systemPrompt, $userMessage, $temperature, $maxTokens);
                if ($result['success']) {
                    return $result;
                }
                Log::warning('Ollama API failed, trying next service', ['error' => $result['error'] ?? 'Unknown']);
            }
        }

        return [
            'success' => false,
            'error' => 'All configured AI services failed. Please check your settings in Admin → Settings → AI Settings.',
        ];
    }

    /**
     * Generate page blocks from a topic description.
     */
    public function generatePageBlocks(string $topic, string $tone = 'professional', string $language = 'nl', array $blockTypes = ['hero', 'text', 'cta']): array
    {
        $typesStr = implode(', ', $blockTypes);
        $systemPrompt = "You are a content architect. Generate a JSON array of page blocks for a website page. "
            . "Available block types: {$typesStr}. "
            . "Each block should have: type (string), content (object with 'title', 'body' keys), settings (object). "
            . "Tone: {$tone}. Language: {$language}. "
            . "Return ONLY valid JSON array, no explanations.";

        $result = $this->callAI($systemPrompt, "Create page blocks for: {$topic}");

        if (!$result['success']) return $result;

        $decoded = json_decode($result['content'], true);
        return ['success' => true, 'blocks' => is_array($decoded) ? $decoded : []];
    }

    /**
     * Generate a full article structure.
     */
    public function generateArticle(string $topic, string $type = 'article', ?string $category = null, string $tone = 'informative', string $language = 'nl', int $length = 1000): array
    {
        $systemPrompt = "You are a professional content writer. Generate a blog article in JSON format with keys: "
            . "title (string), slug (string, kebab-case), short_body (string, excerpt 1-2 sentences), "
            . "long_body (string, HTML formatted article), meta_title (string, max 60 chars), "
            . "meta_description (string, max 155 chars), primary_keyword (string), "
            . "secondary_keywords (array of strings). "
            . "Type: {$type}. " . ($category ? "Category context: {$category}. " : "")
            . "Tone: {$tone}. Language: {$language}. Target length: ~{$length} words. "
            . "Return ONLY valid JSON, no explanations.";

        $result = $this->callAI($systemPrompt, "Write about: {$topic}");

        if (!$result['success']) return $result;

        $decoded = json_decode($result['content'], true);
        return ['success' => true, 'article' => is_array($decoded) ? $decoded : []];
    }

    /**
     * Optimize SEO for given content.
     */
    public function optimizeSEO(string $contentHtml): array
    {
        $systemPrompt = "You are an SEO expert. Analyze the provided HTML content and return JSON with keys: "
            . "meta_title (string, max 60 chars), meta_description (string, max 155 chars), "
            . "primary_keyword (string), secondary_keywords (array), readability_score (int 0-100), "
            . "suggestions (array of improvement strings). "
            . "Return ONLY valid JSON, no explanations.";

        $plainText = strip_tags($contentHtml);
        $snippet = mb_substr($plainText, 0, 3000);

        $result = $this->callAI($systemPrompt, "Analyze this content:\n\n{$snippet}");

        if (!$result['success']) return $result;

        $decoded = json_decode($result['content'], true);
        return ['success' => true, 'seo' => is_array($decoded) ? $decoded : []];
    }

    /**
     * Draft a reply for CRM messages/tickets.
     */
    public function draftReply(string $originalMessage, string $tone = 'professional', string $language = 'nl'): string
    {
        $systemPrompt = "You are a customer service representative. Draft a helpful reply to the customer message below. "
            . "Tone: {$tone}. Language: {$language}. "
            . "Be concise, empathetic, and solution-oriented. Return ONLY the reply text, no JSON or formatting.";

        $result = $this->callAI($systemPrompt, "Customer message:\n\n{$originalMessage}");

        return $result['success'] ? $result['content'] : '';
    }

    /**
     * Generate a content plan.
     */
    public function generateContentPlan(string $topic, int $items = 5, string $language = 'nl'): array
    {
        $systemPrompt = "You are a content strategist. Generate a content plan as a JSON array. "
            . "Each item should have: title (string), type (article|video|podcast), "
            . "category (string), keywords (array), target_funnel_stage (interesseer|overtuig|activeer|inspireer), "
            . "estimated_effort (string like '2h', '4h'). "
            . "Language: {$language}. Generate {$items} content items. "
            . "Return ONLY valid JSON array, no explanations.";

        $result = $this->callAI($systemPrompt, "Create content plan about: {$topic}");

        if (!$result['success']) return $result;

        $decoded = json_decode($result['content'], true);
        return ['success' => true, 'plan' => is_array($decoded) ? $decoded : []];
    }

    /**
     * Clean AI response (remove markdown code blocks, etc.)
     */
    protected function cleanAIResponse(string $response): string
    {
        // Remove markdown code blocks
        $response = preg_replace('/```[\w]*\n?/', '', $response);
        $response = preg_replace('/```$/', '', $response);
        
        // Trim whitespace
        $response = trim($response);
        
        return $response;
    }
}

