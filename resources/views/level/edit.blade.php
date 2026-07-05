@extends('layouts.app')

@section('title', 'Edit Level')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Edit Level</h1>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('level.update', $level->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Nama Level</label>

                    <input type="text"
                           name="level_name"
                           class="form-control"
                           value="{{ old('level_name', $level->level_name) }}">
                </div>

                <button class="btn btn-primary">
                    Update
                </button>

                <a href="{{ route('level.index') }}"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>
</section>

@endsection