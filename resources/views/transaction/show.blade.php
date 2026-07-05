@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Detail Transaksi / Nota</h1>
    </div>

    <div class="section-body">
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="invoice">
            <div class="invoice-print">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="invoice-title">
                            <h2>Invoice</h2>
                            <div class="invoice-number">Order #{{ $order->order_code }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <address>
                                    <strong>Informasi Pelanggan:</strong><br>
                                    {{ $order->customer->customer_name ?? 'Customer Dihapus' }}<br>
                                    Telp: {{ $order->customer->phone ?? '-' }}<br>
                                    Alamat: {{ $order->customer->address ?? '-' }}
                                </address>
                            </div>
                            <div class="col-md-6 text-md-right">
                                <address>
                                    <strong>Informasi Order:</strong><br>
                                    Tanggal Masuk: {{ date('d F Y', strtotime($order->order_date)) }}<br>
                                    Tanggal Selesai: {{ date('d F Y', strtotime($order->order_end_date)) }}<br>
                                    
                                    <div class="mt-3">
                                        <strong>Status Cucian:</strong><br>
                                        <form action="{{ route('transaction.updateStatus', $order->id) }}" method="POST" class="mt-2 d-inline-block" style="max-width: 250px; text-align: left;">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group">
                                                <select name="order_status" class="form-control">
                                                    <option value="0" {{ $order->order_status == 0 ? 'selected' : '' }}>Baru</option>
                                                    <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>Proses</option>
                                                    <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>Selesai</option>
                                                    <option value="3" {{ $order->order_status == 3 ? 'selected' : '' }}>Diambil (Pickup)</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </address>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="section-title">Ringkasan Layanan</div>
                        <p class="section-lead">Semua item cucian tidak bisa diubah karena sudah masuk ke sistem pembayaran.</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-md">
                                <thead>
                                    <tr>
                                        <th data-width="5%">No</th>
                                        <th>Layanan</th>
                                        <th class="text-center">Harga / Kg</th>
                                        <th class="text-center">Kuantitas</th>
                                        <th>Catatan</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->service_name }}</td>
                                        <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $item->qty }}</td>
                                        <td>{{ $item->notes ?? '-' }}</td>
                                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-8">
                                <p class="text-muted">Harap bawa nota ini saat pengambilan pakaian. <br>Pakaian yang tidak diambil lebih dari 30 hari di luar tanggung jawab kami.</p>
                            </div>
                            <div class="col-lg-4 text-right">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">Subtotal Kotor</div>
                                    <div class="invoice-detail-value">Rp {{ number_format($subtotal_kotor, 0, ',', '.') }}</div>
                                </div>
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">Pajak (5%)</div>
                                    <div class="invoice-detail-value">Rp {{ number_format($subtotal_kotor * 0.05, 0, ',', '.') }}</div>
                                </div>
                                <hr class="mt-2 mb-2">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">Total Tagihan</div>
                                    <div class="invoice-detail-value invoice-detail-value-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                                </div>
                                <div class="invoice-detail-item mt-3">
                                    <div class="invoice-detail-name">Nominal Bayar</div>
                                    <div class="invoice-detail-value">Rp {{ number_format($order->order_pay, 0, ',', '.') }}</div>
                                </div>
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">Kembalian</div>
                                    <div class="invoice-detail-value">Rp {{ number_format($order->order_change, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            <div class="text-md-right">
                <a href="{{ route('transaction.index') }}" class="btn btn-secondary btn-icon icon-left"><i class="fas fa-arrow-left"></i> Kembali</a>
                <button onclick="window.print()" class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Cetak Nota</button>
            </div>
        </div>
    </div>
</section>
@endsection