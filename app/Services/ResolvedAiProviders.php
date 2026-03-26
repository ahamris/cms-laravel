<?php

namespace App\Services;

use App\Models\AIServiceSetting;

final class ResolvedAiProviders
{
    /**
     * Provider name => model id for Laravel AI failover (matches config ai.providers keys).
     *
     * @return array<string, string>
     */
    public static function providerModelMap(): array
    {
        $map = [];

        foreach (AIServiceSetting::getActiveServices() as $setting) {
            $name = $setting->service;
            if (! in_array($name, ['groq', 'gemini', 'ollama'], true)) {
                continue;
            }

            if ($name === 'ollama' && empty($setting->base_url)) {
                continue;
            }

            if ($name !== 'ollama' && empty($setting->api_key)) {
                continue;
            }

            $default = match ($name) {
                'groq' => 'llama-3.3-70b-versatile',
                'gemini' => 'gemini-2.0-flash',
                'ollama' => 'llama3.2',
                default => null,
            };

            $model = AIServiceSetting::getModel($name, $default);
            if (is_string($model) && $model !== '') {
                $map[$name] = $model;
            }
        }

        return $map;
    }
}
