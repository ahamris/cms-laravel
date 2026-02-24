<?php

namespace App\Models;

use App\Events\ContactFormSubmitted;

/**
 * @mixin IdeHelperContactForm
 */
class ContactForm extends BaseModel
{

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company_name',
        'country_code',
        'reden',
        'bericht',
        'bijlage',
        'contact_preference',
        'avg_optin',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'avg_optin' => 'boolean',
        'bijlage' => 'array',
    ];

    /**
     * All attachment entries (from bijlage JSON: [{"path": "...", "name": "..."}, ...]).
     *
     * @return array<int, array{path: string, name: string}>
     */
    public function getAttachmentListAttribute(): array
    {
        if (empty($this->bijlage) || ! is_array($this->bijlage)) {
            return [];
        }
        return $this->bijlage;
    }

    protected $dispatchesEvents = [
        'created' => ContactFormSubmitted::class,
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'new' => 'badge-primary',
            'contacted' => 'badge-warning',
            'resolved' => 'badge-success',
            'closed' => 'badge-secondary',
            default => 'badge-light'
        };
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'new' => 'Nieuw',
            'contacted' => 'Gecontacteerd',
            'resolved' => 'Opgelost',
            'closed' => 'Gesloten',
            default => 'Onbekend'
        };
    }

    /**
     * Get all messages for this contact form
     */
    public function messages()
    {
        return $this->hasMany(ContactFormMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get inbound messages (from customer)
     */
    public function inboundMessages()
    {
        return $this->hasMany(ContactFormMessage::class)->where('direction', 'inbound')->orderBy('created_at', 'desc');
    }

    /**
     * Get outbound messages (from admin)
     */
    public function outboundMessages()
    {
        return $this->hasMany(ContactFormMessage::class)->where('direction', 'outbound')->orderBy('created_at', 'desc');
    }
}
