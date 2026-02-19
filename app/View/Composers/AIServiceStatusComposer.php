<?php

namespace App\View\Composers;

use App\Models\AIServiceSetting;
use Illuminate\View\View;

class AIServiceStatusComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Check if any AI service is active
        $activeServices = AIServiceSetting::getActiveServices();
        $hasActiveService = $activeServices->isNotEmpty();

        // Get service statuses
        $groqActive = AIServiceSetting::isServiceActive('groq');
        $geminiActive = AIServiceSetting::isServiceActive('gemini');

        $view->with([
            'aiServiceConfigured' => $hasActiveService,
            'groqServiceActive' => $groqActive,
            'geminiServiceActive' => $geminiActive,
            'aiServiceWarning' => !$hasActiveService ? 'No AI service is configured. Please configure at least one AI service in Settings → AI Settings to use content generation features.' : null,
        ]);
    }
}

