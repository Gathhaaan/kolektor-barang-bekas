<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        // Monthly donation completions
        $monthlyData = Donation::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyChart = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyChart[] = $monthlyData[$m] ?? 0;
        }

        // Category breakdown
        $categoryStats = Category::withCount(['donations as total_donations',
            'donations as completed_donations' => fn($q) => $q->where('status', 'completed')
        ])->having('total_donations', '>', 0)->orderByDesc('total_donations')->get();

        // Status summary
        $statusSummary = Donation::whereYear('created_at', $year)
            ->selectRaw('
                CASE 
                    WHEN status IN ("assigned", "picked_up", "delivered") THEN "in_progress"
                    ELSE status 
                END as group_status, COUNT(*) as count
            ')
            ->groupBy('group_status')
            ->pluck('count', 'group_status');

        // Top donors
        $topDonors = User::withCount(['donations as completed_count' => fn($q) => $q->where('status', 'completed')])
            ->whereHas('role', fn($q) => $q->where('name', 'user'))
            ->having('completed_count', '>', 0)
            ->orderByDesc('points')
            ->take(10)->get();

        $availableYears = Donation::selectRaw('YEAR(created_at) as year')
            ->distinct()->pluck('year')->sortDesc();

        return view('admin.reports.index', compact(
            'monthlyChart', 'categoryStats', 'statusSummary', 'topDonors', 'year', 'availableYears'
        ));
    }
}
