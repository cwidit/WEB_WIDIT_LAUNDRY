@extends('layouts.app')

@section('title', 'Tambah Jenis Service')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Jenis Service</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('type_of_service.index') }}">Jenis Service</a></div>
                <div class="breadcrumb-item active">Tambah Data</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Layanan Baru</h2>
            <p class="section-lead">
                Masukkan nama layanan beserta harga per kilogram (kg).
            </p>

            <div class="row">
                <div class="col-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Isi Detail Layanan</h4>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('type_of_service.store') }}" method="POST">
                                @csrf
                                
                                <div class="form-group">
                                    <label>Nama Service (Contoh: Cuci, Setrika)</label>
                                    <input type="text" name="service_name" class="form-control" value="{{ old('service_name') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Harga per Kg (Rp)</label>
                                    <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi (Opsional)</label>
                                    <textarea name="description" class="form-control" style="height: 100px;">{{ old('description') }}</textarea>
                                </div>

                                <div class="text-right">
                                    <a href="{{ route('type_of_service.index') }}" class="btn btn-secondary mr-2">Batal</a>
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