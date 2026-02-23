<?php

namespace App\Models;

use App\Helpers\Variable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperOrganization
 */
class Organization extends BaseModel
{
    use HasFactory;

    public const CACHE_KEY = 'organizations';

    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'logo',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::created(fn () => Cache::forget(self::CACHE_KEY));
        static::updated(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Return cached array of organizations with name and logo (and logo_url).
     *
     * @return array<int, array{name: string, logo: string|null, logo_url: string|null}>
     */
    public static function getCached(): array
    {
        return Cache::remember(self::CACHE_KEY, Variable::CACHE_TTL, function () {
            return self::query()
                ->orderBy('name')
                ->get()
                ->map(fn (self $org) => [
                    'id' => $org->id,
                    'name' => $org->name,
                    'logo' => $org->logo,
                    'logo_url' => $org->logo_url,
                ])
                ->toArray();
        });
    }

    /**
     * Logo URL accessor (storage path to public URL).
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        return get_image('storage/' . $this->logo);
    }
}
