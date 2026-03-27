<?php

namespace App\View\Composers;

use App\Models\AIServiceSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AIServiceStatusComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $state = Cache::remember('admin.ai.service-status.v1', now()->addMinutes(2), function (): array {
            $activeServices = AIServiceSetting::getActiveServices();
            $hasActiveService = $activeServices->isNotEmpty();

            return [
                'aiServiceConfigured' => $hasActiveService,
                'groqServiceActive' => AIServiceSetting::isServiceActive('groq'),
                'geminiServiceActive' => AIServiceSetting::isServiceActive('gemini'),
                'aiServiceWarning' => ! $hasActiveService
                    ? 'No AI service is configured. Please configure at least one AI service in Settings → AI Settings to use content generation features.'
                    : null,
            ];
        });

        $view->with($state);
    }
}

