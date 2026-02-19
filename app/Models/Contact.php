<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

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
    ];

    protected function casts(): array
    {
        return [
            'payment_due_days' => 'integer',
            'is_customer' => 'boolean',
            'is_supplier' => 'boolean',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
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
