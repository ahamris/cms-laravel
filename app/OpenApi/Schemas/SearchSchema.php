<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SearchSuggestionItem',
    title: 'Search suggestion',
    properties: [
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'type', type: 'string', example: 'Oplossing'),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'icon', type: 'string', example: 'fa-briefcase'),
    ]
)]
#[OA\Schema(
    schema: 'SearchSuggestionsResponse',
    title: 'Search suggestions response',
    properties: [
        new OA\Property(property: 'suggestions', type: 'array', items: new OA\Items(ref: '#/components/schemas/SearchSuggestionItem')),
        new OA\Property(property: 'mostSearched', type: 'array', items: new OA\Items(
            properties: [
                new OA\Property(property: 'term', type: 'string'),
                new OA\Property(property: 'icon', type: 'string'),
                new OA\Property(property: 'url', type: 'string', format: 'uri'),
            ]
        )),
    ]
)]
class SearchSchema
{
}
