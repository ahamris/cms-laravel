<?php

namespace App\Models;

/**
 * @mixin IdeHelperHeroMediaWidget
 */
class HeroMediaWidget extends BaseModel
{
    protected $table = 'hero_media_widgets';

    protected $fillable = [
        // Top Header
        'top_header_icon',
        'top_header_text',
        'top_header_url',
        'top_header_text_color',
        'top_header_bg_color',

        // Title & Subtitle
        'title',
        'title_color',
        'subtitle',
        'subtitle_color',

        // Slogan
        'slogan',
        'slogan_color',

        // List Items
        'list_items',
        'list_item_color',
        'list_item_icon',

        // Primary Button
        'primary_button_text',
        'primary_button_url',
        'primary_button_text_color',
        'primary_button_bg_color',
        'primary_button_icon',

        // Secondary Button
        'secondary_button_text',
        'secondary_button_url',
        'secondary_button_text_color',
        'secondary_button_bg_color',
        'secondary_button_border_color',
        'secondary_button_icon',

        // Component Settings
        'component_type',
        'height',
        'full_height',

        // Background
        'background_type',
        'video_url',
        'image',

        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'list_items' => 'array',
            'full_height' => 'boolean',
            'is_active' => 'boolean',
            'height' => 'integer',
        ];
    }

    /**
     * Get the first active hero widget (for backward compatibility)
     */
    public static function getInstance()
    {
        return static::where('is_active', true)->first();
    }
}

