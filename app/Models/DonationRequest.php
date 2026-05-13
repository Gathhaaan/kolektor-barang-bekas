<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationRequest extends Model
{
    protected $fillable = ['donation_id', 'user_id', 'message', 'status', 'rejection_reason'];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class, 'request_id');
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'  => 'Menunggu',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            default    => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'pending'  => 'amber',
            'accepted' => 'green',
            'rejected' => 'red',
            default    => 'gray',
        };
    }
}
