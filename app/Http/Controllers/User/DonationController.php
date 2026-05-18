<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Notification;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->donations()->with('category')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $donations = $query->paginate(12)->withQueryString();
        return view('user.donations.index', compact('donations'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('user.donations.create', compact('categories'));
    }

    public function store(StoreDonationRequest $request)
    {
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('donations', 'public');
            }
        }

        $donation = Donation::create([
            'user_id'        => auth()->id(),
            'category_id'    => $request->category_id,
            'title'          => $request->title,
            'description'    => $request->description,
            'condition'      => $request->condition,
            'pickup_address' => $request->pickup_address,
            'photos'         => $photos,
            'status'         => 'pending',
        ]);

        // Notify admins
        $admins = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => 'new_donation',
                'title'   => 'Donasi Baru Menunggu Verifikasi',
                'message' => "{$request->user()->name} mengirimkan donasi baru: \"{$donation->title}\"",
                'data'    => ['donation_id' => $donation->id],
            ]);
        }

        return redirect()->route('user.donations.show', $donation)
            ->with('success', 'Donasi berhasil dikirim dan sedang menunggu verifikasi admin.');
    }

    public function show(Donation $donation)
    {
        abort_if($donation->user_id !== auth()->id(), 403);
        $donation->load(['category', 'assignment.courier']);
        return view('user.donations.show', compact('donation'));
    }
}
