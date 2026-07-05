<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Menangkap request tanggal dari form filter
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // Query dasar mengambil semua data transaksi beserta relasi customernya
        $query = TransOrder::with('customer')->orderBy('order_date', 'desc');

        // Jika user mengisi rentang tanggal, filter datanya
        if ($start_date && $end_date) {
            $query->whereBetween('order_date', [$start_date, $end_date]);
        }

        $orders = $query->get();
        
        // Menghitung total pendapatan dari data yang difilter
        $total_revenue = $orders->sum('total');

        return view('report.index', compact('orders', 'start_date', 'end_date', 'total_revenue'));
    }
}