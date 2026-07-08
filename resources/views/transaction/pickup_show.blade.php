@extends('layouts.app')

@section('title', 'Proses Pengambilan Laundry')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1><i class="fas fa-box-open mr-2 text-primary"></i> Proses Pengambilan Laundry</h1>
        </div>

        <div class="section-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card card-primary shadow-sm">
                        <div class="card-header border-bottom">
                            <h4><i class="fas fa-receipt mr-2 text-primary"></i> Ringkasan Nota: {{ $order->order_code }}</h4>
                        </div>
                        <div class="card-body pt-3">
                            <table class="table table-sm table-striped table-bordered text-dark">
                                <tr>
                                    <th width="40%" class="bg-light">Nama Pelanggan</th>
                                    <td><strong>{{ $order->customer->customer_name ?? 'Umum' }}</strong></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">No. Telepon</th>
                                    <td>{{ $order->customer->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Tanggal Terima</th>
                                    <td>{{ date('d M Y H:i', strtotime($order->order_date)) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Status Pakaian</th>
                                    <td><span class="badge badge-success shadow-sm"><i class="fas fa-check-circle mr-1"></i> Selesai (Siap Ambil)</span></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Status Pembayaran</th>
                                    <td>
                                        <span class="badge {{ $order->payment_status == 0 ? 'badge-info' : 'badge-danger' }} shadow-sm">
                                            <i class="fas {{ $order->payment_status == 0 ? 'fa-check-double' : 'fa-hand-holding-usd' }} mr-1"></i>
                                            {{ $order->payment_status == 0 ? 'Lunas' : 'Belum Lunas' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <h6 class="mt-4 font-weight-bold text-dark"><i class="fas fa-tshirt mr-1 text-muted"></i> Rincian Item Cucian:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered text-center text-dark">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-left">Layanan</th>
                                            <th>Berat</th>
                                            <th class="text-left">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->details as $d)
                                            <tr>
                                                <td class="text-left">{{ $d->typeOfService->service_name ?? 'Layanan' }}</td>
                                                <td><span class="badge badge-light font-weight-bold">{{ $d->qty / 1000 }} Kg</span></td>
                                                <td class="text-left text-muted" style="font-size: 0.85rem;">{{ $d->notes ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-primary mt-3 p-3 text-center shadow-sm" style="border-radius: 8px;">
                                <span style="font-size: 0.9rem; display: block;" class="text-uppercase tracking-wider opacity-80">Total Tagihan</span>
                                <strong style="font-size: 1.5rem;">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card card-success shadow-sm">
                        <div class="card-header border-bottom">
                            <h4><i class="fas fa-user-check mr-2 text-success"></i> Form Pengambilan</h4>
                        </div>
                        <div class="card-body pt-3">
                            <form action="{{ route('transaction.pickup.process', $order->id) }}" method="POST" id="pickupForm">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark">Tanggal Ambil <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                        <input type="datetime-local" name="pickup_date" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark">Catatan Pengambilan</label>
                                    <textarea name="notes" class="form-control" style="height: 70px; resize: none;" placeholder="Contoh: Diambil oleh anggota keluarga / Kakak pelanggan"></textarea>
                                </div>

                                @if ($order->payment_status == 1)
                                    <div class="card border border-danger shadow-none bg-light mb-3">
                                        <div class="card-body p-3">
                                            <h6 class="text-danger font-weight-bold mb-3">
                                                <i class="fas fa-exclamation-circle mr-1"></i> Pelunasan Pembayaran Sisa Tagihan
                                            </h6>

                                            <div class="form-group mb-2">
                                                <label class="font-weight-bold small text-muted">Total Sisa Tagihan</label>
                                                <input type="text" class="form-control font-weight-bold text-danger form-control-sm text-right"
                                                    style="background-color: #ffebee; font-size: 1.1rem;" readonly
                                                    value="Rp {{ number_format($order->total, 0, ',', '.') }}">
                                            </div>

                                            <div class="form-group mb-2">
                                                <label class="font-weight-bold small text-dark">Uang Diterima (Rp) <span class="text-danger">*</span></label>
                                                <div class="input-group input-group-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text font-weight-bold bg-danger text-white">Rp</span>
                                                    </div>
                                                    <input type="number" name="order_pay" id="order_pay"
                                                        class="form-control font-weight-bold text-dark text-right" required
                                                        min="{{ $order->total }}" placeholder="0">
                                                </div>
                                                <small id="payment_note" class="form-text text-danger font-weight-bold mt-1"></small>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="font-weight-bold small text-muted">Uang Kembalian</label>
                                                <input type="text" id="order_change_display"
                                                    class="form-control font-weight-bold text-success text-right"
                                                    style="background-color: #e8f5e9; font-size: 1.1rem;" readonly value="Rp 0">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('transaction.pickup.index') }}" class="btn btn-lg btn-secondary mr-2 px-4 shadow-sm" style="border-radius: 4px !important;">
                                        <i class="fas fa-arrow-left mr-1"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-lg btn-success px-4 shadow-sm" style="border-radius: 4px !important;">
                                        <i class="fas fa-save mr-1"></i> Simpan Transaksi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($order->payment_status == 1)
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const orderPayInput = document.getElementById("order_pay");
                const orderChangeDisplay = document.getElementById("order_change_display");
                const paymentNote = document.getElementById("payment_note");
                const totalTagihan = {{ $order->total }};

                orderPayInput.addEventListener("input", function() {
                    const payVal = parseFloat(this.value) || 0;

                    if (payVal >= totalTagihan) {
                        const changeVal = payVal - totalTagihan;

                        orderChangeDisplay.value = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(changeVal);

                        paymentNote.innerHTML = "";
                    } else {
                        const kurang = totalTagihan - payVal;

                        orderChangeDisplay.value = "Rp 0";
                        paymentNote.innerHTML =
                            "<i class='fas fa-times-circle mr-1'></i> Uang kurang " + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(kurang);
                    }
                });
            });
        </script>
    @endif
@endsection
