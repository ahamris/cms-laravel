<?php

namespace App\Http\Controllers\Admin\Element;

use App\Enums\ElementType;

class RelatedContentElementController extends BaseTypedElementController
{
    protected function type(): ElementType
    {
        return ElementType::RelatedContent;
    }

    protected function heading(): string
    {
        return 'Related Content Elements';
    }

    protected function routeBase(): string
    {
        return 'admin.element-related-content';
    }

    protected function typeHelp(): string
    {
        return 'Use options JSON to store your related content references and presentation settings.';
    }
}
