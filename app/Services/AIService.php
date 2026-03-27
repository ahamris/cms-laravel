<?php

namespace App\Services;

use App\Models\AIServiceSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected function callOpenAIAI(string $systemPrompt, string $userMessage, float $temperature = 0.7, int $maxTokens = 8192): array
    {
        $startedAt = microtime(true);
        try {
            $apiKey = AIServiceSetting::getApiKey('openai');
            if (empty($apiKey)) {
                return ['success' => false, 'provider' => 'openai', 'error' => 'OpenAI API key not configured.'];
            }

            $model = AIServiceSetting::getModel('openai', 'gpt-4o-mini');
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ]);

            if ($response->failed()) {
                return ['success' => false, 'provider' => 'openai', 'error' => 'OpenAI API request failed'];
            }

            $content = $response->json('choices.0.message.content');
            if (! is_string($content) || trim($content) === '') {
                return ['success' => false, 'provider' => 'openai', 'error' => 'No response from OpenAI'];
            }

            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

            return ['success' => true, 'provider' => 'openai', 'model' => $model, 'duration_ms' => $durationMs, 'content' => $this->cleanAIResponse($content)];
        } catch (\Throwable $e) {
            return ['success' => false, 'provider' => 'openai', 'error' => $e->getMessage()];
        }
    }

    protected function callAnthropicAI(string $systemPrompt, string $userMessage, float $temperature = 0.7, int $maxTokens = 8192): array
    {
        $startedAt = microtime(true);
        try {
            $apiKey = AIServiceSetting::getApiKey('anthropic');
            if (empty($apiKey)) {
                return ['success' => false, 'provider' => 'anthropic', 'error' => 'Anthropic API key not configured.'];
            }

            $model = AIServiceSetting::getModel('anthropic', 'claude-3-5-sonnet-latest');
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->timeout(120)->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'max_tokens' => $maxTokens,
                'temperature' => $temperature,
                'system' => $systemPrompt,
                'messages' => [
                    ['role' => 'user', 'content' => $userMessage],
                ],
            ]);

            if ($response->failed()) {
                return ['success' => false, 'provider' => 'anthropic', 'error' => 'Anthropic API request failed'];
            }

            $content = $response->json('content.0.text');
            if (! is_string($content) || trim($content) === '') {
                return ['success' => false, 'provider' => 'anthropic', 'error' => 'No response from Anthropic'];
            }

            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

            return ['success' => true, 'provider' => 'anthropic', 'model' => $model, 'duration_ms' => $durationMs, 'content' => $this->cleanAIResponse($content)];
        } catch (\Throwable $e) {
            return ['success' => false, 'provider' => 'anthropic', 'error' => $e->getMessage()];
        }
    }

    /**
     * Call Groq AI API
     */
    protected function callGroqAI(string $systemPrompt, string $userMessage, float $temperature = 0.7, int $maxTokens = 8192): array
    {
        $startedAt = microtime(true);
        try {
            $apiKey = AIServiceSetting::getApiKey('groq');
            if (empty($apiKey)) {
                return ['success' => false, 'error' => 'Groq API key not configured. Please configure it in Admin → Settings → AI Settings.'];
            }

            $model = AIServiceSetting::getModel('groq', 'llama-3.3-70b-versatile');

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
                    'body' => $response->body(),
                ]);

                if ($response->status() === 429) {
                    return ['success' => false, 'error' => 'API rate limit exceeded. Please wait a moment and try again.'];
                }

                return ['success' => false, 'error' => 'Groq API request failed'];
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            if (! $content) {
                return ['success' => false, 'error' => 'No response from Groq AI'];
            }

            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);
            Log::info('ai.request.completed', [
                'provider' => 'groq',
                'model' => $model,
                'duration_ms' => $durationMs,
                'success' => true,
            ]);

            return ['success' => true, 'provider' => 'groq', 'model' => $model, 'duration_ms' => $durationMs, 'content' => $this->cleanAIResponse($content)];

        } catch (\Exception $e) {
            Log::error('Groq AI Error', ['message' => $e->getMessage()]);

            return ['success' => false, 'provider' => 'groq', 'error' => $e->getMessage()];
        }
    }

    /**
     * Call Gemini AI API
     */
    protected function callGeminiAI(string $systemPrompt, string $userMessage, float $temperature = 0.7, int $maxTokens = 8192): array
    {
        $startedAt = microtime(true);
        try {
            $apiKey = AIServiceSetting::getApiKey('gemini');
            if (empty($apiKey)) {
                return ['success' => false, 'error' => 'Gemini API key not configured. Please configure it in Admin → Settings → AI Settings.'];
            }

            $model = AIServiceSetting::getModel('gemini', 'gemini-2.0-flash');

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt."\n\n".$userMessage],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => $temperature,
                    'maxOutputTokens' => $maxTokens,
                ],
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(120)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", $payload);

            if ($response->failed()) {
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                if ($response->status() === 429) {
                    return ['success' => false, 'error' => 'API rate limit exceeded. Please wait a moment and try again.'];
                }

                return ['success' => false, 'error' => 'Gemini API request failed'];
            }

            $data = $response->json();
            $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (! $content) {
                return ['success' => false, 'error' => 'No response from Gemini AI'];
            }

            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);
            Log::info('ai.request.completed', [
                'provider' => 'gemini',
                'model' => $model,
                'duration_ms' => $durationMs,
                'success' => true,
            ]);

            return ['success' => true, 'provider' => 'gemini', 'model' => $model, 'duration_ms' => $durationMs, 'content' => $this->cleanAIResponse($content)];

        } catch (\Exception $e) {
            Log::error('Gemini AI Error', ['message' => $e->getMessage()]);

            return ['success' => false, 'provider' => 'gemini', 'error' => $e->getMessage()];
        }
    }

    /**
     * Call Ollama API (self-hosted; no API key)
     */
    protected function callOllamaAI(string $systemPrompt, string $userMessage, float $temperature = 0.7, int $maxTokens = 8192): array
    {
        $startedAt = microtime(true);
        try {
            $baseUrl = AIServiceSetting::getBaseUrl('ollama');
            if (empty($baseUrl)) {
                return ['success' => false, 'error' => 'Ollama base URL not configured. Please set it in Admin → Settings → AI Settings.'];
            }

            $model = AIServiceSetting::getModel('ollama', 'llama3.2');
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

                return ['success' => false, 'error' => 'Ollama request failed: '.($response->json()['error'] ?? $response->body())];
            }

            $data = $response->json();
            $content = $data['message']['content'] ?? null;

            if (! $content) {
                return ['success' => false, 'error' => 'No response from Ollama'];
            }

            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);
            Log::info('ai.request.completed', [
                'provider' => 'ollama',
                'model' => $model,
                'duration_ms' => $durationMs,
                'success' => true,
            ]);

            return ['success' => true, 'provider' => 'ollama', 'model' => $model, 'duration_ms' => $durationMs, 'content' => $this->cleanAIResponse($content)];

        } catch (\Exception $e) {
            Log::error('Ollama AI Error', ['message' => $e->getMessage()]);

            return ['success' => false, 'provider' => 'ollama', 'error' => $e->getMessage()];
        }
    }

    /**
     * Call AI with fallback. Tries providers in task-specific order (Gemini → OpenAI → Anthropic → …), then any remaining active providers.
     *
     * @param  'content'|'seo'|'crm'  $profile
     */
    protected function callAI(string $systemPrompt, string $userMessage, float $temperature = 0.7, int $maxTokens = 8192, string $profile = 'content'): array
    {
        $activeServices = AIServiceSetting::getActiveServices();

        if ($activeServices->isEmpty()) {
            return [
                'success' => false,
                'error' => 'No AI service is configured. Please configure at least one AI service in Admin → Settings → AI Settings.',
            ];
        }

        $preferred = match ($profile) {
            'seo' => ResolvedAiProviders::TASK_SEO_ORDER,
            'crm' => ResolvedAiProviders::TASK_CRM_ORDER,
            default => ResolvedAiProviders::TASK_CONTENT_ORDER,
        };

        $sorted = $activeServices->sortBy(function ($service) use ($preferred) {
            $name = AIServiceSetting::normalizeServiceName((string) $service->service);
            $i = array_search($name, $preferred, true);

            return $i === false ? 1000 : $i;
        })->values();

        foreach ($sorted as $service) {
            $name = AIServiceSetting::normalizeServiceName((string) $service->service);
            $result = match ($name) {
                'groq' => $this->callGroqAI($systemPrompt, $userMessage, $temperature, $maxTokens),
                'gemini' => $this->callGeminiAI($systemPrompt, $userMessage, $temperature, $maxTokens),
                'ollama' => $this->callOllamaAI($systemPrompt, $userMessage, $temperature, $maxTokens),
                'openai' => $this->callOpenAIAI($systemPrompt, $userMessage, $temperature, $maxTokens),
                'anthropic' => $this->callAnthropicAI($systemPrompt, $userMessage, $temperature, $maxTokens),
                default => null,
            };

            if ($result === null) {
                continue;
            }

            if ($result['success']) {
                return $result;
            }

            Log::warning('AI provider failed, trying next service', [
                'provider' => $name,
                'error' => $result['error'] ?? 'Unknown',
            ]);
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
        $systemPrompt = 'You are a content architect. Generate a JSON array of page blocks for a website page. '
            ."Available block types: {$typesStr}. "
            ."Each block should have: type (string), content (object with 'title', 'body' keys), settings (object). "
            ."Tone: {$tone}. Language: {$language}. "
            .'Return ONLY valid JSON array, no explanations.';

        $result = $this->callAI($systemPrompt, "Create page blocks for: {$topic}", 0.7, 8192, 'content');

        if (! $result['success']) {
            return $result;
        }

        $decoded = json_decode($result['content'], true);

        return [
            'success' => true,
            'blocks' => is_array($decoded) ? $decoded : [],
            'provider' => $result['provider'] ?? null,
            'model' => $result['model'] ?? null,
            'duration_ms' => $result['duration_ms'] ?? null,
        ];
    }

    /**
     * Generate a full article structure.
     */
    public function generateArticle(string $topic, string $type = 'article', ?string $category = null, string $tone = 'informative', string $language = 'nl', int $length = 1000): array
    {
        $gen = app(ContentGenerationService::class);
        $structured = $gen->generateArticleStructured($topic, $type, $category, $tone, $language, $length);
        if ($structured['success'] && ! empty($structured['article'])) {
            return [
                'success' => true,
                'article' => $structured['article'],
                'provider' => null,
                'model' => null,
                'duration_ms' => null,
            ];
        }

        $systemPrompt = 'You are a professional content writer. Generate a blog article in JSON format with keys: '
            .'title (string), slug (string, kebab-case), short_body (string, excerpt 1-2 sentences), '
            .'long_body (string, HTML formatted article), meta_title (string, max 60 chars), '
            .'meta_description (string, max 155 chars), primary_keyword (string), '
            .'secondary_keywords (array of strings). '
            ."Type: {$type}. ".($category ? "Category context: {$category}. " : '')
            ."Tone: {$tone}. Language: {$language}. Target length: ~{$length} words. "
            .'Return ONLY valid JSON, no explanations.';

        $result = $this->callAI($systemPrompt, "Write about: {$topic}", 0.7, 8192, 'content');

        if (! $result['success']) {
            return $result;
        }

        $decoded = json_decode($result['content'], true);

        return [
            'success' => true,
            'article' => is_array($decoded) ? $decoded : [],
            'provider' => $result['provider'] ?? null,
            'model' => $result['model'] ?? null,
            'duration_ms' => $result['duration_ms'] ?? null,
        ];
    }

    /**
     * Optimize SEO for given content.
     */
    public function optimizeSEO(string $contentHtml): array
    {
        $systemPrompt = 'You are an SEO expert. Analyze the provided HTML content and return JSON with keys: '
            .'meta_title (string, max 60 chars), meta_description (string, max 155 chars), '
            .'primary_keyword (string), secondary_keywords (array), readability_score (int 0-100), '
            .'suggestions (array of improvement strings). '
            .'Return ONLY valid JSON, no explanations.';

        $plainText = strip_tags($contentHtml);
        $snippet = mb_substr($plainText, 0, 3000);

        $result = $this->callAI($systemPrompt, "Analyze this content:\n\n{$snippet}", 0.7, 8192, 'seo');

        if (! $result['success']) {
            return $result;
        }

        $decoded = json_decode($result['content'], true);

        return [
            'success' => true,
            'seo' => is_array($decoded) ? $decoded : [],
            'provider' => $result['provider'] ?? null,
            'model' => $result['model'] ?? null,
            'duration_ms' => $result['duration_ms'] ?? null,
        ];
    }

    /**
     * Draft a reply for CRM messages/tickets.
     */
    public function draftReply(string $originalMessage, string $tone = 'professional', string $language = 'nl', ?int $contactId = null): string
    {
        $gen = app(ContentGenerationService::class);
        $assist = $gen->crmAssist($originalMessage, $contactId, $tone, $language);
        if ($assist['success'] && trim($assist['draft'] ?? '') !== '') {
            return trim($assist['draft']);
        }

        return $this->draftReplyViaHttp($originalMessage, $tone, $language);
    }

    /**
     * CRM assist with structured fields when the AI SDK path succeeds; otherwise HTTP draft only.
     *
     * @return array{
     *     success: bool,
     *     draft?: string,
     *     summary?: string,
     *     suggested_status?: string,
     *     risk_flags?: array<int, string>,
     *     error?: string
     * }
     */
    public function crmStructuredAssist(string $thread, ?int $contactId = null, string $tone = 'professional', string $language = 'nl'): array
    {
        $gen = app(ContentGenerationService::class);
        $assist = $gen->crmAssist($thread, $contactId, $tone, $language);
        if ($assist['success'] && trim($assist['draft'] ?? '') !== '') {
            return $assist;
        }

        $draft = $this->draftReplyViaHttp($thread, $tone, $language);
        if ($draft === '') {
            return ['success' => false, 'error' => 'AI draft unavailable.'];
        }

        return [
            'success' => true,
            'draft' => $draft,
            'summary' => '',
            'suggested_status' => '',
            'risk_flags' => [],
        ];
    }

    protected function draftReplyViaHttp(string $originalMessage, string $tone, string $language): string
    {
        $systemPrompt = 'You are a customer service representative. Draft a helpful reply to the customer message below. '
            ."Tone: {$tone}. Language: {$language}. "
            .'Be concise, empathetic, and solution-oriented. Return ONLY the reply text, no JSON or formatting.';

        $result = $this->callAI($systemPrompt, "Customer message:\n\n{$originalMessage}", 0.7, 8192, 'crm');

        return $result['success'] ? $result['content'] : '';
    }

    /**
     * Generate a content plan.
     */
    public function generateContentPlan(string $topic, int $items = 5, string $language = 'nl'): array
    {
        $systemPrompt = 'You are a content strategist. Generate a content plan as a JSON array. '
            .'Each item should have: title (string), type (article|video|podcast), '
            .'category (string), keywords (array), target_funnel_stage (interesseer|overtuig|activeer|inspireer), '
            ."estimated_effort (string like '2h', '4h'). "
            ."Language: {$language}. Generate {$items} content items. "
            .'Return ONLY valid JSON array, no explanations.';

        $result = $this->callAI($systemPrompt, "Create content plan about: {$topic}", 0.7, 8192, 'content');

        if (! $result['success']) {
            return $result;
        }

        $decoded = json_decode($result['content'], true);

        return [
            'success' => true,
            'plan' => is_array($decoded) ? $decoded : [],
            'provider' => $result['provider'] ?? null,
            'model' => $result['model'] ?? null,
            'duration_ms' => $result['duration_ms'] ?? null,
        ];
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
