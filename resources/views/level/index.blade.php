@extends('layouts.app')

@section('title', 'Data Level')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Level</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">

                <<a href="{{ route('level.create') }}" class="btn btn-primary mb-3">>
                    Tambah Level
                </a>
                @if(session('success'))
                <div class="alert alert-success">
                {{ session('success') }}
                </div>
                @endif
                <table class="table table-bordered">
                    <thead>
                    <tr>
                    <th>No</th>
                    <th>Nama Level</th>
                    <th>Aksi</th>
                 </tr>
                </thead>
                    <tbody>
@forelse($levels as $level)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $level->level_name }}</td>
    <td>
        <a href="{{ route('level.edit', $level->id) }}" class="btn btn-warning btn-sm">
            Edit
        </a>

        <form action="{{ route('level.destroy', $level->id) }}"
              method="POST"
              style="display:inline;">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                Hapus
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="3" class="text-center">Belum ada data</td>
</tr>
@endforelse
</tbody>
                </table>

            </div>
        </div>
    </div>
</section>
@endsection