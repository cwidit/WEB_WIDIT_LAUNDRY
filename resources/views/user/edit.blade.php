@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit User</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('user.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Level</label>
                        <select name="id_level" class="form-control" required>
                            <option value="">Pilih Level</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" {{ $level->id == $user->id_level ? 'selected' : '' }}>{{ $level->level_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Password (biarkan kosong jika tidak ingin mengganti)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection