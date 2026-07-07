@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Transaksi Baru</h1>
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
                                <label>Kode Nota Transaksi</label>
                                <input type="text" name="order_code" class="form-control font-weight-bold text-primary" value="{{ $order_code }}" readonly required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Tipe Pelanggan <span class="text-danger">*</span></label>
                                <div class="d-flex mb-2">
                                    <div class="custom-control custom-radio mr-3">
                                        <input type="radio" id="customer_type_old" name="customer_type" class="custom-control-input" value="old" {{ old('customer_type', 'old') === 'old' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customer_type_old" style="cursor: pointer;">Pelanggan Terdaftar</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customer_type_new" name="customer_type" class="custom-control-input" value="new" {{ old('customer_type') === 'new' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customer_type_new" style="cursor: pointer;">Pelanggan Baru</label>
                                    </div>
                                </div>
                            </div>

                            <div id="old-customer-wrapper" class="form-group mb-3">
                                <label>Pilih Customer <span class="text-danger">*</span></label>
                                <select name="id_customer" id="id_customer" class="form-control" required>
                                    <option value="">-- Pilih Customer --</option>
                                    <option value="new" style="display: none;">Pelanggan Baru</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('id_customer') == $c->id ? 'selected' : '' }}>{{ $c->customer_name }} - {{ $c->phone }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="new-customer-form" style="display: none; border-left: 3px solid #6777ef; padding-left: 12px; margin-top: 15px; margin-bottom: 15px; background: #fafbfe; padding-top: 10px; padding-bottom: 10px; border-radius: 4px;">
                                <h6 class="text-primary mb-2 font-weight-bold">Data Pelanggan Baru</h6>
                                <div class="form-group mb-2">
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="new_customer_name" id="new_customer_name" class="form-control form-control-sm" placeholder="Nama pelanggan baru" value="{{ old('new_customer_name') }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label>No. HP <span class="text-danger">*</span></label>
                                    <input type="text" name="new_customer_phone" id="new_customer_phone" class="form-control form-control-sm" placeholder="08xxxxxxxxxx" value="{{ old('new_customer_phone') }}">
                                </div>
                                <div class="form-group mb-0">
                                    <label>Alamat <span class="text-danger">*</span></label>
                                    <textarea name="new_customer_address" id="new_customer_address" class="form-control form-control-sm" style="height: 60px;" placeholder="Alamat lengkap">{{ old('new_customer_address') }}</textarea>
                                </div>
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
                            <button type="button" class="btn btn-primary btn-sm" id="add-row" style="border-radius: 2px !important;">+ Tambah Baris</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" id="item-table">
                                <thead>
                                    <tr>
                                        <th>Jenis Layanan <span class="text-danger">*</span></th>
                                        <th width="20%">Qty / Kilo <span class="text-danger">*</span></th>
                                        <th>Catatan</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td>
                                            <select name="id_service[]" class="form-control service-select" required>
                                                <option value="">-- Pilih Layanan --</option>
                                                @foreach($services as $s)
                                                    <option value="{{ $s->id }}" data-price="{{ $s->price }}">
                                                        {{ $s->service_name }} (Rp {{ number_format($s->price, 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="qty[]" class="form-control qty-input" min="0.001" step="any" value="1" required>
                                        </td>
                                        <td>
                                            <input type="text" name="notes[]" class="form-control notes-input" placeholder="ex: Kaos robek dikit">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-row" disabled>Hapus</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <hr>

                            <div class="row justify-content-end">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold">Jumlah Bayar (Subtotal)</label>
                                        <input type="text" id="subtotal-display" class="form-control text-center font-weight-bold text-dark" readonly value="Rp 0">
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold">Pajak (5%)</label>
                                        <input type="text" id="tax-display" class="form-control text-center font-weight-bold text-danger" readonly value="Rp 0">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Total Bayar (Termasuk Pajak)</label>
                                        <input type="hidden" name="total" id="total-hidden" value="0">
                                        <input type="text" id="total-display" class="form-control form-control-lg text-center font-weight-bold text-dark" style="font-size: 1.25rem; background-color: #eef2ff; border: 2px solid #6777ef;" readonly value="Rp 0">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold">Opsi Pembayaran <span class="text-danger">*</span></label>
                                        <select name="payment_option" id="payment_option" class="form-control" required>
                                            <option value="upfront" selected>Bayar di Muka (Lunas / DP)</option>
                                            <option value="pickup">Bayar saat Ambil (Belum Lunas)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-3" id="order_pay_wrapper">
                                        <label class="font-weight-bold">Jumlah Uang yang Diterima (Rp) <span class="text-danger">*</span></label>
                                        <input type="number" name="order_pay" id="order_pay" class="form-control form-control-lg" placeholder="Masukkan nominal bayar" min="0" value="0" required>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('transaction.index') }}" class="btn btn-secondary mr-2">Batal</a>
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
        // Toggle form customer baru berdasarkan radio button
        $('input[name="customer_type"]').change(function() {
            if ($(this).val() === 'new') {
                $('#old-customer-wrapper').slideUp();
                $('#id_customer').val('new');
                $('#new-customer-form').slideDown();
                $('#new_customer_name').attr('required', true);
                $('#new_customer_phone').attr('required', true);
                $('#new_customer_address').attr('required', true);
            } else {
                $('#old-customer-wrapper').slideDown();
                // Kembalikan ke kosong jika sebelumnya 'new'
                if ($('#id_customer').val() === 'new') {
                    $('#id_customer').val('');
                }
                $('#new-customer-form').slideUp();
                $('#new_customer_name').removeAttr('required').val('');
                $('#new_customer_phone').removeAttr('required').val('');
                $('#new_customer_address').removeAttr('required').val('');
            }
        });

        // Trigger change event on page load to restore state (e.g. from validation error)
        $('input[name="customer_type"]:checked').trigger('change');

        // Toggle field nominal bayar berdasarkan opsi pembayaran
        $('#payment_option').change(function() {
            if ($(this).val() === 'pickup') {
                $('#order_pay_wrapper').slideUp();
                $('#order_pay').removeAttr('required').val(0);
            } else {
                $('#order_pay_wrapper').slideDown();
                $('#order_pay').attr('required', true).val('');
            }
        });

        // Tombol Tambah Baris
        $('#add-row').click(function() {
            let newRow = `
                <tr class="item-row">
                    <td>
                        <select name="id_service[]" class="form-control service-select" required>
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}" data-price="{{ $s->price }}">
                                    {{ $s->service_name }} (Rp {{ number_format($s->price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="qty[]" class="form-control qty-input" min="0.001" step="any" value="1" required>
                    </td>
                    <td>
                        <input type="text" name="notes[]" class="form-control notes-input" placeholder="ex: Noda tinta">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                    </td>
                </tr>
            `;
            $('#item-table tbody').append(newRow);
            toggleRemoveButton();
            calculateTotal();
        });

        // Tombol Hapus Baris
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            toggleRemoveButton();
            calculateTotal();
        });

        // Event listener untuk kalkulasi total
        $(document).on('change', '.service-select', function() {
            calculateTotal();
        });

        $(document).on('input', '.qty-input', function() {
            calculateTotal();
        });

        function toggleRemoveButton() {
            let rowCount = $('#item-table tbody tr').length;
            if (rowCount <= 1) {
                $('#item-table tbody tr:first-child .remove-row').attr('disabled', true);
            } else {
                $('.remove-row').attr('disabled', false);
            }
        }

        function calculateTotal() {
            let subtotal = 0;
            $('#item-table tbody tr').each(function() {
                let price = parseFloat($(this).find('.service-select option:selected').data('price')) || 0;
                let qty = parseFloat($(this).find('.qty-input').val()) || 0;
                subtotal += price * qty;
            });
            let tax = subtotal * 0.05;
            let total = Math.round(subtotal + tax);

            $('#subtotal-display').val('Rp ' + Math.round(subtotal).toLocaleString('id-ID'));
            $('#tax-display').val('Rp ' + Math.round(tax).toLocaleString('id-ID'));
            $('#total-hidden').val(total);
            $('#total-display').val('Rp ' + total.toLocaleString('id-ID'));
        }

        // Jalankan kalkulasi pertama kali
        calculateTotal();
    });
</script>
@endsection