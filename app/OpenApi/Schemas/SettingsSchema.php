<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ThemeSettings',
    title: 'Theme settings',
    description: 'Theme colors and typography from admin Theme Settings. Used for header, footer, and brand styling on the frontend.',
    properties: [
        new OA\Property(property: 'base_color', type: 'string', nullable: true, description: 'Admin theme base color'),
        new OA\Property(property: 'accent_color', type: 'string', nullable: true, description: 'Admin theme accent color'),
        new OA\Property(property: 'primary_color', type: 'string', description: 'Primary brand color (hex)'),
        new OA\Property(property: 'secondary_color', type: 'string', description: 'Secondary/accent color (hex)'),
        new OA\Property(property: 'natural_color', type: 'string', description: 'Neutral/natural color (hex)'),
        new OA\Property(property: 'footer_bg', type: 'string', description: 'Footer background color (hex)'),
        new OA\Property(property: 'footer_text', type: 'string', description: 'Footer text color (hex)'),
        new OA\Property(property: 'header_bg', type: 'string', description: 'Header background color (hex)'),
        new OA\Property(property: 'header_text', type: 'string', description: 'Header text color (hex)'),
        new OA\Property(property: 'font_sans', type: 'string', description: 'Primary sans-serif font family'),
        new OA\Property(property: 'font_outfit', type: 'string', description: 'Heading font family (e.g. Outfit)'),
        new OA\Property(property: 'font_size_h1', type: 'string', description: 'H1 font size (e.g. 3rem)'),
        new OA\Property(property: 'font_size_h2', type: 'string', description: 'H2 font size'),
        new OA\Property(property: 'font_size_h3', type: 'string', description: 'H3 font size'),
        new OA\Property(property: 'font_size_h4', type: 'string', description: 'H4 font size'),
        new OA\Property(property: 'font_size_h5', type: 'string', description: 'H5 font size'),
        new OA\Property(property: 'font_size_h6', type: 'string', description: 'H6 font size'),
        new OA\Property(property: 'font_size_p', type: 'string', description: 'Paragraph font size'),
    ]
)]
#[OA\Schema(
    schema: 'SettingsResponse',
    title: 'Site settings response',
    description: 'Grouped site, theme, SEO, contact/map, cookie, hero, header and footer settings for frontend.',
    properties: [
        new OA\Property(property: 'site', type: 'object', description: 'Site name, tagline, logo, favicon, contact, copyright'),
        new OA\Property(property: 'theme', ref: '#/components/schemas/ThemeSettings'),
        new OA\Property(property: 'seo', type: 'object', description: 'Meta title, description, keywords, Google Analytics ID'),
        new OA\Property(property: 'contact', type: 'object', description: 'Map latitude, longitude, zoom'),
        new OA\Property(property: 'cookie', type: 'object', description: 'Cookie banner and preference labels/URLs'),
        new OA\Property(property: 'hero', type: 'object', description: 'Hero background image URLs by section'),
        new OA\Property(property: 'header', type: 'object', description: 'Header CTA button text and URL'),
        new OA\Property(property: 'organizations', type: 'array', description: 'Cached organizations'),
        new OA\Property(property: 'external_codes', type: 'array', description: 'Cached external code snippets'),
    ]
)]
class SettingsSchema
{
}
