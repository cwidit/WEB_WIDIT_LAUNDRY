@extends('layouts.app')

@section('title', 'Pickup Laundry')

@section('content')
<section class="section">
    <div class="section-header">
        <h1><i class="fas fa-truck mr-2"></i> Pickup Laundry</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-info alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    <ul class="mb-0 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @php
            $activeTab = request()->query('tab', 'ready');
        @endphp

        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h4>Manajemen Pengambilan Cucian</h4>
            </div>
            <div class="card-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="pickupTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'ready' ? 'active' : '' }}" id="ready-tab" data-toggle="tab" href="#ready" role="tab" aria-controls="ready" aria-selected="{{ $activeTab === 'ready' ? 'true' : 'false' }}">
                            <i class="fas fa-check-circle mr-1"></i> Cucian Berjalan <span class="badge badge-warning ml-1 text-dark">{{ $ready_orders->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'history' ? 'active' : '' }}" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="{{ $activeTab === 'history' ? 'true' : 'false' }}">
                            <i class="fas fa-history mr-1"></i> Riwayat Pengambilan <span class="badge badge-secondary ml-1">{{ $pickup_history->count() }}</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="pickupTabContent">
                    <!-- Tab Cucian Berjalan -->
                    <div class="tab-pane fade {{ $activeTab === 'ready' ? 'show active' : '' }}" id="ready" role="tabpanel" aria-labelledby="ready-tab">
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Kode Nota</th>
                                        <th>Pelanggan</th>
                                        <th>Status Cucian</th>
                                        <th>Total Berat</th>
                                        <th>Tagihan</th>
                                        <th>Status Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ready_orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('transaction.show', $order->id) }}" class="font-weight-bold text-primary text-decoration-none" data-toggle="tooltip" title="Klik untuk Lihat Detail / Perbarui Status">
                                                <i class="fas fa-receipt mr-1"></i> {{ $order->order_code }}
                                            </a>
                                        </td>
                                        <td>{{ $order->customer->customer_name ?? 'Umum' }}</td>
                                        <td>
                                            @if($order->order_status == 0)
                                                <span class="badge badge-leaf-green">Baru</span>
                                            @elseif($order->order_status == 1)
                                                <span class="badge badge-warning">Proses</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($order->details->sum('qty') / 1000, 2, ',', '.') }} kg</td>
                                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ $order->payment_status == 0 ? 'badge-sky-blue' : 'badge-danger' }}">
                                                {{ $order->payment_status == 0 ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada cucian yang sedang berjalan saat ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Riwayat Pengambilan & Selesai -->
                    <div class="tab-pane fade {{ $activeTab === 'history' ? 'show active' : '' }}" id="history" role="tabpanel" aria-labelledby="history-tab">
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

                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pickup_history as $history)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('transaction.show', $history->id) }}" class="font-weight-bold text-primary text-decoration-none" data-toggle="tooltip" title="Lihat Detail Transaksi">
                                                <strong>{{ $history->order_code }}</strong>
                                            </a>
                                        </td>
                                        <td>{{ $history->customer->customer_name ?? 'Umum' }}</td>
                                        <td>
                                            @if($history->order_status == 3)
                                                @if($history->pickupLog)
                                                    {{ date('d M Y H:i', strtotime($history->pickupLog->pickup_date)) }}
                                                @else
                                                    {{ date('d M Y H:i', strtotime($history->updated_at)) }}
                                                @endif
                                            @else
                                                <span class="text-muted"><em>Belum diambil</em></span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($history->total, 0, ',', '.') }}</td>
                                        <td>
                                            @if($history->order_status == 2)
                                                <span class="badge badge-success">Selesai (Siap Ambil)</span>
                                            @elseif($history->order_status == 3)
                                                <span class="badge badge-primary">Diambil (Done)</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($history->order_status == 2)
                                                <a href="{{ route('transaction.pickup.show', $history->id) }}" class="btn btn-success btn-sm btn-block" style="border-radius: 4px !important;">
                                                    <i class="fas fa-box-open mr-1"></i> Ambil Cucian
                                                </a>
                                            @elseif($history->order_status == 3)
                                                <span class="text-muted"><i class="fas fa-check-double mr-1 text-success"></i> Done</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat pengambilan laundry.</td>
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
</section>
@endsection
