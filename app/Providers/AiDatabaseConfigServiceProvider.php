<?php

namespace App\Providers;

use App\Models\AIServiceSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AiDatabaseConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! Schema::hasTable('ai_service_settings')) {
            return;
        }

        $firstDefault = null;

        foreach (AIServiceSetting::getActiveServices() as $setting) {
            $name = $setting->service;
            if (! in_array($name, ['groq', 'gemini', 'ollama'], true)) {
                continue;
            }

            $providers = config('ai.providers', []);
            if (! isset($providers[$name])) {
                continue;
            }

            if ($name === 'ollama') {
                if ($setting->base_url) {
                    Config::set('ai.providers.ollama.url', rtrim($setting->base_url, '/'));
                }
                if ($setting->api_key) {
                    Config::set('ai.providers.ollama.key', $setting->api_key);
                }
            } elseif ($setting->api_key) {
                Config::set("ai.providers.{$name}.key", $setting->api_key);
            }

            $firstDefault ??= $name;
        }

        if ($firstDefault !== null) {
            Config::set('ai.default', $firstDefault);
        }
    }
}
