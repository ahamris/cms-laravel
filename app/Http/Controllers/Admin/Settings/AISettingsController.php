<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\AIServiceSetting;
use Illuminate\Http\Request;

class AISettingsController extends AdminBaseController
{
    /**
     * Display the AI settings page.
     */
    public function index()
    {
        $groqSetting = AIServiceSetting::getForService('groq');
        $geminiSetting = AIServiceSetting::getForService('gemini');
        $ollamaSetting = AIServiceSetting::getForService('ollama');

        $settings = [
            'groq_api_key' => $groqSetting->api_key ?? '',
            'groq_model' => $groqSetting->model ?? 'llama-3.3-70b-versatile',
            'groq_is_active' => $groqSetting->is_active ?? false,
            'groq_priority' => $groqSetting->priority ?? 0,
            'gemini_api_key' => $geminiSetting->api_key ?? '',
            'gemini_model' => $geminiSetting->model ?? 'gemini-2.0-flash',
            'gemini_is_active' => $geminiSetting->is_active ?? false,
            'gemini_priority' => $geminiSetting->priority ?? 1,
            'ollama_base_url' => $ollamaSetting->base_url ?? 'http://localhost:11434',
            'ollama_model' => $ollamaSetting->model ?? 'llama3.2',
            'ollama_is_active' => $ollamaSetting->is_active ?? false,
            'ollama_priority' => $ollamaSetting->priority ?? 2,
        ];

        $groqModels = $this->fetchGroqModels($settings['groq_api_key'] ?? null);

        return view('admin.settings.ai.index', compact('settings', 'groqModels'));
    }

    /**
     * Fetch available models from Groq API (GET https://api.groq.com/openai/v1/models).
     * Returns array of ['id' => 'model-id', 'label' => 'Model Id'] for dropdown.
     * Returns empty array if no API key or request fails (view will use static list).
     */
    protected function fetchGroqModels(?string $apiKey): array
    {
        if (empty($apiKey)) {
            return [];
        }

        try {
            $response = \Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->get('https://api.groq.com/openai/v1/models');

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            $list = $data['data'] ?? [];

            $models = [];
            foreach ($list as $model) {
                $id = $model['id'] ?? null;
                if ($id && is_string($id)) {
                    $models[] = ['id' => $id, 'label' => $id];
                }
            }

            return $models;
        } catch (\Exception $e) {
            \Log::debug('Groq fetch models failed', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Update AI settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'groq_api_key' => 'nullable|string|max:255',
            'groq_model' => 'nullable|string|max:100',
            'groq_is_active' => 'boolean',
            'groq_priority' => 'integer|min:0|max:10',
            'gemini_api_key' => 'nullable|string|max:255',
            'gemini_model' => 'nullable|string|max:100',
            'gemini_is_active' => 'boolean',
            'gemini_priority' => 'integer|min:0|max:10',
            'ollama_base_url' => 'nullable|string|max:500',
            'ollama_model' => 'nullable|string|max:100',
            'ollama_is_active' => 'boolean',
            'ollama_priority' => 'integer|min:0|max:10',
        ]);

        try {
            // Update Groq settings
            AIServiceSetting::updateOrCreate(
                ['service' => 'groq'],
                [
                    'api_key' => $validated['groq_api_key'] ?? null,
                    'model' => $validated['groq_model'] ?? 'llama-3.3-70b-versatile',
                    'is_active' => $request->has('groq_is_active') && !empty($validated['groq_api_key']),
                    'priority' => $validated['groq_priority'] ?? 0,
                ]
            );

            // Update Gemini settings
            AIServiceSetting::updateOrCreate(
                ['service' => 'gemini'],
                [
                    'api_key' => $validated['gemini_api_key'] ?? null,
                    'model' => $validated['gemini_model'] ?? 'gemini-2.0-flash',
                    'is_active' => $request->has('gemini_is_active') && !empty($validated['gemini_api_key']),
                    'priority' => $validated['gemini_priority'] ?? 1,
                ]
            );

            // Update Ollama settings (self-hosted; no API key)
            $ollamaBaseUrl = isset($validated['ollama_base_url']) ? rtrim(trim($validated['ollama_base_url']), '/') : null;
            AIServiceSetting::updateOrCreate(
                ['service' => 'ollama'],
                [
                    'api_key' => null,
                    'base_url' => $ollamaBaseUrl,
                    'model' => $validated['ollama_model'] ?? 'llama3.2',
                    'is_active' => $request->has('ollama_is_active') && !empty($ollamaBaseUrl),
                    'priority' => $validated['ollama_priority'] ?? 2,
                ]
            );

            // Log activity
            $this->logSettingsUpdate('AI Settings');

            return redirect()->back()
                ->with('success', 'AI settings updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'An error occurred while updating settings: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Test AI API connection.
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:groq,gemini,ollama',
        ]);

        try {
            $provider = $request->provider;
            $setting = AIServiceSetting::getForService($provider);

            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => ucfirst($provider) . ' service is not configured.',
                ], 400);
            }

            if ($provider === 'ollama') {
                if (!empty($setting->base_url)) {
                    $testResult = $this->performApiTest($provider, $setting->base_url, $setting->model);
                    return response()->json($testResult);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Ollama base URL is not set.',
                ], 400);
            }

            if (!$setting->is_active || empty($setting->api_key)) {
                return response()->json([
                    'success' => false,
                    'message' => ucfirst($provider) . ' service is not configured or not active.',
                ], 400);
            }

            $testResult = $this->performApiTest($provider, $setting->api_key, $setting->model);

            return response()->json($testResult);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Perform actual API test.
     * For Ollama, $apiKey is the base URL.
     */
    protected function performApiTest(string $provider, string $apiKeyOrBaseUrl, string $model): array
    {
        try {
            if ($provider === 'groq') {
                $response = \Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$apiKeyOrBaseUrl}",
                ])->timeout(10)->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'user', 'content' => 'Say "test"'],
                    ],
                    'max_tokens' => 10,
                ]);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'message' => 'Groq API connection successful!',
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Groq API test failed: ' . ($response->json()['error']['message'] ?? 'Unknown error'),
                    ];
                }
            }

            if ($provider === 'ollama') {
                $baseUrl = rtrim($apiKeyOrBaseUrl, '/');
                $response = \Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->timeout(15)->post("{$baseUrl}/api/chat", [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'user', 'content' => 'Say "test"'],
                    ],
                    'stream' => false,
                ]);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'message' => 'Ollama connection successful!',
                    ];
                } else {
                    $body = $response->json();
                    $err = $body['error'] ?? $response->body();
                    return [
                        'success' => false,
                        'message' => 'Ollama test failed: ' . (is_string($err) ? $err : json_encode($err)),
                    ];
                }
            }

            // Gemini
            $response = \Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(10)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKeyOrBaseUrl}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Say test']
                        ]
                    ]
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 10,
                ],
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Gemini API connection successful!',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gemini API test failed: ' . ($response->json()['error']['message'] ?? 'Unknown error'),
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }
}
