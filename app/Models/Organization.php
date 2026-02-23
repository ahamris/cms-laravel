<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperOrganization
 */
class Organization extends BaseModel
{
    use HasFactory;

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

    /**
     * Logo URL accessor (storage path to public URL).
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        return asset('storage/' . $this->logo);
    }
}
