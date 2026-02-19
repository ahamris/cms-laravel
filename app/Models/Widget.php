<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperWidget
 */
class Widget extends BaseModel
{
    const CACHE_KEY = 'widgets';

    protected $fillable = [
        'section_identifier',
        'template',
        'template_parameter',
        'template_parameter_id',
        'title',
        'subtitle',
        'content',
        'button_text',
        'button_url',
        'button_external',
        'image',
        'background_color',
        'text_color',
        'is_active',
        'sort_order',
        'meta_data',
    ];

    protected $casts = [
        'button_external' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'template_parameter_id' => 'integer',
        'meta_data' => 'array',
    ];

    /**
     * Boot method to handle cache invalidation
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when model is created, updated, or deleted
        static::created(fn () => self::clearCache());
        static::saved(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }

    /**
     * Get all cached widgets
     */
    public static function getCached()
    {
        return Cache::remember(self::CACHE_KEY, 86400, function () {
            return self::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('created_at')
                ->get();
        });
    }

    /**
     * Get widgets by section identifier with caching
     */
    public static function getBySection(string $sectionIdentifier)
    {
        $cacheKey = "widgets_section_{$sectionIdentifier}";

        return Cache::remember($cacheKey, 86400, function () use ($sectionIdentifier) {
            return self::where('section_identifier', $sectionIdentifier)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('created_at')
                ->get();
        });
    }

    /**
     * Clear all widget caches
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);

        if (self::query()->doesntExist()) {
            return;
        }

        // Clear individual section caches
        $sections = self::query()->distinct()->pluck('section_identifier');
        foreach ($sections as $section) {
            Cache::forget("widgets_section_{$section}");
        }
    }

    /**
     * Scope for active widgets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered widgets
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Scope for specific section
     */
    public function scopeForSection($query, string $sectionIdentifier)
    {
        return $query->where('section_identifier', $sectionIdentifier);
    }

    /**
     * Get the target attribute for buttons
     */
    public function getButtonTargetAttribute()
    {
        return $this->button_external ? '_blank' : '_self';
    }

    /**
     * Check if the widget has a button
     */
    public function hasButton()
    {
        return ! empty($this->button_text) && ! empty($this->button_url);
    }

    /**
     * Get available templates
     */
    public static function getAvailableTemplates()
    {
        return [
            'hero' => 'Hero Classic',
            'hero-media' => 'Hero Media',
            'solution-feature' => 'Feature Block (2-Column)',
            'cta' => 'CTA Block',
            'about' => 'About Us Section',
            'service-grid-3-box' => '6 Grid Boxes',
            'knowledge-grid-3-box' => '5 Grid Boxes',
            'module-list-widget' => 'Module List Widget',
            'changelog' => 'Changelog Section',
            'changelog-all' => 'Changelog Section All',
            'changelog-api' => 'Changelog Section API',
            'blog' => 'News & Articles',
            'carousel-widget' => 'Carousel Widget',
            'contact' => 'Form Section',
            'faq' => 'FAQ Section',
            //            'stats' => 'Statistics',
            //            'team' => 'Team Members',
            //            'gallery' => 'Image Gallery',
            //            'text' => 'Text Content',
            //            'video' => 'Video Section',
        ];
    }

    /**
     * Get template parameter requirements
     */
    public static function getTemplateParameters()
    {
        return [
            'hero' => [
                'parameter_name' => 'widget_data_id',
                'parameter_label' => 'Select Hero Section',
                'model' => 'WidgetData',
                'display_field' => 'name',
                'value_field' => 'id',
                'where' => [
                    ['widget_type', '=', 'hero'],
                    ['is_active', '=', true]
                ],
            ],
            'hero-media' => [
                'parameter_name' => 'widget_data_id',
                'parameter_label' => 'Select Hero Media Widget',
                'model' => 'WidgetData',
                'display_field' => 'name',
                'value_field' => 'id',
                'where' => [
                    ['widget_type', '=', 'hero-media'],
                    ['is_active', '=', true]
                ],
            ],
            'solution-feature' => [
                'parameter_name' => 'widget_data_id',
                'parameter_label' => 'Select Feature Block',
                'model' => 'WidgetData',
                'display_field' => 'name',
                'value_field' => 'id',
                'where' => [
                    ['widget_type', '=', 'solution-feature'],
                    ['is_active', '=', true]
                ],
            ],
            'service-grid-3-box' => [
                'parameter_name' => 'widget_data_id',
                'parameter_label' => 'Select Solutions Grid',
                'model' => 'WidgetData',
                'display_field' => 'name',
                'value_field' => 'id',
                'where' => [
                    ['widget_type', '=', 'service-grid-3-box'],
                    ['is_active', '=', true]
                ],
            ],
            'knowledge-grid-3-box' => [
                'parameter_name' => 'widget_data_id',
                'parameter_label' => 'Select Knowledge Grid',
                'model' => 'WidgetData',
                'display_field' => 'name',
                'value_field' => 'id',
                'where' => [
                    ['widget_type', '=', 'knowledge-grid-3-box'],
                    ['is_active', '=', true]
                ],
            ],
            'module-list-widget' => [
                'parameter_name' => 'widget_data_id',
                'parameter_label' => 'Select Module List Widget',
                'model' => 'WidgetData',
                'display_field' => 'name',
                'value_field' => 'id',
                'where' => [
                    ['widget_type', '=', 'module-list-widget'],
                    ['is_active', '=', true]
                ],
            ],
            'faq' => [
                'parameter_name' => 'faq_identifier',
                'parameter_label' => 'Select FAQ Module',
                'model' => 'Faq',
                'display_field' => 'title',
                'value_field' => 'identifier',
                'where' => ['identifier' => ['operator' => 'whereNotNull']],
            ],
            'cta' => [
                'parameter_name' => 'widget_data_identifier',
                'parameter_label' => 'Select Call to Action',
                'model' => 'WidgetData',
                'display_field' => 'name',
                'value_field' => 'identifier',
                'where' => [
                    ['widget_type', '=', 'cta'],
                    ['is_active', '=', true]
                ],
            ],
            'contact' => [
                'parameter_name' => 'widget_data_identifier',
                'parameter_label' => 'Select Contact Form',
                'model' => 'WidgetData',
                'display_field' => 'name',
                'value_field' => 'identifier',
                'where' => [
                    ['widget_type', '=', 'contact'],
                    ['is_active', '=', true]
                ],
            ],
            'blog' => [
                'parameter_name' => 'blog_category_id',
                'parameter_label' => 'Select Blog Category (Optional)',
                'model' => 'BlogCategory',
                'display_field' => 'name',
                'value_field' => 'id',
                'where' => ['is_active' => true],
            ],
            'carousel-widget' => [
                'parameter_name' => 'widget_data_id',
                'parameter_label' => 'Select Carousel Widget',
                'model' => 'WidgetData',
                'display_field' => 'name',
                'value_field' => 'id',
                'where' => [
                    ['widget_type', '=', 'carousel-widget'],
                    ['is_active', '=', true]
                ],
            ],
        ];
    }

