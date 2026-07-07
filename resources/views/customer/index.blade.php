@extends('layouts.app') @section('title', 'Data Customer')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Customer</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Data Customer</div>
            </div>
        </div>

        <div class="section-body">
            @if(session('success'))
                <div class="alert alert-info">{{ session('success') }}</div>
            @endif


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Pelanggan</h4>
                            <div class="card-header-action">
                                <a href="{{ route('customer.create') }}" class="btn btn-primary" style="border-radius: 4px !important;"><i class="fas fa-plus"></i> Tambah Customer</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Customer</th>
                                            <th>No. Handphone</th>
                                            <th>Alamat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customers as $index => $c)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $c->customer_name }}</td>
                                            <td>{{ $c->phone }}</td>
                                            <td>{{ $c->address }}</td>
                                            <td>
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('customer.edit', $c->id) }}" class="btn btn-navy btn-sm">Edit</a>
                                            
                                            <!-- Tombol Hapus (Menggunakan Form) -->
                                            <form action="{{ route('customer.destroy', $c->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada data customer. Silakan tambah data baru.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection