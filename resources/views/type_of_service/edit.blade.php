@extends('layouts.app')

@section('title', 'Edit Layanan')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Layanan</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('type_of_service.update', $service->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Nama Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="service_name" class="form-control @error('service_name') is-invalid @enderror" value="{{ old('service_name', $service->service_name) }}" required>
                    @error('service_name') 
                        <span class="text-danger small">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Harga per Kg (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $service->price) }}" min="0" required>
                    @error('price') 
                        <span class="text-danger small">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Deskripsi (Opsional)</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $service->description) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Data
                </button>
                <a href="{{ route('type_of_service.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</section>
@endsection