<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Assignment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total'     => $user->assignments()->count(),
            'assigned'  => $user->assignments()->where('status', 'assigned')->count(),
            'picked_up' => $user->assignments()->where('status', 'picked_up')->count(),
            'delivered' => $user->assignments()->where('status', 'delivered')->count(),
        ];

        $todayAssignments = $user->assignments()
            ->with(['donation.category', 'donation.user'])
            ->whereDate('pickup_date', today())
            ->whereIn('status', ['assigned', 'picked_up'])
            ->get();

        $recentAssignments = $user->assignments()
            ->with(['donation.category'])
            ->latest()->take(5)->get();

        return view('courier.dashboard', compact('stats', 'todayAssignments', 'recentAssignments'));
    }
}
