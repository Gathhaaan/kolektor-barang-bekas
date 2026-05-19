<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            // Donor stats
            'donations_total'     => $user->donations()->count(),
            'donations_pending'   => $user->donations()->where('status', 'pending')->count(),
            'donations_approved'  => $user->donations()->whereIn('status', ['approved', 'assigned', 'picked_up', 'delivered'])->count(),
            'donations_completed' => $user->donations()->where('status', 'completed')->count(),
            'points'              => $user->points,
            
            // Recipient stats
            'requests_total'      => $user->donationRequests()->count(),
            'requests_pending'    => $user->donationRequests()->where('status', 'pending')->count(),
            'requests_accepted'   => $user->donationRequests()->where('status', 'accepted')->count(),
        ];

        // Donor info
        $myDonations = $user->donations()->with('category')->latest()->take(5)->get();
        $pointLogs = $user->pointLogs()->with('donation')->latest()->take(5)->get();

        // Recipient info
        $recentAvailable = Donation::with(['category', 'user'])
            ->where('status', 'approved')
            ->latest()->take(6)->get();
        $myRequests = $user->donationRequests()->with(['donation.category'])->latest()->take(5)->get();
        $categories = Category::withCount(['donations as available_count' => function($q) {
            $q->where('status', 'approved');
        }])->get();

        // Top Donors Leaderboard
        $topDonors = \App\Models\User::withCount(['donations as completed_count' => fn($q) => $q->where('status', 'completed')])
            ->whereHas('role', fn($q) => $q->where('name', 'user'))
            ->having('completed_count', '>', 0)
            ->orderByDesc('points')
            ->take(5)->get();

        return view('user.dashboard', compact('stats', 'myDonations', 'pointLogs', 'recentAvailable', 'myRequests', 'categories', 'topDonors'));
    }
}
