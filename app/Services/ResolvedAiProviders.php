<?php

namespace App\Services;

use App\Models\AIServiceSetting;

final class ResolvedAiProviders
{
    /** Preferred failover order for long-form / CMS content (Laravel AI `provider` map preserves key order). */
    public const array TASK_CONTENT_ORDER = ['gemini', 'openai', 'anthropic', 'groq', 'ollama'];

    /** Preferred order for SEO-style analysis (lighter models first when configured). */
    public const array TASK_SEO_ORDER = ['gemini', 'openai', 'anthropic', 'groq', 'ollama'];

    /** CRM / support agents share content-style ordering. */
    public const array TASK_CRM_ORDER = ['gemini', 'openai', 'anthropic', 'groq', 'ollama'];

    /**
     * Provider name => model id for Laravel AI failover (matches config ai.providers keys).
     *
     * @return array<string, string>
     */
    public static function providerModelMap(): array
    {
        $map = [];

        foreach (AIServiceSetting::getActiveServices() as $setting) {
            $name = AIServiceSetting::normalizeServiceName((string) $setting->service);
            if (! in_array($name, AIServiceSetting::SUPPORTED_SERVICES, true)) {
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
                'openai' => 'gpt-4o-mini',
                'anthropic' => 'claude-3-5-sonnet-latest',
                default => null,
            };

            $model = AIServiceSetting::getModel($name, $default);
            if (is_string($model) && $model !== '') {
                $map[$name] = $model;
            }
        }

        return $map;
    }

    /**
     * Same as {@see providerModelMap()} but with task-specific provider order; unknown configured providers are appended.
     *
     * @param  'content'|'seo'|'crm'  $task
     * @return array<string, string>
     */
    public static function providerModelMapForTask(string $task): array
    {
        $full = self::providerModelMap();
        if ($full === []) {
            return [];
        }

        $order = match ($task) {
            'seo' => self::TASK_SEO_ORDER,
            'crm' => self::TASK_CRM_ORDER,
            default => self::TASK_CONTENT_ORDER,
        };

        $ordered = [];
        foreach ($order as $name) {
            if (isset($full[$name])) {
                $ordered[$name] = $full[$name];
            }
        }
        foreach ($full as $name => $model) {
            if (! array_key_exists($name, $ordered)) {
                $ordered[$name] = $model;
            }
        }

        return $ordered;
    }
}
