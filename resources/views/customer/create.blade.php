@extends('layouts.app') 

@section('title', 'Tambah Customer')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Customer Baru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('customer.index') }}">Data Customer</a></div>
                <div class="breadcrumb-item active">Tambah Data</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Pendaftaran Pelanggan</h2>
            <p class="section-lead">
                Pastikan data yang diinputkan sesuai dan valid.
            </p>

            <div class="row">
                <div class="col-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Isi Data Pelanggan</h4>
                        </div>
                        <div class="card-body">
                            <!-- Menampilkan pesan error validasi -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Form Action mengarah ke route store -->
                            <form action="{{ route('customer.store') }}" method="POST">
                                @csrf <!-- Token keamanan wajib Laravel -->
                                
                                <div class="form-group">
                                    <label>Nama Customer</label>
                                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Nomor Handphone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Alamat Lengkap</label>
                                    <textarea name="address" class="form-control" style="height: 100px;" required>{{ old('address') }}</textarea>
                                </div>

                                <div class="text-right">
                                    <a href="{{ route('customer.index') }}" class="btn btn-secondary mr-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection