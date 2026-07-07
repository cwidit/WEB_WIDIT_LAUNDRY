@extends('layouts.app')

@section('title', 'Pickup Laundry')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Pickup Laundry</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-info">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Manajemen Pengambilan Cucian</h4>
                    </div>
                    <div class="card-body">
                        <!-- Nav Tabs -->
                        <ul class="nav nav-tabs" id="pickupTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="ready-tab" data-toggle="tab" href="#ready" role="tab" aria-controls="ready" aria-selected="true">
                                    <i class="fas fa-check-circle mr-1"></i> Siap Diambil <span class="badge badge-warning ml-1 text-dark">{{ $ready_orders->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">
                                    <i class="fas fa-history mr-1"></i> Riwayat Pengambilan <span class="badge badge-secondary ml-1">{{ $pickup_history->count() }}</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="pickupTabContent">
                            <!-- Tab Siap Diambil -->
                            <div class="tab-pane fade show active" id="ready" role="tabpanel" aria-labelledby="ready-tab">
                                <div class="table-responsive pt-3">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Kode Nota</th>
                                                <th>Pelanggan</th>
                                                <th>Total Berat</th>
                                                <th>Tagihan</th>
                                                <th>Status Bayar</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ready_orders as $order)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $order->order_code }}</strong></td>
                                                <td>{{ $order->customer->customer_name ?? 'Umum' }}</td>
                                                <td>{{ $order->details->sum('qty') / 1000 }} kg</td>
                                                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge {{ $order->payment_status == 0 ? 'badge-sky-blue' : 'badge-danger' }}">
                                                        {{ $order->payment_status == 0 ? 'Lunas' : 'Belum Lunas' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('transaction.pickup.show', $order->id) }}" class="btn btn-primary btn-sm" style="border-radius: 4px !important;">
                                                        Ambil Cucian
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">Tidak ada cucian yang siap diambil saat ini.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab Riwayat Pengambilan -->
                            <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                                <div class="table-responsive pt-3">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Kode Nota</th>
                                                <th>Pelanggan</th>
                                                <th>Tanggal Ambil</th>
                                                <th>Total Tagihan</th>
                                                <th>Status</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pickup_history as $history)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $history->order->order_code ?? 'Nota Dihapus' }}</strong></td>
                                                <td>{{ $history->customer->customer_name ?? 'Umum' }}</td>
                                                <td>{{ date('d M Y H:i', strtotime($history->pickup_date)) }}</td>
                                                <td>Rp {{ number_format($history->order->total ?? 0, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge badge-primary">Selesai & Diambil</span>
                                                </td>
                                                <td>{{ $history->notes ?? '-' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat pengambilan laundry.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
