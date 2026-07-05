@extends('layouts.app')

@section('title','Edit Customer')

@section('content')

<section class="section">
<div class="section-header">
<h1>Edit Customer</h1>
</div>

<div class="card">
<div class="card-body">

<form action="{{ route('customer.update', $customer->id) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">
<label>Nama Customer</label>
<input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $customer->customer_name) }}">
</div>

<div class="mb-3">
<label>No HP</label>
<input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
</div>

<div class="mb-3">
<label>Alamat</label>
<textarea name="address" class="form-control">{{ old('address', $customer->address) }}</textarea>
</div>

<button type="submit" class="btn btn-primary">
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