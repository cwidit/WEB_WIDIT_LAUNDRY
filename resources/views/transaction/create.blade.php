@extends('layouts.app')

@section('title', 'Buat Transaksi Baru')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Buat Transaksi Baru</h1>
    </div>

    <div class="section-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('transaction.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h4>Informasi Nota</h4></div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label>Pilih Customer <span class="text-danger">*</span></label>
                                <select name="id_customer" class="form-control" required>
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->customer_name }} - {{ $c->phone }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Tanggal Terima <span class="text-danger">*</span></label>
                                <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="order_end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Item Cucian</h4>
                            <button type="button" class="btn btn-success btn-sm" id="add-row">+ Tambah Baris</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="item-table">
                                <thead>
                                    <tr>
                                        <th>Jenis Layanan <span class="text-danger">*</span></th>
                                        <th width="20%">Qty / Kilo <span class="text-danger">*</span></th>
                                        <th>Catatan</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][id_service]" class="form-control service-select" required>
                                                <option value="">-- Pilih Layanan --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}">
                                                        {{ $s->service_name }} (Rp {{ number_format($s->price, 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][qty]" class="form-control" min="1" value="1" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][notes]" class="form-control" placeholder="ex: Kaos robek dikit">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-row" disabled>X</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <hr>

                            <div class="row justify-content-end">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Total Bayar (Termasuk Pajak 5%)</label>
                                        <div class="alert alert-info text-center py-2 font-weight-bold" style="font-size: 1.2rem;">
                                            Kalkulasi Otomatis di Backend
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label>Jumlah Uang yang Diterima (Rp) <span class="text-danger">*</span></label>
                                        <input type="number" name="order_pay" class="form-control form-control-lg" placeholder="Masukkan nominal bayar" min="0" required>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('transaction.index') }}" class="btn btn-secondary me-2">Batal</a>
                                        <button type="submit" class="btn btn-primary btn-lg">Simpan Transaksi</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let rowIndex = 1;

        // Tombol Tambah Baris
        $('#add-row').click(function() {
            let newRow = `
                <tr class="item-row">
                    <td>
                        <select name="items[${rowIndex}][id_service]" class="form-control" required>
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->service_name }} (Rp {{ number_format($s->price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[${rowIndex}][qty]" class="form-control" min="1" value="1" required>
                    </td>
                    <td>
                        <input type="text" name="items[${rowIndex}][notes]" class="form-control" placeholder="ex: Noda tinta">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                    </td>
                </tr>
            `;
            $('#item-table tbody').append(newRow);
            rowIndex++;
            toggleRemoveButton();
        });

        // Tombol Hapus Baris
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            toggleRemoveButton();
        });

        function toggleRemoveButton() {
            let rowCount = $('#item-table tbody tr').length;
            if (rowCount <= 1) {
                $('#item-table tbody tr:first-child .remove-row').attr('disabled', true);
            } else {
                $('.remove-row').attr('disabled', false);
            }
        }
    });
</script>
@endsection