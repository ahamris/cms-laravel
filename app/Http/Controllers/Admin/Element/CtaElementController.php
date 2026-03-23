<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;
use Illuminate\Http\Request;

class CtaElementController extends BaseTypedElementController
{
    protected function type(): ElementType
    {
        return ElementType::Cta;
    }

    protected function heading(): string
    {
        return 'CTA Elements';
    }

    protected function routeBase(): string
    {
        return 'admin.element-cta';
    }

    protected function optionsFormView(): string
    {
        return 'admin.elements.forms.cta-options';
    }

    protected function showOptionsView(): string
    {
        return 'admin.elements.show.cta-options';
    }

    protected function typeHelp(): string
    {
        return 'Configure the button, link, and visual style for this call-to-action block.';
    }

    protected function validateOptions(Request $request): array
    {
        return $request->validate([
            'options.button_text' => 'nullable|string|max:255',
            'options.button_url' => 'nullable|string|max:500',
            'options.button_style' => 'required|in:primary,secondary',
            'options.background' => 'required|in:gradient,light,dark,none',
            'options.alignment' => 'required|in:left,center,right',
        ])['options'];
    }
}
