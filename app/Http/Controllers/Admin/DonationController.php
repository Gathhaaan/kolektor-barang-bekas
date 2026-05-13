<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignCourierRequest;
use App\Models\Assignment;
use App\Models\Donation;
use App\Models\DonationRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with(['donor', 'category'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $donations = $query->paginate(15)->withQueryString();
        return view('admin.donations.index', compact('donations'));
    }

    public function show(Donation $donation)
    {
        $donation->load(['donor', 'category', 'requests.recipient', 'assignment.courier']);
        $couriers = User::whereHas('role', fn($q) => $q->where('name', 'courier'))
            ->where('is_active', true)->get();
        return view('admin.donations.show', compact('donation', 'couriers'));
    }

    public function approve(Request $request, Donation $donation)
    {
        $request->validate(['admin_note' => 'nullable|string|max:500']);

        $donation->update([
            'status'      => 'approved',
            'admin_note'  => $request->admin_note,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Notify donor
        Notification::create([
            'user_id' => $donation->user_id,
            'type'    => 'donation_approved',
            'title'   => 'Donasi Disetujui!',
            'message' => "Donasi Anda \"{$donation->title}\" telah disetujui dan kini terlihat di katalog.",
            'data'    => ['donation_id' => $donation->id],
        ]);

        return back()->with('success', 'Donasi berhasil disetujui.');
    }

    public function reject(Request $request, Donation $donation)
    {
        $request->validate(['rejection_reason' => 'required|string|min:10|max:500']);

        $donation->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'verified_by'      => auth()->id(),
            'verified_at'      => now(),
        ]);

        Notification::create([
            'user_id' => $donation->user_id,
            'type'    => 'donation_rejected',
            'title'   => 'Donasi Ditolak',
            'message' => "Donasi \"{$donation->title}\" ditolak. Alasan: {$request->rejection_reason}",
            'data'    => ['donation_id' => $donation->id],
        ]);

        return back()->with('success', 'Donasi telah ditolak.');
    }

    public function assign(AssignCourierRequest $request, Donation $donation)
    {
        $validated = $request->validated();

        // Accept a request if specified
        if (!empty($validated['request_id'])) {
            DonationRequest::where('donation_id', $donation->id)
                ->where('id', '!=', $validated['request_id'])
                ->update(['status' => 'rejected']);

            DonationRequest::find($validated['request_id'])?->update(['status' => 'accepted']);
        }

        Assignment::create([
            'donation_id' => $donation->id,
            'courier_id'  => $validated['courier_id'],
            'admin_id'    => auth()->id(),
            'request_id'  => $validated['request_id'] ?? null,
            'pickup_date' => $validated['pickup_date'],
            'pickup_note' => $validated['pickup_note'] ?? null,
            'status'      => 'assigned',
        ]);

        $donation->update(['status' => 'assigned']);

        // Notify courier
        $courier = User::find($validated['courier_id']);
        Notification::create([
            'user_id' => $validated['courier_id'],
            'type'    => 'assignment_created',
            'title'   => 'Tugas Pengambilan Baru',
            'message' => "Anda ditugaskan untuk mengambil \"{$donation->title}\" pada {$validated['pickup_date']}.",
            'data'    => ['donation_id' => $donation->id],
        ]);

        return back()->with('success', "Kurir {$courier->name} berhasil ditugaskan.");
    }
}
