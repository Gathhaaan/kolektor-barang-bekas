<?php

namespace App\Http\Controllers\Recipient;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequestRequest;
use App\Models\Category;
use App\Models\Donation;
use App\Models\DonationRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function catalog(Request $request)
    {
        $query = Donation::with(['category', 'donor'])
            ->where('status', 'approved');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $donations = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::withCount(['donations as approved_count' => fn($q) => $q->where('status', 'approved')])->get();

        return view('recipient.catalog.index', compact('donations', 'categories'));
    }

    public function catalogShow(Donation $donation)
    {
        abort_if($donation->status !== 'approved', 404);
        $donation->load(['category', 'donor']);

        $alreadyRequested = auth()->user()->donationRequests()
            ->where('donation_id', $donation->id)->exists();

        return view('recipient.catalog.show', compact('donation', 'alreadyRequested'));
    }

    public function store(StoreRequestRequest $request)
    {
        $donation = Donation::findOrFail($request->donation_id);

        // Check not already requested
        $exists = auth()->user()->donationRequests()
            ->where('donation_id', $donation->id)->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah mengajukan permintaan untuk barang ini.');
        }

        $donationRequest = DonationRequest::create([
            'donation_id' => $donation->id,
            'user_id'     => auth()->id(),
            'message'     => $request->message,
            'status'      => 'pending',
        ]);

        // Notify admin
        $admins = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => 'new_request',
                'title'   => 'Permintaan Donasi Baru',
                'message' => auth()->user()->name . " mengajukan permintaan untuk \"{$donation->title}\"",
                'data'    => ['request_id' => $donationRequest->id],
            ]);
        }

        return redirect()->route('recipient.requests.index')
            ->with('success', 'Permintaan berhasil dikirim!');
    }

    public function myRequests(Request $request)
    {
        $query = auth()->user()->donationRequests()
            ->with(['donation.category', 'assignment'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(12)->withQueryString();
        return view('recipient.requests.index', compact('requests'));
    }
}
