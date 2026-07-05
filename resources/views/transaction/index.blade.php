@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Transaksi Laundry</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <a href="{{ route('transaction.create') }}" class="btn btn-primary">
                    Tambah Transaksi Baru
                </a>
            </div>

            <div class="card-body">
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
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $order->order_code }}</strong></td>
                                <td>{{ date('d M Y', strtotime($order->order_date)) }}</td>
                                <td>{{ $order->customer->customer_name ?? 'Customer Dihapus' }}</td>
                                <td>
                                    <!-- Menampilkan Status Pakaian -->
                                    @if($order->order_status == 0)
                                        <span class="badge badge-warning">Baru</span>
                                    @elseif($order->order_status == 1)
                                        <span class="badge badge-info">Proses</span>
                                    @elseif($order->order_status == 2)
                                        <span class="badge badge-success">Selesai</span>
                                    @elseif($order->order_status == 3)
                                        <span class="badge badge-primary">Diambil</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    <!-- Tombol Detail untuk melihat item cucian -->
                                    <a href="{{ route('transaction.show', $order->id) }}" class="btn btn-info btn-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    Belum ada data transaksi.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection