<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TypeOfServiceController;
use App\Http\Controllers\TransOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController; // Pastikan Anda sudah membuat Controller ini

Route::get('/', function () {
    return redirect()->route('login');
});

// Route Dashboard (Bisa diakses semua yang sudah login)
Route::get('/dashboard', function () {
    $total_customer = \App\Models\Customer::count();
    $total_transaksi = \App\Models\TransOrder::count();
    $pendapatan = \App\Models\TransOrder::sum('total');

    // Transaksi berjalan (order_status < 3 yaitu Baru, Proses, Selesai)
    $active_transactions = \App\Models\TransOrder::with('customer')
        ->where('order_status', '<', 3)
        ->orderBy('id', 'desc')
        ->take(10)
        ->get();

    // Hitung kuantitas order per status
    $count_baru = \App\Models\TransOrder::where('order_status', 0)->count();
    $count_proses = \App\Models\TransOrder::where('order_status', 1)->count();
    $count_selesai = \App\Models\TransOrder::where('order_status', 2)->count();
    $count_diambil = \App\Models\TransOrder::where('order_status', 3)->count();

    return view('dashboard', compact(
        'total_customer',
        'total_transaksi',
        'pendapatan',
        'active_transactions',
        'count_baru',
        'count_proses',
        'count_selesai',
        'count_diambil'
    ));
})->middleware(['auth', 'verified', 'prevent-back-history'])->name('dashboard');

// --------------------------------------------------------
// 1. AKSES KHUSUS ADMINISTRATOR
// --------------------------------------------------------
Route::middleware(['auth', 'prevent-back-history', 'role:Administrator'])->group(function () {
    Route::resource('level', LevelController::class);
    Route::resource('type_of_service', TypeOfServiceController::class);
    Route::resource('user', UserController::class); // Sesuai instruksi UjiKom
});

// --------------------------------------------------------
// 2. AKSES BERSAMA: ADMINISTRATOR & OPERATOR
// --------------------------------------------------------
Route::middleware(['auth', 'prevent-back-history', 'role:Administrator,Operator'])->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::resource('transaction', TransOrderController::class);
    Route::put('transaction/status/{id}', [TransOrderController::class, 'updateStatus'])->name('transaction.updateStatus');
    Route::get('/transaction/{id}/print', [\App\Http\Controllers\TransOrderController::class, 'printInvoice'])->name('transaction.print');

    // Rute Pickup Laundry
    Route::get('pickup', [TransOrderController::class, 'pickupIndex'])->name('transaction.pickup.index');
    Route::get('pickup/{id}', [TransOrderController::class, 'pickupShow'])->name('transaction.pickup.show');
    Route::post('pickup/{id}', [TransOrderController::class, 'pickupProcess'])->name('transaction.pickup.process');
});

// --------------------------------------------------------
// 3. AKSES KHUSUS OWNER (PIMPINAN) & ADMINISTRATOR
// --------------------------------------------------------
Route::middleware(['auth', 'prevent-back-history','role:Administrator,Owner'])->group(function () {
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
});

// --------------------------------------------------------
// ROUTE PROFILE BAWAAN BREEZE
// --------------------------------------------------------
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
