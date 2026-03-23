<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;

class FaqElementController extends BaseTypedElementController
{
    protected function type(): ElementType
    {
        return ElementType::Faq;
    }

    protected function heading(): string
    {
        return 'FAQ Elements';
    }

    protected function routeBase(): string
    {
        return 'admin.element-faq';
    }

    protected function typeHelp(): string
    {
        return 'Use options JSON with items (question/answer pairs), plus optional layout and columns.';
    }
}
