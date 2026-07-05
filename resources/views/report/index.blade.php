@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<style>
    /* CSS ini akan menyembunyikan elemen tertentu saat halaman dicetak */
    @media print {
        .no-print, .main-sidebar, .main-navbar, .section-header {
            display: none !important;
        }
        .main-content {
            padding-left: 0 !important;
            padding-top: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>

<section class="section">
    <div class="section-header no-print">
        <h1>Laporan Pendapatan Laundry</h1>
    </div>

    <div class="section-body">
        <!-- Form Filter Tanggal -->
        <div class="card no-print">
            <div class="card-body">
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
                        <p>Palembang, {{ date('d M Y') }}</p>
                        <br><br><br>
                        <p><strong>Pimpinan Laundry</strong></p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>
@endsection