<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role_id',
        'phone', 'address', 'avatar', 'points', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function donationRequests()
    {
        return $this->hasMany(DonationRequest::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'courier_id');
    }

    public function pointLogs()
    {
        return $this->hasMany(PointLog::class);
    }

    public function appNotifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isDonor(): bool
    {
        return $this->role?->name === 'donor';
    }

    public function isRecipient(): bool
    {
        return $this->role?->name === 'recipient';
    }

    public function isCourier(): bool
    {
        return $this->role?->name === 'courier';
    }

    public function roleName(): string
    {
        return $this->role?->label ?? 'Unknown';
    }

    public function dashboardRoute(): string
    {
        return match($this->role?->name) {
            'admin'     => 'admin.dashboard',
            'donor'     => 'donor.dashboard',
            'recipient' => 'recipient.dashboard',
            'courier'   => 'courier.dashboard',
            default     => 'dashboard',
        };
    }

    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $initials = urlencode(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initials}&background=4f46e5&color=fff&size=128";
    }
}
