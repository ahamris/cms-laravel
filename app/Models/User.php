<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Helpers\Variable;
use App\Models\Traits\ImageGetterTrait;
use App\Notifications\CustomVerifyEmailNotification;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasFactory, HasPermissions, HasRoles, ImageGetterTrait, MustVerifyEmail, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'avatar',
        'secondary_email',
        'otp_code',
        'email_verified_at',
        'two_factor_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Check if the user is a super admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
        ];
    }

    public function sendEmailVerificationNotification()
    {
        if ($this->hasRole(Variable::ROLE_ADMIN)) {
            return;
        }

        $this->notify(new CustomVerifyEmailNotification);
    }


    public function ShortName(): Attribute
    {
        $name = $this->name ?? $this->first_name;
        $lastName = $this->last_name;

        if (!empty($lastName)) {
            $lastNameInitial = Str::substr($lastName, 0, 1);
            $result = $name . ' ' . ucfirst($lastNameInitial) . '.';
        } else {
            $result = $name;
        }

        return Attribute::get(
            fn() => $result
        );

    }

    public function FullName(): Attribute
    {
        $result = $name = $this->name ?? $this->first_name;
        $lastName = $this->last_name;

        if ($lastName) {
            $result = $name . ' ' . $lastName;
        }

        return Attribute::get(
            fn() => $result
        );
    }

    /**
     * Get the user's two factor recovery codes.
     */
    public function getRecoveryCodesAttribute(): array
    {
        return json_decode(decrypt($this->two_factor_recovery_codes), true) ?? [];
    }

    /**
     * Set the user's two factor recovery codes.
     */
    public function setRecoveryCodesAttribute(array $codes): void
    {
        $this->attributes['two_factor_recovery_codes'] = encrypt(json_encode($codes));
    }

    /**
     * Generate new recovery codes.
     */
    public function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtolower(Str::random(5) . '-' . Str::random(5));
        }
        
        $this->setRecoveryCodesAttribute($codes);
        $this->save();
        
        return $codes;
    }

    /**
     * Use a recovery code.
     */
    public function useRecoveryCode(string $code): bool
    {
        $codes = $this->getRecoveryCodesAttribute();
        
        if (($key = array_search(strtolower($code), $codes)) !== false) {
            unset($codes[$key]);
            $this->setRecoveryCodesAttribute(array_values($codes));
            $this->save();
            return true;
        }
        
        return false;
    }

    /**
     * Check if two factor authentication is enabled.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled && !is_null($this->two_factor_secret);
    }

    /**
     * Get the two factor authentication secret.
     */
    public function getTwoFactorSecret(): ?string
    {
        return $this->two_factor_secret ? decrypt($this->two_factor_secret) : null;
    }

    /**
     * Set the two factor authentication secret.
     */
    public function setTwoFactorSecret(string $secret): void
    {
        $this->two_factor_secret = encrypt($secret);
    }

    /**
     * Enable two factor authentication.
     */
    public function enableTwoFactor(string $secret): void
    {
        $this->setTwoFactorSecret($secret);
        $this->two_factor_enabled = true;
        $this->two_factor_confirmed_at = now();
        $this->generateRecoveryCodes();
        $this->save();
    }

    /**
     * Disable two factor authentication.
     */
    public function disableTwoFactor(): void
    {
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->two_factor_confirmed_at = null;
        $this->two_factor_enabled = false;
        $this->save();
    }
}
