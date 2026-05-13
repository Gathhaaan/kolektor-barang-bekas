<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationRequest;
use App\Models\Assignment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending'   => Donation::where('status', 'pending')->count(),
            'approved'  => Donation::where('status', 'approved')->count(),
            'assigned'  => Donation::where('status', 'assigned')->count(),
            'completed' => Donation::where('status', 'completed')->count(),
            'total'     => Donation::count(),
            'donors'    => User::whereHas('role', fn($q) => $q->where('name', 'donor'))->count(),
            'recipients'=> User::whereHas('role', fn($q) => $q->where('name', 'recipient'))->count(),
            'couriers'  => User::whereHas('role', fn($q) => $q->where('name', 'courier'))->count(),
        ];

        $recentDonations = Donation::with(['donor', 'category'])
            ->latest()->take(8)->get();

        $pendingDonations = Donation::with(['donor', 'category'])
            ->where('status', 'pending')->latest()->take(5)->get();

        $activeAssignments = Assignment::with(['donation', 'courier'])
            ->whereIn('status', ['assigned', 'picked_up'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentDonations', 'pendingDonations', 'activeAssignments'));
    }
}
