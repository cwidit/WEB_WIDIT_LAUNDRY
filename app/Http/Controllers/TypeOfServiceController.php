<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeOfService;

class TypeOfServiceController extends Controller
{
    public function index()
    {
        $services = TypeOfService::orderBy('id', 'desc')->get();
        return view('type_of_service.index', compact('services'));
    }

    public function create()
    {
        return view('type_of_service.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        TypeOfService::create([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description
        ]);

        return redirect()->route('type_of_service.index')->with('success', 'Jenis service berhasil ditambahkan!');
    }

    // Fungsi untuk menampilkan halaman form edit
    public function edit($id)
    {
        // Mencari data service berdasarkan ID
        $service = TypeOfService::findOrFail($id);
        
        // Melempar data ke halaman view edit
        return view('type_of_service.edit', compact('service'));
    }

    // Fungsi untuk memproses perubahan data ke database
    public function update(Request $request, $id)
    {
        // Validasi inputan
        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        // Mencari data dan melakukan update
        $service = TypeOfService::findOrFail($id);
        $service->update([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description
        ]);

        // Mengembalikan ke halaman index dengan pesan sukses
        return redirect()->route('type_of_service.index')->with('success', 'Data jenis service berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $service = TypeOfService::findOrFail($id);
        $service->delete();
        return redirect()->route('type_of_service.index')->with('success', 'Jenis service berhasil dihapus!');
    }
}