@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="far fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Customer</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_customer }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Transaksi</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_transaksi }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Pendapatan</h4>
                        </div>
                        <div class="card-body" style="font-size: 1.2rem;">
                            Rp {{ number_format($pendapatan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Daftar Transaksi Berjalan -->
            <div class="col-lg-8 col-md-12 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Transaksi Berjalan</h4>
                        <div class="card-header-action">
                            <a href="{{ route('transaction.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Transaksi Baru</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode Nota</th>
                                        <th>Pelanggan</th>
                                        <th>Status Pakaian</th>
                                        <th>Pembayaran</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($active_transactions as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_code }}</strong></td>
                                        <td>{{ $order->customer->customer_name ?? 'Umum' }}</td>
                                        <td>
                                            @if($order->order_status == 0)
                                                <span class="badge badge-warning text-dark">Baru</span>
                                            @elseif($order->order_status == 1)
                                                <span class="badge badge-info">Proses</span>
                                            @elseif($order->order_status == 2)
                                                <span class="badge badge-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $order->payment_status == 0 ? 'badge-success' : 'badge-danger' }}">
                                                {{ $order->payment_status == 0 ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('transaction.show', $order->id) }}" class="btn btn-primary btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Tidak ada transaksi berjalan saat ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Cucian Widget -->
            <div class="col-lg-4 col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Status Cucian (Saat Ini)</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-dot-circle text-warning mr-2"></i> Baru</span>
                                <span class="badge badge-primary badge-pill">{{ $count_baru }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-sync-alt text-info mr-2"></i> Proses</span>
                                <span class="badge badge-info badge-pill">{{ $count_proses }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-check-circle text-success mr-2"></i> Selesai (Siap Ambil)</span>
                                <span class="badge badge-success badge-pill">{{ $count_selesai }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-archive text-secondary mr-2"></i> Sudah Diambil</span>
                                <span class="badge badge-secondary badge-pill">{{ $count_diambil }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection