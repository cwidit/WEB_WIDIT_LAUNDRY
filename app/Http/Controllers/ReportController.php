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
        $total_orders = $orders->count();
        $status_new = $orders->where('order_status', 0)->count();
        $status_processing = $orders->where('order_status', 1)->count();
        $status_completed = $orders->where('order_status', 2)->count();
        $status_picked_up = $orders->where('order_status', 3)->count();

        return view('report.index', compact(
            'orders',
            'start_date',
            'end_date',
            'total_revenue',
            'total_orders',
            'status_new',
            'status_processing',
            'status_completed',
            'status_picked_up'
        ));
    }
}