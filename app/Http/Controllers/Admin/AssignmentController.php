<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assignment::with(['donation.category', 'courier', 'donation.donor'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->paginate(15)->withQueryString();
        return view('admin.assignments.index', compact('assignments'));
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['donation.category', 'courier', 'admin', 'request.recipient', 'donation.donor']);
        return view('admin.assignments.show', compact('assignment'));
    }
}
