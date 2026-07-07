<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    // Fungsi menampilkan tabel data
    public function index()
    {
        $customers = Customer::orderBy('id', 'desc')->get();
        return view('customer.index', compact('customers'));
    }

    // Fungsi menampilkan halaman form tambah data
    public function create()
    {
        return view('customer.create');
    }

    // Fungsi memproses dan menyimpan data ke database
    public function store(Request $request)
    {
        // 1. Validasi inputan agar tidak ada data yang kosong
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string'
        ], [
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'address.required' => 'Alamat wajib diisi.'
        ]);

        // 2. Simpan ke database
        Customer::create([
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        // 3. Kembalikan ke halaman index dengan pesan sukses
        return redirect()->route('customer.index')->with('success', 'Data customer berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Tembak langsung pake ID, gausah pake binding Customer $customer
        $customer = Customer::findOrFail($id);
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|max:50',
            'phone' => 'required|max:20',
            'address' => 'required',
        ]);

        // Cari datanya dulu, baru di-update
        $customer = Customer::findOrFail($id);
        $customer->update([
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // Fungsi untuk menghapus data dari database
    public function destroy($id)
    {
        // Mencari data berdasarkan ID
        $customer = Customer::findOrFail($id);
        
        // Menghapus data
        $customer->delete();

        // Mengembalikan ke halaman index dengan pesan sukses
        return redirect()->route('customer.index')->with('success', 'Data customer berhasil dihapus!');
    }}
