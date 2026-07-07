@extends('layouts.app')

@section('title', 'Data Jenis Service')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Jenis Service</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Jenis Service</div>
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
                            <h4>Daftar Layanan Laundry</h4>
                            <div class="card-header-action">
                                <a href="{{ route('type_of_service.create') }}" class="btn btn-primary" style="border-radius: 4px !important;"><i class="fas fa-plus"></i> Tambah Service</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Service</th>
                                            <th>Harga (Rp)</th>
                                            <th>Deskripsi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($services as $index => $s)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $s->service_name }}</td>
                                            <td>{{ number_format($s->price, 0, ',', '.') }}</td>
                                            <td>{{ $s->description }}</td>
                                            <td>
                                                <a href="{{ route('type_of_service.edit', $s->id) }}" class="btn btn-navy btn-sm">Edit</a>
                                                <form action="{{ route('type_of_service.destroy', $s->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus layanan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada data service.</td>
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