    /**
     * Get parameter options for a specific template
     */
    public static function getParameterOptions($template)
    {
        $parameters = self::getTemplateParameters();

        if (! isset($parameters[$template])) {
            return [];
        }

        $config = $parameters[$template];
        $modelClass = "App\\Models\\{$config['model']}";

        if (! class_exists($modelClass)) {
            return [];
        }

        $query = $modelClass::query();

        // Apply where conditions if specified
        if (isset($config['where'])) {
            foreach ($config['where'] as $condition) {
                if (is_array($condition) && count($condition) === 3) {
                    // Handle array format: ['field', 'operator', 'value']
                    $query->where($condition[0], $condition[1], $condition[2]);
                } elseif (is_array($condition) && isset($condition['operator'])) {
                    // Handle special operators
                    if ($condition['operator'] === 'whereNotNull') {
                        $query->whereNotNull($condition[0] ?? key($condition));
                    }
                } elseif (is_array($condition)) {
                    // Handle key-value pairs
                    foreach ($condition as $field => $value) {
                        if (is_array($value) && isset($value['operator'])) {
                            if ($value['operator'] === 'whereNotNull') {
                                $query->whereNotNull($field);
                            }
                        } elseif ($value === null) {
                            $query->whereNull($field);
                        } else {
                            $query->where($field, $value);
                        }
                    }
                } else {
                    // Fallback for simple conditions
                    $query->where($condition);
                }
            }
        }

        return $query->get()->map(function ($item) use ($config) {
            $displayValue = $item->{$config['display_field']};

            return [
                'value' => (string) $item->{$config['value_field']},
                'label' => $displayValue,
            ];
        })->toArray();
    }

    /**
     * Get template display name
     */
    public function getTemplateDisplayNameAttribute()
    {
        $templates = self::getAvailableTemplates();

        return $templates[$this->template] ?? ucfirst($this->template);
    }

    /**
     * Get template icon
     */
    public function getTemplateIcon(): string
    {
        $icons = [
            'hero' => 'star',
            'hero-media' => 'video',
            'solution-feature' => 'columns',
            'cta' => 'bullhorn',
            'about' => 'info-circle',
            'service-grid-3-box' => 'th-large',
            'knowledge-grid-3-box' => 'book',
            'blog' => 'newspaper',
            'carousel-widget' => 'images',
            'changelog' => 'list-alt',
            'changelog-all' => 'list-alt',
            'changelog-api' => 'code',
            'contact' => 'envelope',
            'faq' => 'question-circle',
            'team' => 'users',
            'gallery' => 'images',
            'text' => 'file-text',
        ];

        return $icons[$this->template] ?? 'layer-group';
    }

    /**
     * Get available section identifiers
     */
    public static function getAvailableSections()
    {
        return [
            'homepage' => 'Homepage',
            'service_page' => 'Service Page',
            'about_page' => 'About Page',
            'contact_page' => 'Contact Page',
            'pricing_page' => 'Pricing Page',
        ];
    }

    /**
     * Get available pages with metadata
     */
    public static function getAvailablePages()
    {
        return [
            'homepage' => [
                'name' => 'Home Pagina',
                'description' => 'Manage your homepage sections and hero content',
                'icon' => 'home',
                'route' => 'home',
            ],
            'service_page' => [
                'name' => 'Oplossingen Pagina',
                'description' => 'Manage your services page layout and content',
                'icon' => 'briefcase',
                'route' => 'services',
            ],
            'about_page' => [
                'name' => 'Over Ons Pagina',
                'description' => 'Manage your about page sections',
                'icon' => 'info-circle',
                'route' => 'about',
            ],
            'contact_page' => [
                'name' => 'Contact Pagina',
                'description' => 'Manage your contact page layout',
                'icon' => 'envelope',
                'route' => 'contact',
            ],
            //            'pricing_page' => [
            //                'name' => 'Pricing Page',
            //                'description' => 'Manage your pricing page sections',
            //                'icon' => 'dollar-sign',
            //                'route' => 'pricing',
            //            ],
        ];
    }

    /**
     * Get section display name
     */
    public function getSectionDisplayNameAttribute()
    {
        $sections = self::getAvailableSections();

        return $sections[$this->section_identifier] ?? ucfirst($this->section_identifier);
    }
}
