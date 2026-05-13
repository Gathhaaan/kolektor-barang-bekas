<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'donation_id', 'courier_id', 'admin_id', 'request_id',
        'pickup_date', 'delivery_date', 'pickup_note', 'delivery_note',
        'status', 'picked_up_at', 'delivered_at',
    ];

    protected $casts = [
        'pickup_date'  => 'date',
        'delivery_date' => 'date',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function request()
    {
        return $this->belongsTo(DonationRequest::class, 'request_id');
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'assigned'  => 'Ditugaskan',
            'picked_up' => 'Sudah Diambil',
            'delivered' => 'Sudah Dikirim',
            default     => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'assigned'  => 'blue',
            'picked_up' => 'amber',
            'delivered' => 'green',
            default     => 'gray',
        };
    }
}
