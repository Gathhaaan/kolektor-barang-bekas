<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Donation extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title', 'description',
        'condition', 'photos', 'pickup_address', 'status',
        'rejection_reason', 'admin_note', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'photos'      => 'array',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function donor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function requests()
    {
        return $this->hasMany(DonationRequest::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }

    public function pointLogs()
    {
        return $this->hasMany(PointLog::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Helpers
    public function conditionLabel(): string
    {
        return match($this->condition) {
            'baru'        => 'Baru',
            'sangat_baik' => 'Sangat Baik',
            'baik'        => 'Baik',
            'cukup_baik'  => 'Cukup Baik',
            default       => $this->condition,
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'   => 'Menunggu Verifikasi',
            'approved'  => 'Disetujui',
            'rejected'  => 'Ditolak',
            'assigned'  => 'Kurir Ditugaskan',
            'picked_up' => 'Sudah Diambil',
            'delivered' => 'Sudah Dikirim',
            'completed' => 'Selesai',
            default     => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'pending'   => 'amber',
            'approved'  => 'blue',
            'rejected'  => 'red',
            'assigned'  => 'purple',
            'picked_up' => 'indigo',
            'delivered' => 'teal',
            'completed' => 'green',
            default     => 'gray',
        };
    }

    public function firstPhoto(): ?string
    {
        if ($this->photos && count($this->photos) > 0) {
            return asset('storage/' . $this->photos[0]);
        }
        return null;
    }
}
