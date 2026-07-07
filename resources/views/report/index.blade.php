@extends('layouts.app')

@section('title', 'Laporan Keuangan Laundry')

@section('content')
<style>
    /* CSS khusus pencetakan laporan PDF */
    @media print {
        @page {
            size: A4 portrait;
            margin: 15mm 10mm;
        }
        body {
            background-color: #fff !important;
            color: #000 !important;
            font-family: Arial, sans-serif !important;
            font-size: 11px !important;
        }
        .no-print, .main-sidebar, .main-navbar, .section-header, .main-footer, .card-header-action, .mb-3 {
            display: none !important;
        }
        .main-content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
            margin-bottom: 15px !important;
            background: transparent !important;
        }
        .card-body {
            padding: 0 !important;
        }
        .table-responsive {
            overflow: visible !important;
        }
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 10px !important;
        }
        .table th, .table td {
            border: 1px solid #000 !important;
            padding: 6px 8px !important;
            color: #000 !important;
            font-size: 10px !important;
        }
        .table thead th {
            background-color: #f3f4f6 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .table tfoot th, .table tfoot td {
            border: 1px solid #000 !important;
            font-weight: bold !important;
            font-size: 11px !important;
        }
        /* Ringkasan Rekap agar tetap horizontal di print */
        .card .row .col-md-2 {
            width: 16.666667% !important;
            float: left !important;
            box-sizing: border-box !important;
            margin-bottom: 0 !important;
        }
        .card .row .p-3 {
            padding: 8px !important;
            border: 1px solid #ccc !important;
            border-radius: 4px !important;
            background-color: #f9fafb !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .h3 {
            font-size: 13px !important;
        }
        .print-header {
            display: block !important;
        }
    }
</style>

<section class="section">
    <div class="section-header no-print">
        <h1>Laporan Keuangan Laundry</h1>
    </div>

    <div class="section-body">
        
        <!-- Kop Surat Laporan (Hanya Tampil Saat Cetak / PDF) -->
        <div class="d-none d-print-block print-header" style="font-family: Arial, Helvetica, sans-serif; color: #000; margin-bottom: 25px;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 3px double #000; padding-bottom: 12px; margin-bottom: 15px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <!-- SVG Logo Widit Laundry -->
                    <svg width="55" height="55" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #4f46e5;">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                        <path d="M8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z" fill="currentColor"/>
                        <path d="M7 7C7 7 8 6 10 6C12 6 12 7 14 7C16 7 17 6 17 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <div>
                        <h2 style="font-size: 20px; font-weight: bold; margin: 0; color: #1e3a8a; line-height: 1.2; letter-spacing: 0.5px;">WIDIT LAUNDRY</h2>
                        <p style="margin: 2px 0 0 0; font-size: 10px; color: #4b5563;">Jasa Laundry Cepat, Bersih, Wangi, & Rapi</p>
                        <p style="margin: 1px 0 0 0; font-size: 9px; color: #6b7280;">Jl. Raya Karangampel No. 45 Indramayu | Telp: 0812-3456-7890</p>
                    </div>
                </div>
                <div style="text-align: right;">
                    <h3 style="font-size: 14px; font-weight: bold; margin: 0; color: #1e3a8a; letter-spacing: 0.5px;">LAPORAN PENDAPATAN</h3>
                    <p style="margin: 4px 0 0 0; font-size: 9px; color: #4b5563; font-weight: bold;">
                        Periode: 
                        @if($start_date && $end_date)
                            {{ date('d/m/Y', strtotime($start_date)) }} - {{ date('d/m/Y', strtotime($end_date)) }}
                        @else
                            Keseluruhan
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="card no-print">
            <div class="card-body">
                <div class="mb-3 text-info font-weight-bold">
                    <i class="fas fa-info-circle mr-1"></i> Note : View Only
                </div>
                <form action="{{ route('report.index') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label>Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-filter"></i> Tampilkan</button>
                            <a href="{{ route('report.index') }}" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                            <button type="button" onclick="window.print()" class="btn btn-warning float-right"><i class="fas fa-print"></i> Cetak PDF</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ringkasan Rekap -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="p-3 rounded" style="background: #eff6ff; height: 100%;">
                            <h5 class="mb-1" style="font-size: 0.95rem;">Total Order</h5>
                            <p class="h3 mb-0" style="font-size: 1.6rem;">{{ $total_orders }}</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="p-3 rounded" style="background: #eef2ff; height: 100%;">
                            <h5 class="mb-1" style="font-size: 0.95rem;">Pendapatan</h5>
                            <p class="h3 mb-0" style="font-size: 1.3rem; font-weight: bold; margin-top: 4px;">Rp {{ number_format($total_revenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="p-3 rounded" style="background: #fef3c7; height: 100%;">
                            <h5 class="mb-1" style="font-size: 0.95rem;">Baru</h5>
                            <p class="h3 mb-0" style="font-size: 1.6rem;">{{ $status_new }}</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="p-3 rounded" style="background: #e0f2fe; height: 100%;">
                            <h5 class="mb-1" style="font-size: 0.95rem;">Proses</h5>
                            <p class="h3 mb-0" style="font-size: 1.6rem;">{{ $status_processing }}</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="p-3 rounded" style="background: #d1fae5; height: 100%;">
                            <h5 class="mb-1" style="font-size: 0.95rem;">Selesai</h5>
                            <p class="h3 mb-0" style="font-size: 1.6rem;">{{ $status_completed }}</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 mb-3">
                        <div class="p-3 rounded" style="background: #f3f4f6; height: 100%;">
                            <h5 class="mb-1" style="font-size: 0.95rem;">Diambil</h5>
                            <p class="h3 mb-0" style="font-size: 1.6rem;">{{ $status_picked_up }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="text-dark">
                    Rekapitulasi Transaksi 
                    @if($start_date && $end_date)
                        ({{ date('d M Y', strtotime($start_date)) }} - {{ date('d M Y', strtotime($end_date)) }})
                    @else
                        (Keseluruhan)
                    @endif
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>Kode Nota</th>
                                <th>Nama Customer</th>
                                <th>Status</th>
                                <th class="text-right">Total Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->order_date)) }}</td>
                                <td>{{ $order->order_code }}</td>
                                <td>{{ $order->customer->customer_name ?? 'Customer Dihapus' }}</td>
                                <td>
                                    @if($order->order_status == 0)
                                        Baru
                                    @elseif($order->order_status == 1)
                                        Proses
                                    @elseif($order->order_status == 2)
                                        Selesai
                                    @elseif($order->order_status == 3)
                                        Diambil
                                    @endif
                                </td>
                                <td class="text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data transaksi pada rentang tanggal tersebut.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right font-weight-bold">Total Pendapatan :</th>
                                <th class="text-right font-weight-bold text-success" style="font-size: 1.1rem;">
                                    Rp {{ number_format($total_revenue, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Tanda Tangan Cetak (Hanya tampil saat di print) -->
                <div class="row mt-5 d-none d-print-flex">
                    <div class="col-8"></div>
                    <div class="col-4 text-center">
                        <p>Indramayu, {{ date('d M Y') }}</p>
                        <br><br><br>
                        <p><strong>Pimpinan Laundry</strong></p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
@endsection