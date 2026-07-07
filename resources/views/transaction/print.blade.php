<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nota - {{ $order->order_code }}</title>
    <style>
        /* Reset margin & padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            /* Monospace font khas struk belanja / kasir */
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            line-height: 1.4;
            background: #f3f4f6;
            padding: 20px 0;
        }

        /* Lebar container disesuaikan kertas thermal roll 80mm */
        .ticket-container {
            width: 80mm;
            margin: 0 auto;
            background: #fff;
            padding: 15px 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        .header {
            margin-bottom: 10px;
        }

        .shop-name {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .shop-info {
            font-size: 10px;
            color: #4b5563;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .double-divider {
            border-top: 2px double #000;
            margin: 8px 0;
            height: 3px;
            border-bottom: 1px double #000;
        }

        .info-table, .items-table, .total-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .items-table th {
            border-bottom: 1px dashed #000;
            padding-bottom: 4px;
            text-align: left;
            font-size: 11px;
        }

        .items-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .item-row-header {
            font-weight: bold;
        }

        .item-row-details {
            font-size: 10px;
            color: #374151;
            padding-left: 5px;
        }

        .item-notes {
            font-size: 10px;
            font-style: italic;
            color: #4b5563;
            margin-top: 1px;
        }

        .total-table td {
            padding: 3px 0;
        }

        .status-banner {
            font-size: 14px;
            font-weight: bold;
            border: 1px dashed #000;
            padding: 6px;
            margin: 12px 0;
            letter-spacing: 2px;
        }

        .terms {
            font-size: 9px;
            line-height: 1.3;
            color: #4b5563;
            text-align: justify;
            margin-top: 15px;
        }

        .terms-title {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .signatures {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        .sig-block {
            text-align: center;
            width: 45%;
        }

        .sig-space {
            height: 35px;
        }

        /* Panel kontrol layar - disembunyikan saat cetak */
        .control-panel {
            width: 80mm;
            margin: 0 auto 10px auto;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 8px;
            font-family: inherit;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-print {
            background-color: #4f46e5;
            color: #fff;
        }

        .btn-back {
            background-color: #9ca3af;
            color: #fff;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .ticket-container {
                width: 80mm;
                border: none;
                box-shadow: none;
                padding: 0;
                margin: 0;
            }

            .control-panel {
                display: none;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Panel Kontrol Layar (Tidak Tercetak) -->
    <div class="control-panel">
        <a href="{{ route('transaction.show', $order->id) }}" class="btn btn-back">Kembali ke Detail</a>
        <button onclick="window.print()" class="btn btn-print">Cetak Ulang</button>
    </div>

    <div class="ticket-container">
        
        <!-- Header Toko -->
        <div class="text-center header">
            <div class="shop-name">TEEYA LAUNDRY</div>
            <div class="shop-info">
                Jl. Raya Karangampel No. 45 Indramayu<br>
                Telp: 0812-3456-7890 | WhatsApp: 0812-3456-7890
            </div>
        </div>

        <div class="double-divider"></div>

        <!-- Meta Informasi Transaksi -->
        <table class="info-table">
            <tr>
                <td width="32%">No. Nota</td>
                <td width="3%">:</td>
                <td class="font-bold">{{ $order->order_code }}</td>
            </tr>
            <tr>
                <td>Tgl Masuk</td>
                <td>:</td>
                <td>{{ date('d-m-Y H:i', strtotime($order->created_at)) }}</td>
            </tr>
            <tr>
                <td>Estimasi Selesai</td>
                <td>:</td>
                <td class="font-bold">{{ date('d-m-Y', strtotime($order->order_end_date)) }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td>:</td>
                <td class="uppercase">{{ $order->customer->customer_name ?? 'Umum' }}</td>
            </tr>
            @if($order->customer && $order->customer->phone)
            <tr>
                <td>No. HP</td>
                <td>:</td>
                <td>{{ $order->customer->phone }}</td>
            </tr>
            @endif
        </table>

        <div class="divider"></div>

        <!-- Daftar Item Cucian -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">Layanan</th>
                    <th width="20%" class="text-right">Qty</th>
                    <th width="30%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->details as $item)
                    <tr>
                        <td>
                            <span class="item-row-header">{{ $item->typeOfService->service_name ?? 'Layanan Dihapus' }}</span>
                            @if($item->notes)
                                <div class="item-notes">* Catatan: {{ $item->notes }}</div>
                            @endif
                        </td>
                        <td class="text-right font-bold" style="white-space: nowrap;">
                            {{ number_format($item->qty / 1000, 2, ',', '.') }} Kg
                        </td>
                        <td class="text-right" style="white-space: nowrap;">
                            {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    <!-- Detail Harga Satuan -->
                    <tr>
                        <td colspan="3" class="item-row-details">
                            Harga Satuan: Rp {{ number_format($item->typeOfService->price ?? 0, 0, ',', '.') }}/Kg
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <!-- Rincian Biaya -->
        <table class="total-table">
            @php
                $subtotal_kotor = $order->details->sum('subtotal');
            @endphp
            <tr>
                <td width="60%">Subtotal</td>
                <td width="5%">:</td>
                <td class="text-right">{{ number_format($subtotal_kotor, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pajak (5%)</td>
                <td>:</td>
                <td class="text-right">{{ number_format($subtotal_kotor * 0.05, 0, ',', '.') }}</td>
            </tr>
            <tr class="font-bold">
                <td>Total Tagihan</td>
                <td>:</td>
                <td class="text-right">{{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bayar (Tunai)</td>
                <td>:</td>
                <td class="text-right">{{ number_format($order->order_pay, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td>:</td>
                <td class="text-right">{{ number_format($order->order_change, 0, ',', '.') }}</td>
            </tr>
        </table>

        <!-- Status Pembayaran -->
        <div class="text-center status-banner uppercase">
            @if($order->payment_status == 0)
                *** LUNAS ***
            @else
                *** BELUM LUNAS / HUTANG ***
            @endif
        </div>

        <div class="divider"></div>

        <!-- Tanda Tangan -->
        <div class="signatures">
            <div class="sig-block">
                <div>Pelanggan</div>
                <div class="sig-space"></div>
                <div class="font-bold">({{ substr($order->customer->customer_name ?? '...', 0, 15) }})</div>
            </div>
            <div class="sig-block">
                <div>Penerima</div>
                <div class="sig-space"></div>
                <div class="font-bold">( {{ Auth::user()->name ?? 'Operator' }} )</div>
            </div>
        </div>

        <!-- Ketentuan Ketentuan -->
        <div class="terms">
            <div class="terms-title">Syarat & Ketentuan:</div>
            1. Pakaian luntur/rusak karena sifat bahan bukan tanggung jawab laundry.<br>
            2. Komplain maksimal 24 jam setelah pakaian diambil dengan menyertakan nota asli.<br>
            3. Pakaian yang tidak diambil dalam 30 hari di luar tanggung jawab kami.<br>
            4. Kehilangan pakaian diganti maksimal 5x dari biaya jasa cuci item tersebut.
        </div>

        <div class="divider" style="margin-top: 15px;"></div>

        <div class="text-center font-bold" style="font-size: 10px; margin-top: 8px;">
            Terima kasih atas kepercayaan Anda!
        </div>

    </div>

</body>
</html>