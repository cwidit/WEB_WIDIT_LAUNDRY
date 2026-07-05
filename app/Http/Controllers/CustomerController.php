<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $customers = Customer::latest()->get();

    return view('customer.index', compact('customers'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('customer.create');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'customer_name' => 'required|max:50',
        'phone' => 'required|max:20',
        'address' => 'required',
    ]);

    Customer::create([
        'customer_name' => $request->customer_name,
        'phone' => $request->phone,
        'address' => $request->address,
    ]);

    return redirect()->route('customer.index')
        ->with('success', 'Customer berhasil ditambahkan.');
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
    public function destroy($id)
    {
        // Cari datanya, sikat hapus!
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil dihapus.');
    }}
