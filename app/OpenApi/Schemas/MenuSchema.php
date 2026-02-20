<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MenuItem',
    title: 'Menu item',
    description: 'Header menu tree node (id, title, subtitle, description, url, order, children)',
    properties: [
        new OA\Property(property: 'id', type: 'integer', nullable: true),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'subtitle', type: 'string', nullable: true),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'order', type: 'integer'),
        new OA\Property(property: 'children', type: 'array', items: new OA\Items(ref: '#/components/schemas/MenuItem')),
    ]
)]
#[OA\Schema(
    schema: 'HeaderMenuResponse',
    title: 'Header menu response',
    properties: [
        new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: '#/components/schemas/MenuItem')),
        new OA\Property(property: 'settings', type: 'object', properties: [
            new OA\Property(property: 'sticky', type: 'boolean'),
            new OA\Property(property: 'login_link_enabled', type: 'boolean'),
            new OA\Property(property: 'login_link_url', type: 'string'),
        ]),
    ]
)]
#[OA\Schema(
    schema: 'FooterLinkItem',
    title: 'Footer link',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'url', type: 'string'),
        new OA\Property(property: 'order', type: 'integer'),
    ]
)]
#[OA\Schema(
    schema: 'FooterColumn',
    title: 'Footer column',
    properties: [
        new OA\Property(property: 'column', type: 'integer'),
        new OA\Property(property: 'links', type: 'array', items: new OA\Items(ref: '#/components/schemas/FooterLinkItem')),
    ]
)]
#[OA\Schema(
    schema: 'FooterMenuResponse',
    title: 'Footer menu response',
    properties: [
        new OA\Property(property: 'columns', type: 'array', items: new OA\Items(ref: '#/components/schemas/FooterColumn')),
    ]
)]
#[OA\Schema(
    schema: 'StickyMenuItem',
    title: 'Sticky menu item',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'icon', type: 'string'),
        new OA\Property(property: 'link', type: 'string'),
        new OA\Property(property: 'link_type', type: 'string'),
        new OA\Property(property: 'is_external', type: 'boolean'),
        new OA\Property(property: 'sort_order', type: 'integer'),
    ]
)]
class MenuSchema
{
}
