<?php

namespace App\Http\Controllers\Recipient;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_requests'  => $user->donationRequests()->count(),
            'pending'         => $user->donationRequests()->where('status', 'pending')->count(),
            'accepted'        => $user->donationRequests()->where('status', 'accepted')->count(),
            'rejected'        => $user->donationRequests()->where('status', 'rejected')->count(),
        ];

        $recentAvailable = Donation::with(['category', 'donor'])
            ->where('status', 'approved')
            ->latest()->take(6)->get();

        $myRequests = $user->donationRequests()->with(['donation.category'])->latest()->take(5)->get();

        $categories = Category::withCount(['donations as available_count' => fn($q) => $q->where('status', 'approved')])->get();

        return view('recipient.dashboard', compact('stats', 'recentAvailable', 'myRequests', 'categories'));
    }
}
