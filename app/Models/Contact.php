<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperContact
 */
class Contact extends BaseModel
{
    use Sluggable;

    protected $table = 'contacts';

    protected $fillable = [
        // identity
        'organization_name', 'slug', 'alias', 'email', 'phone', 'website',
        // registration / tax
        'chamber_of_commerce', 'tax_number',
        // invoicing
        'invoice_email', 'invoice_email_cc', 'invoice_email_bcc', 'payment_due_days', 'currency', 'preferred_language',
        // billing
        'billing_attention', 'billing_street', 'billing_house_number', 'billing_zipcode', 'billing_city', 'billing_region', 'billing_country',
        // shipping
        'shipping_attention', 'shipping_street', 'shipping_house_number', 'shipping_zipcode', 'shipping_city', 'shipping_region', 'shipping_country',
        // banking
        'iban', 'bic',
        // flags
        'is_customer', 'is_supplier', 'is_active',
        // notes
        'notes',
        // CRM fields
        'funnel_fase', 'lead_source', 'lead_score', 'lifecycle_stage',
        'company_name', 'job_title', 'avatar_url', 'last_activity_at', 'tags',
    ];

    protected function casts(): array
    {
        return [
            'payment_due_days'  => 'integer',
            'is_customer'       => 'boolean',
            'is_supplier'       => 'boolean',
            'is_active'         => 'boolean',
            'lead_score'        => 'integer',
            'last_activity_at'  => 'datetime',
            'tags'              => 'array',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }

    public function deals(): HasMany
    {
        return $this->hasMany(CrmDeal::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(CrmTicket::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(CrmAppointment::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(CrmNote::class);
    }

    public function formSubmissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class, 'converted_contact_id');
    }

    public function scopeByFunnel($query, string $fase)
    {
        return $query->where('funnel_fase', $fase);
    }

    public function scopeByLifecycle($query, string $stage)
    {
        return $query->where('lifecycle_stage', $stage);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'organization_name',
                'maxLength' => 255,
                'separator' => '-',
                'includeTrashed' => true,
            ],
        ];
    }
}
