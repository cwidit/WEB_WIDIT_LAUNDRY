@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Data Customer</h1>
    </div>

    <div class="section-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <a href="{{ route('customer.create') }}" class="btn btn-primary">
                    Tambah Customer
                </a>
            </div>

            <div class="card-body">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Customer</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($customers as $customer)

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->address }}</td>

                            <td>

                                <a href="{{ route('customer.edit', $customer->id) }}"
                                    class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('customer.destroy', $customer->id) }}"
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
                            <td colspan="5" class="text-center">
                                Belum ada data customer.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

    </div>
</section>

@endsection