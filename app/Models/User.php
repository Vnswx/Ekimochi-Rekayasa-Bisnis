<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'profile_photo',
        'avatar_color',
        'google_id',
        'google_token',
        'google_refresh_token',
        'notify_promo',
        'notify_order',
        'notify_product',
        'notify_outlet',
        'notify_email',
        'notify_whatsapp',
        'notify_push',
    ];

    /**
     * Nilai bawaan preferensi notifikasi bila kolom belum terisi.
     */
    public const NOTIFICATION_DEFAULTS = [
        'promo' => true,
        'order' => true,
        'product' => true,
        'outlet' => false,
        'email' => true,
        'whatsapp' => false,
        'push' => true,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'notify_promo' => 'boolean',
        'notify_order' => 'boolean',
        'notify_product' => 'boolean',
        'notify_outlet' => 'boolean',
        'notify_email' => 'boolean',
        'notify_whatsapp' => 'boolean',
        'notify_push' => 'boolean',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Orders placed by this user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Ambil satu preferensi notifikasi ('promo', 'order', 'product',
     * 'outlet', 'email', 'whatsapp', atau 'push').
     */
    public function notificationPreference(string $key): bool
    {
        if (! array_key_exists($key, self::NOTIFICATION_DEFAULTS)) {
            return false;
        }

        $column = 'notify_' . $key;
        $value = $this->getAttribute($column);

        return $value === null ? self::NOTIFICATION_DEFAULTS[$key] : (bool) $value;
    }

    /**
     * Seluruh preferensi notifikasi dalam bentuk array asosiatif.
     *
     * @return array<string, bool>
     */
    public function notificationPreferences(): array
    {
        $preferences = [];

        foreach (array_keys(self::NOTIFICATION_DEFAULTS) as $key) {
            $preferences[$key] = $this->notificationPreference($key);
        }

        return $preferences;
    }

    /**
     * Get the user's avatar URL or generate default avatar.
     */
    public function getAvatarAttribute(): string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        // Generate default avatar with first letter of name
        $initial = strtoupper(substr($this->name, 0, 1));
        $color = $this->avatar_color ?? $this->generateAvatarColor();
        
        return "https://ui-avatars.com/api/?name={$initial}&background={$color}&color=fff&size=200";
    }

    /**
     * Get the first letter of the user's name.
     */
    public function getInitialAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    /**
     * Generate a pastel color for avatar.
     */
    public function generateAvatarColor(): string
    {
        $pastelColors = [
            'FFB3BA', // pastel pink
            'C7B3FF', // pastel purple
            'B3D9FF', // pastel blue
            'B3FFB3', // pastel green
            'FFFFB3', // pastel yellow
            'FFD4B3', // pastel orange
        ];

        // Use user ID to consistently generate the same color
        $index = $this->id % count($pastelColors);
        return $pastelColors[$index];
    }

    /**
     * Ensure avatar color is set when user is created.
     */
    protected static function booted()
    {
        static::created(function ($user) {
            if (!$user->avatar_color) {
                $user->avatar_color = $user->generateAvatarColor();
                $user->save();
            }
        });
    }
}
