@extends('layouts.app')

@section('title','Tambah Customer')

@section('content')

<section class="section">
<div class="section-header">
<h1>Tambah Customer</h1>
</div>

<div class="card">
<div class="card-body">

<form action="{{ route('customer.store') }}" method="POST">

@csrf

<div class="mb-3">
<label>Nama Customer</label>
<input type="text" name="customer_name" class="form-control">
</div>

<div class="mb-3">
<label>No HP</label>
<input type="text" name="phone" class="form-control">
</div>

<div class="mb-3">
<label>Alamat</label>
<textarea name="address" class="form-control"></textarea>
</div>

<button class="btn btn-primary">
Simpan
</button>

<a href="{{ route('customer.index') }}" class="btn btn-secondary">
Kembali
</a>

</form>

</div>
</div>

</section>

@endsection