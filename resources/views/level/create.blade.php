@extends('layouts.app')

@section('title', 'Tambah Level')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Level</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">

                <form action="{{ route('level.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Nama Level</label>
                        <input type="text" name="level_name" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                    <a href="{{ route('level.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                </form>

            </div>
        </div>
    </div>
</section>
@endsection