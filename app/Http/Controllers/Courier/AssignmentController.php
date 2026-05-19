<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Donation;
use App\Models\Notification;
use App\Models\PointLog;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->assignments()
            ->with(['donation.category', 'donation.user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->paginate(12)->withQueryString();
        return view('courier.assignments.index', compact('assignments'));
    }

    public function show(Assignment $assignment)
    {
        abort_if($assignment->courier_id !== auth()->id(), 403);
        $assignment->load(['donation.category', 'donation.user', 'request.user', 'admin']);
        return view('courier.assignments.show', compact('assignment'));
    }

    public function markPickedUp(Request $request, Assignment $assignment)
    {
        abort_if($assignment->courier_id !== auth()->id(), 403);
        abort_if($assignment->status !== 'assigned', 400);

        $request->validate(['pickup_note' => 'nullable|string|max:500']);

        $assignment->update([
            'status'       => 'picked_up',
            'pickup_note'  => $request->pickup_note,
            'picked_up_at' => now(),
        ]);

        $assignment->donation->update(['status' => 'picked_up']);

        // Notify donor
        Notification::create([
            'user_id' => $assignment->donation->user_id,
            'type'    => 'item_picked_up',
            'title'   => 'Barang Sudah Diambil',
            'message' => "Barang \"{$assignment->donation->title}\" telah diambil oleh kurir.",
            'data'    => ['donation_id' => $assignment->donation_id],
        ]);

        return back()->with('success', 'Status diperbarui: Barang sudah diambil.');
    }

    public function markDelivered(Request $request, Assignment $assignment)
    {
        abort_if($assignment->courier_id !== auth()->id(), 403);
        abort_if($assignment->status !== 'picked_up', 400);

        $request->validate(['delivery_note' => 'nullable|string|max:500']);

        $assignment->update([
            'status'        => 'delivered',
            'delivery_note' => $request->delivery_note,
            'delivered_at'  => now(),
            'delivery_date' => now()->toDateString(),
        ]);

        $donation = $assignment->donation;
        $donation->update(['status' => 'completed']);

        // Award points to user
        $pointsEarned = 10;
        $user = $donation->user;
        $user->increment('points', $pointsEarned);

        PointLog::create([
            'user_id'     => $user->id,
            'donation_id' => $donation->id,
            'points'      => $pointsEarned,
            'description' => "Donasi \"{$donation->title}\" berhasil dikirimkan.",
        ]);

        // Notify user
        Notification::create([
            'user_id' => $user->id,
            'type'    => 'donation_completed',
            'title'   => 'Donasi Selesai! +' . $pointsEarned . ' Poin',
            'message' => "Barang \"{$donation->title}\" berhasil dikirimkan. Anda mendapatkan {$pointsEarned} poin kontribusi!",
            'data'    => ['donation_id' => $donation->id, 'points' => $pointsEarned],
        ]);

        // Notify user if exists
        if ($assignment->request) {
            Notification::create([
                'user_id' => $assignment->request->user_id,
                'type'    => 'request_delivered',
                'title'   => 'Barang Sudah Dikirim!',
                'message' => "Barang \"{$donation->title}\" yang Anda minta telah berhasil dikirimkan.",
                'data'    => ['donation_id' => $donation->id],
            ]);
        }

        return back()->with('success', 'Pengiriman selesai! Donor mendapat ' . $pointsEarned . ' poin.');
    }
}
