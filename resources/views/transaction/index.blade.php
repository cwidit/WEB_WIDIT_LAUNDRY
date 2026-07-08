@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Transaksi Laundry</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-info">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <a href="{{ route('transaction.create') }}" class="btn btn-primary" style="border-radius: 4px !important;">
                    Tambah Transaksi Baru
                </a>
            </div>

            <div class="card-body">

                <!-- Navigasi Tab -->
                <ul class="nav nav-pills mb-3" id="pickupTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-pickup" data-toggle="pill" href="#content-pickup" role="tab">
                            Daftar Pickup Baru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-history" data-toggle="pill" href="#content-history" role="tab">
                            Riwayat / Siap Diambil
                        </a>
                    </li>
                </ul>

                <!-- Isi Konten Tab -->
                <div class="tab-content" id="pickupTabContent">

                    <!-- Konten Tab 1: Daftar Pickup -->
                    <div class="tab-pane fade show active" id="content-pickup" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Kode Nota</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Total Tagihan</th>
                                        <th>Total Berat</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pickupOrders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $order->order_code }}</strong></td>
                                        <td>{{ date('d M Y', strtotime($order->order_date)) }}</td>
                                        <td>{{ $order->customer->customer_name ?? 'Customer Dihapus' }}</td>
                                        <td>
                                            <span class="badge badge-leaf-green">Baru</span>
                                        </td>
                                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td>{{ $order->details->sum('qty') / 1000 }} kg</td>
                                        <td>
                                            <a href="{{ route('transaction.show', $order->id) }}" class="btn btn-info btn-sm">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Belum ada data pesanan baru.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Konten Tab 2: History / Siap Diambil -->
                    <div class="tab-pane fade" id="content-history" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Kode Nota</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Total Tagihan</th>
                                        <th>Total Berat</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($historyOrders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $order->order_code }}</strong></td>
                                        <td>{{ date('d M Y', strtotime($order->order_date)) }}</td>
                                        <td>{{ $order->customer->customer_name ?? 'Customer Dihapus' }}</td>
                                        <td>
                                            <span class="badge badge-primary">Diambil</span>
                                        </td>
                                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td>{{ $order->details->sum('qty') / 1000 }} kg</td>
                                        <td>
                                            <a href="{{ route('transaction.show', $order->id) }}" class="btn btn-info btn-sm">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Belum ada data riwayat transaksi.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div> <!-- Penutup Tab Content -->

            </div>
        </div>

    </div>
</section>
@endsection
