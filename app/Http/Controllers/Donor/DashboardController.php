<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $donations = $user->donations()->with('category')->latest()->take(5)->get();

        $stats = [
            'total'     => $user->donations()->count(),
            'pending'   => $user->donations()->where('status', 'pending')->count(),
            'approved'  => $user->donations()->whereIn('status', ['approved', 'assigned', 'picked_up', 'delivered'])->count(),
            'completed' => $user->donations()->where('status', 'completed')->count(),
            'rejected'  => $user->donations()->where('status', 'rejected')->count(),
            'points'    => $user->points,
        ];

        $pointLogs = $user->pointLogs()->with('donation')->latest()->take(5)->get();

        return view('donor.dashboard', compact('donations', 'stats', 'pointLogs'));
    }
}
