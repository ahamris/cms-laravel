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
     * Call AI with fallback (Groq first, then Gemini)
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

        // Try services in priority order
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
            }
        }

        return [
            'success' => false,
            'error' => 'All configured AI services failed. Please check your API keys in Admin → Settings → AI Settings.',
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

