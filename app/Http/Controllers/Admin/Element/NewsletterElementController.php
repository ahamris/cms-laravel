<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;
use Illuminate\Http\Request;

class NewsletterElementController extends BaseTypedElementController
{
    private const SUBMIT_ENDPOINT = '/api/newsletter/subscribe';

    protected function type(): ElementType
    {
        return ElementType::Newsletter;
    }

    protected function heading(): string
    {
        return 'Newsletter elements';
    }

    protected function routeBase(): string
    {
        return 'admin.element-newsletter';
    }

    protected function optionsFormView(): string
    {
        return 'admin.elements.forms.newsletter-options';
    }

    protected function showOptionsView(): string
    {
        return 'admin.elements.show.newsletter-options';
    }

    protected function typeHelp(): string
    {
        return 'Newsletter signup block with email field and submit endpoint for the frontend.';
    }

    protected function validateOptions(Request $request): array
    {
        $options = $request->validate([
            'options.email_placeholder' => 'nullable|string|max:255',
            'options.button_text' => 'nullable|string|max:255',
            'options.terms_text' => 'nullable|string|max:1000',
        ])['options'];

        $options['submit_endpoint'] = self::SUBMIT_ENDPOINT;

        return $options;
    }
}
