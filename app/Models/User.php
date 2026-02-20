<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'email',
        'avatar',
        'password',
        'is_admin',
        'admin_scopes',
        'is_master_admin',
        'phone',
        'country',
        'street_name',
        'building_name',
        'floor_apartment',
        'landmark',
        'city_area',
        'locale',
    ];
    
    /**
     * Casts for attributes.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'admin_scopes' => 'array',
        'is_master_admin' => 'boolean',
        'is_admin' => 'boolean',
    ];
    
    /**
     * Check if user is the master admin.
     */
    public function isMasterAdmin(): bool
    {
        return (bool) ($this->is_master_admin ?? false);
    }
    
    /**
     * Check whether user has the given admin scope.
     * Accepts a single scope name or array of names.
     */
    public function hasAdminScope($scope): bool
    {
        if ($this->isMasterAdmin()) {
            return true;
        }
        
        $scopes = $this->admin_scopes ?? [];
        if (is_array($scope)) {
            foreach ($scope as $s) {
                if (in_array($s, $scopes)) {
                    return true;
                }
            }
            return false;
        }
        
        return in_array($scope, $scopes);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function formatShippingAddress(): string
    {
        $parts = array_filter([
            $this->street_name,
            $this->building_name,
            $this->floor_apartment,
            $this->landmark,
            $this->city_area,
            $this->country,
        ]);
        return implode(', ', $parts) ?: 'No address on file';
    }

    /**
     * Check whether the user has any shipping address fields filled.
     *
     * @return bool
     */
    public function hasShippingAddress(): bool
    {
        return (bool) array_filter([
            $this->street_name,
            $this->building_name,
            $this->floor_apartment,
            $this->landmark,
            $this->city_area,
            $this->country,
        ]);
    }

    /**
     * Returns whether the user's current phone number has been verified via OTP.
     */
    public function hasVerifiedPhone(): bool
    {
        if (empty($this->phone)) return false;
        return \App\Models\Verification::where('phone', $this->phone)
                    ->where('verified', true)
                    ->where('expires_at', '>', now())
                    ->exists();
    }
}
