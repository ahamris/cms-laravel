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
        new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (search-suggestions)'),
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
#[OA\Schema(
    schema: 'SearchResultItem',
    title: 'Search result item',
    properties: [
        new OA\Property(property: 'type', type: 'string', description: 'page, blog, solution, doc, course_video, course_category, changelog'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'excerpt', type: 'string'),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'slug', type: 'string', nullable: true),
        new OA\Property(property: 'anchor', type: 'string', nullable: true, description: 'For solutions'),
        new OA\Property(property: 'version', type: 'string', nullable: true, description: 'For docs'),
        new OA\Property(property: 'section', type: 'string', nullable: true, description: 'For docs'),
    ]
)]
#[OA\Schema(
    schema: 'SearchResponse',
    title: 'Search response',
    properties: [
        new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (search-result)'),
        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/SearchResultItem')),
        new OA\Property(property: 'meta', type: 'object', properties: [
            new OA\Property(property: 'query', type: 'string'),
            new OA\Property(property: 'type', type: 'string'),
            new OA\Property(property: 'total', type: 'integer'),
            new OA\Property(property: 'current_page', type: 'integer'),
            new OA\Property(property: 'last_page', type: 'integer'),
            new OA\Property(property: 'per_page', type: 'integer'),
        ]),
    ]
)]
class SearchSchema
{
}
