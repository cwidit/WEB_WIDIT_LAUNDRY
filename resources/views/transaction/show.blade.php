@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<style>
    .receipt-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        padding: 24px;
        max-width: 900px;
        margin: 0 auto;
    }

    .receipt-paper {
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 20px;
        background: linear-gradient(180deg, #fff 0%, #fcfdff 100%);
    }

    .receipt-header {
        text-align: center;
        margin-bottom: 12px;
    }

    .receipt-header h3 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #111827;
    }

    .receipt-header p {
        margin: 2px 0 0;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .receipt-divider {
        border-top: 1px dashed #cbd5e1;
        margin: 10px 0;
    }

    .receipt-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px 18px;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .receipt-meta .meta-item {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 6px;
    }

    .receipt-meta .meta-label {
        color: #6b7280;
    }

    .receipt-meta .meta-value {
        font-weight: 600;
        color: #111827;
        text-align: right;
        word-break: break-word;
    }

    .receipt-item {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .receipt-item:last-child {
        border-bottom: none;
    }

    .receipt-item .item-name {
        font-weight: 600;
        color: #111827;
    }

    .receipt-item .item-note {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 3px;
    }

    .receipt-item .item-meta {
        text-align: right;
        min-width: 115px;
        color: #374151;
        font-size: 0.9rem;
    }

    .receipt-total {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #cbd5e1;
    }

    .receipt-total .line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
        color: #374151;
    }

    .receipt-total .line.total {
        font-size: 1.05rem;
        font-weight: 700;
        color: #111827;
        padding-top: 6px;
        border-top: 1px solid #e5e7eb;
        margin-top: 8px;
    }

    .receipt-footer {
        margin-top: 14px;
        text-align: center;
        color: #6b7280;
        font-size: 0.85rem;
        line-height: 1.5;
    }

    .receipt-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 18px;
    }

    @media print {
        body {
            background: #fff !important;
        }

        .section-header,
        .no-print,
        .alert,
        .btn,
        .card,
        .section-body > .row {
            display: none !important;
        }

        .main-content {
            padding: 0 !important;
        }

        .receipt-card {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
            margin: 0 !important;
        }

        .receipt-paper {
            border: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        .receipt-meta {
            grid-template-columns: 1fr 1fr;
            gap: 8px 12px;
        }

        .receipt-item {
            padding: 8px 0;
        }
    }
</style>

<section class="section">
    <div class="section-header">
        <h1>Detail Transaksi / Nota</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-info">
                {{ session('success') }}
            </div>
        @endif

        <div class="receipt-card">
            <div class="receipt-paper">
                <div class="receipt-header">
                            <h3>Widit Laundy</h3>
                            <p>Jasa laundry cepat, bersih, dan rapi</p>
                </div>

                <div class="receipt-divider"></div>

                <div class="receipt-meta">
                    <div class="meta-item">
                        <span class="meta-label">No. Order</span>
                        <span class="meta-value">#{{ $order->order_code }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Tanggal Masuk</span>
                        <span class="meta-value">{{ date('d M Y', strtotime($order->order_date)) }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Pelanggan</span>
                        <span class="meta-value">{{ $order->customer->customer_name ?? 'Customer Dihapus' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Selesai</span>
                        <span class="meta-value">{{ date('d M Y', strtotime($order->order_end_date)) }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Telp</span>
                        <span class="meta-value">{{ $order->customer->phone ?? '-' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Alamat</span>
                        <span class="meta-value">{{ $order->customer->address ?? '-' }}</span>
                    </div>
                </div>

                <div class="receipt-divider"></div>

                <div class="mb-2">
                    <strong>Detail Cucian</strong>
                </div>

                @foreach($order->details as $item)
                    <div class="receipt-item">
                        <div>
                            <div class="item-name">{{ $item->typeOfService->service_name ?? 'Layanan Dihapus' }}</div>
                            <div class="item-note">{{ $item->notes ?? 'Tidak ada catatan' }}</div>
                        </div>
                        <div class="item-meta">
                            <div>{{ number_format($item->qty / 1000, 2, ',', '.') }} kg</div>
                            <div>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach

                <div class="receipt-total">
                    <div class="line">
                        <span>Subtotal Kotor</span>
                        <span>Rp {{ number_format($subtotal_kotor, 0, ',', '.') }}</span>
                    </div>
                    <div class="line">
                        <span>Pajak (5%)</span>
                        <span>Rp {{ number_format($subtotal_kotor * 0.05, 0, ',', '.') }}</span>
                    </div>
                    <div class="line total">
                        <span>Total Tagihan</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="line">
                        <span>Nominal Bayar</span>
                        <span>Rp {{ number_format($order->order_pay, 0, ',', '.') }}</span>
                    </div>
                    <div class="line">
                        <span>Kembalian</span>
                        <span>Rp {{ number_format($order->order_change, 0, ',', '.') }}</span>
                    </div>
                    <div class="line">
                        <span>Status Pembayaran</span>
                        <span class="badge {{ $order->payment_status == 0 ? 'badge-sky-blue' : 'badge-danger' }}">
                            {{ $order->payment_status == 0 ? 'Lunas' : 'Belum Lunas / Hutang' }}
                        </span>
                    </div>
                </div>

                <div class="receipt-footer">
                    <p>Harap simpan nota ini sebagai bukti penerimaan cucian.</p>
                    <p>Pakaian yang tidak diambil lebih dari 30 hari menjadi tanggung jawab pemilik.</p>
                    <p><strong>Status Pakaian:</strong>
                        @if($order->order_status == 0)
                            <span class="badge badge-leaf-green">Baru</span>
                        @elseif($order->order_status == 1)
                            <span class="badge badge-info">Proses</span>
                        @elseif($order->order_status == 2)
                            <span class="badge badge-success">Selesai</span>
                        @elseif($order->order_status == 3)
                            <span class="badge badge-primary">Diambil</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="receipt-actions no-print">
                <a href="{{ route('transaction.index') }}" class="btn btn-secondary btn-icon icon-left"><i class="fas fa-arrow-left"></i> Kembali</a>
                <a href="{{ route('transaction.print', $order->id) }}" target="_blank" class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Cetak Nota</a>
            </div>
        </div>

        <!-- Form Update Status (Hanya untuk Admin & Operator) -->
        @if(in_array(optional(Auth::user()->level)->level_name, ['Administrator', 'Operator']))
        <div class="card mt-4 no-print" style="max-width: 900px; margin: 20px auto 0;">
            <div class="card-header border-bottom">
                <h4>Perbarui Status Transaksi</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('transaction.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Status Cucian</label>
                                <select name="order_status" class="form-control" required>
                                    <option value="0" {{ $order->order_status == 0 ? 'selected' : '' }}>Baru</option>
                                    <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>Proses</option>
                                    <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>Selesai</option>
                                    <option value="3" {{ $order->order_status == 3 ? 'selected' : '' }}>Diambil</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Status Pembayaran</label>
                                <select name="payment_status" class="form-control" required>
                                    <option value="0" {{ $order->payment_status == 0 ? 'selected' : '' }}>Lunas</option>
                                    <option value="1" {{ $order->payment_status == 1 ? 'selected' : '' }}>Belum Lunas / Hutang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-block" style="height: 42px;">
                                <i class="fas fa-save mr-1"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection