<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'username', 'bio', 'location', 'email', 'password',
        'address', 'avatar', 'phone', 'phone_verified_at', 'fb_id',
        'notification_prefs', 'privacy_prefs', 'password_changed_at',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'notification_prefs' => 'array',
        'privacy_prefs' => 'array',
    ];

    // ---- Accessors ----

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::exists($this->avatar)) {
            return Storage::url($this->avatar);
        }
        return '';
    }

    public function getVerificationLevelAttribute(): string
    {
        $score = 0;
        if ($this->email_verified_at) $score++;
        if ($this->phone_verified_at) $score++;
        if ($this->avatar) $score++;
        if (Advertisement::where('user_id', $this->id)->where('published', 1)->count() >= 3) $score++;

        if ($score >= 4) return 'gold';
        if ($score >= 2) return 'silver';
        return 'bronze';
    }

    public function getIsPhoneVerifiedAttribute(): bool
    {
        return $this->phone_verified_at !== null;
    }

    // ---- Helpers ----

    public function wantsNotification(string $channel, string $type): bool
    {
        $prefs = $this->notification_prefs ?? [];
        return $prefs[$channel][$type] ?? true;
    }

    public function getPrivacySetting(string $key, $default = null)
    {
        $prefs = $this->privacy_prefs ?? [];
        return $prefs[$key] ?? $default;
    }

    // ---- Relationships ----

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    // ---- Scopes ----

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeWithPhone($query)
    {
        return $query->whereNotNull('phone')->whereNotNull('phone_verified_at');
    }
}
