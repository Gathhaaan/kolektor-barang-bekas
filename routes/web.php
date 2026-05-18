<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Donor;
use App\Http\Controllers\Recipient;
use App\Http\Controllers\Courier;
use App\Models\Category;
use App\Models\Donation;
use Illuminate\Support\Facades\Route;

// ─── Public Landing ──────────────────────────────────────────────────────────
Route::get('/', function () {
    $featuredDonations = Donation::with(['category', 'donor'])
        ->where('status', 'approved')->latest()->take(6)->get();
    $categories = Category::withCount(['donations as approved_count' => fn($q) => $q->where('status', 'approved')])->get();
    $stats = [
        'total'     => Donation::count(),
        'completed' => Donation::where('status', 'completed')->count(),
        'approved'  => Donation::where('status', 'approved')->count(),
    ];
    return view('welcome', compact('featuredDonations', 'categories', 'stats'));
})->name('home');

// ─── Auth Redirect for root /dashboard ───────────────────────────────────────
Route::get('/dashboard', function () {
    return redirect()->route(auth()->user()->dashboardRoute());
})->middleware(['auth'])->name('dashboard');

// ─── Notification Mark Read ───────────────────────────────────────────────────
Route::post('/notifications/{notification}/read', function (\App\Models\Notification $notification) {
    abort_if($notification->user_id !== auth()->id(), 403);
    $notification->markAsRead();
    return back();
})->middleware('auth')->name('notifications.read');

Route::post('/notifications/read-all', function () {
    auth()->user()->appNotifications()->whereNull('read_at')->update(['read_at' => now()]);
    return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
})->middleware('auth')->name('notifications.readAll');

// ─── ADMIN ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Donations
    Route::get('/donations', [Admin\DonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/{donation}', [Admin\DonationController::class, 'show'])->name('donations.show');
    Route::post('/donations/{donation}/approve', [Admin\DonationController::class, 'approve'])->name('donations.approve');
    Route::post('/donations/{donation}/reject', [Admin\DonationController::class, 'reject'])->name('donations.reject');
    Route::post('/donations/{donation}/assign', [Admin\DonationController::class, 'assign'])->name('donations.assign');

    // Assignments
    Route::get('/assignments', [Admin\AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [Admin\AssignmentController::class, 'show'])->name('assignments.show');

    // Requests
    Route::get('/requests', [Admin\RequestController::class, 'index'])->name('requests.index');

    // Categories
    Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Users
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle-active', [Admin\UserController::class, 'toggleActive'])->name('users.toggleActive');

    // Reports
    Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');
});

// ─── DONOR ────────────────────────────────────────────────────────────────────
Route::prefix('donor')->name('donor.')->middleware(['auth', 'role:donor'])->group(function () {
    Route::get('/dashboard', [Donor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/donations', [Donor\DonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/create', [Donor\DonationController::class, 'create'])->name('donations.create');
    Route::post('/donations', [Donor\DonationController::class, 'store'])->name('donations.store');
    Route::get('/donations/{donation}', [Donor\DonationController::class, 'show'])->name('donations.show');
});

// ─── RECIPIENT ────────────────────────────────────────────────────────────────
Route::prefix('recipient')->name('recipient.')->middleware(['auth', 'role:recipient'])->group(function () {
    Route::get('/dashboard', [Recipient\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/catalog', [Recipient\RequestController::class, 'catalog'])->name('catalog.index');
    Route::get('/catalog/{donation}', [Recipient\RequestController::class, 'catalogShow'])->name('catalog.show');
    Route::post('/requests', [Recipient\RequestController::class, 'store'])->name('requests.store');
    Route::get('/requests', [Recipient\RequestController::class, 'myRequests'])->name('requests.index');
});

// ─── COURIER ──────────────────────────────────────────────────────────────────
Route::prefix('courier')->name('courier.')->middleware(['auth', 'role:courier'])->group(function () {
    Route::get('/dashboard', [Courier\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/assignments', [Courier\AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [Courier\AssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{assignment}/pickup', [Courier\AssignmentController::class, 'markPickedUp'])->name('assignments.pickup');
    Route::post('/assignments/{assignment}/deliver', [Courier\AssignmentController::class, 'markDelivered'])->name('assignments.deliver');
});

require __DIR__ . '/auth.php';
