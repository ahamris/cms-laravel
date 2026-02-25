<?php

namespace App\Models;

use Illuminate\Support\Facades\Route;

class PartnerTechItem extends BaseModel
{

    public const TYPE_PARTNER = 0;
    public const TYPE_TECH_STACK = 1;

    protected $fillable = [
        'name',
        'banner',
        'title',
        'description',
        'type',
        'data',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'integer',
            'data' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopePartners($query)
    {
        return $query->where('type', self::TYPE_PARTNER);
    }

    public function scopeTechStack($query)
    {
        return $query->where('type', self::TYPE_TECH_STACK);
    }

    /**
     * Resolve link URL for a single data item (external URL or API static page URL).
     */
    public static function resolveLinkUrl(string $link, string $linkType = 'external'): ?string
    {
        if (empty($link)) {
            return null;
        }
        if ($linkType === 'static' && Route::has('api.static.show')) {
            return route('api.static.show', ['slug' => $link]);
        }

        return $link;
    }

    /**
     * Data array with resolved URL and image URL for each item.
     *
     * @return array<int, array{link: ?string, link_type: string, image: ?string, sort_order: int, url: ?string, image_url: ?string}>
     */
    public function getDataItemsResolvedAttribute(): array
    {
        $data = $this->data;
        if (! is_array($data)) {
            return [];
        }
        $items = [];
        foreach ($data as $index => $item) {
            if (! is_array($item)) {
                continue;
            }
            $link = $item['link'] ?? null;
            $linkType = $item['link_type'] ?? 'external';
            $image = $item['image'] ?? null;
            $sortOrder = isset($item['sort_order']) ? (int) $item['sort_order'] : $index;
            $items[] = [
                'link' => $link,
                'link_type' => $linkType,
                'image' => $image,
                'sort_order' => $sortOrder,
                'url' => self::resolveLinkUrl((string) $link, $linkType),
                'image_url' => $image ? get_image($image) : null,
            ];
        }
        usort($items, fn ($a, $b) => $a['sort_order'] <=> $b['sort_order']);

        return $items;
    }

    /**
     * Banner image URL.
     */
    public function getBannerUrlAttribute(): ?string
    {
        $banner = $this->banner;
        if (empty($banner)) {
            return null;
        }

        return get_image($banner);
    }
}
