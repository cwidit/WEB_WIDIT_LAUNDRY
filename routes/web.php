<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TypeOfServiceController;
use App\Http\Controllers\TransOrderController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    // Mengambil total data untuk ditampilkan di dashboard
    $total_customer = \App\Models\Customer::count();
    $total_transaksi = \App\Models\TransOrder::count();
    $pendapatan = \App\Models\TransOrder::sum('total');

    return view('dashboard', compact('total_customer', 'total_transaksi', 'pendapatan'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:Administrator,Operator'])->group(function () {
    Route::resource('level', LevelController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('type_of_service', TypeOfServiceController::class);
    Route::resource('transaction', TransOrderController::class);
    Route::put('transaction/status/{id}', [App\Http\Controllers\TransOrderController::class, 'updateStatus'])->name('transaction.updateStatus');
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
