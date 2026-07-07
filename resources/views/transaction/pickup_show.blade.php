@extends('layouts.app')

@section('title', 'Proses Pengambilan Laundry')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Proses Pengambilan Laundry</h1>
    </div>

    <div class="section-body">
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
            <!-- Informasi Nota / Cucian -->
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Nota: {{ $order->order_code }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-striped">
                            <tr>
                                <th width="35%">Pelanggan</th>
                                <td>: {{ $order->customer->customer_name ?? 'Umum' }}</td>
                            </tr>
                            <tr>
                                <th>No. Telepon</th>
                                <td>: {{ $order->customer->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Terima</th>
                                <td>: {{ date('d M Y H:i', strtotime($order->order_date)) }}</td>
                            </tr>
                            <tr>
                                <th>Status Pakaian</th>
                                <td>: <span class="badge badge-success">Selesai (Siap Ambil)</span></td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>: 
                                    <span class="badge {{ $order->payment_status == 0 ? 'badge-sky-blue' : 'badge-danger' }}">
                                        {{ $order->payment_status == 0 ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </td>
                            </tr>
                        </table>

                        <h6 class="mt-4 font-weight-bold">Rincian Cucian:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Layanan</th>
                                        <th class="text-center">Berat (Kg)</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->details as $d)
                                    <tr>
                                        <td>{{ $d->typeOfService->service_name ?? 'Layanan' }}</td>
                                        <td class="text-center">{{ $d->qty / 1000 }} kg</td>
                                        <td>{{ $d->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info mt-3 p-2 text-center font-weight-bold" style="font-size: 1.1rem;">
                            Total Tagihan: Rp {{ number_format($order->total, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Proses Pengambilan -->
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Form Pengambilan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('transaction.pickup.process', $order->id) }}" method="POST" id="pickupForm">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tanggal Ambil <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="pickup_date" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Catatan Pengambilan</label>
                                <textarea name="notes" class="form-control" style="height: 80px;" placeholder="ex: Diambil oleh Kakak pelanggan"></textarea>
                            </div>

                            @if($order->payment_status == 1)
                                <!-- Pembayaran Pelunasan Sisa Tagihan -->
                                <div class="bg-light p-3 rounded mb-3 border">
                                    <h6 class="text-danger font-weight-bold"><i class="fas fa-exclamation-circle mr-1"></i> Pelunasan Pembayaran</h6>
                                    <hr class="my-2">
                                    
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Total Sisa Tagihan</label>
                                        <input type="text" class="form-control font-weight-bold text-danger" style="background-color: #ffebee;" readonly value="Rp {{ number_format($order->total, 0, ',', '.') }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Uang Diterima (Rp) <span class="text-danger">*</span></label>
                                        <input type="number" name="order_pay" id="order_pay" class="form-control form-control-lg font-weight-bold" required min="{{ $order->total }}" placeholder="Masukkan jumlah uang diterima">
                                    </div>

                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">Uang Kembalian</label>
                                        <input type="text" id="order_change_display" class="form-control font-weight-bold text-success" style="background-color: #e8f5e9;" readonly value="Rp 0">
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('transaction.pickup.index') }}" class="btn btn-secondary mr-2" style="border-radius: 4px !important;">Batal</a>
                                <button type="submit" class="btn btn-primary" style="border-radius: 4px !important;">Simpan Pengambilan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($order->payment_status == 1)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const orderPayInput = document.getElementById("order_pay");
        const orderChangeDisplay = document.getElementById("order_change_display");
        const totalTagihan = {{ $order->total }};

        orderPayInput.addEventListener("input", function() {
            const payVal = parseFloat(this.value) || 0;
            const changeVal = Math.max(0, payVal - totalTagihan);

            // Format Rupiah
            const formattedChange = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(changeVal);

            orderChangeDisplay.value = formattedChange;
        });
    });
</script>
@endif
@endsection
