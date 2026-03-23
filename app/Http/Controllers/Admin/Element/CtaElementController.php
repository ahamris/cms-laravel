<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;

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

    protected function typeHelp(): string
    {
        return 'Use options JSON with keys such as button_text, button_url, button_style, background, and alignment.';
    }
}
