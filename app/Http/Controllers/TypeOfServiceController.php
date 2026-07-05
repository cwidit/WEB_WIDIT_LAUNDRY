<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeOfService; 

class TypeOfServiceController extends Controller
{
    public function index()
    {
        $services = TypeOfService::latest()->get();
        return view('type_of_service.index', compact('services'));
    }

    public function create()
    {
        return view('type_of_service.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|max:50',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        TypeOfService::create([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('type_of_service.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $service = TypeOfService::findOrFail($id);
        return view('type_of_service.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|max:50',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        $service = TypeOfService::findOrFail($id);
        $service->update([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('type_of_service.index')
            ->with('success', 'Layanan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $service = TypeOfService::findOrFail($id);
        $service->delete();

        return redirect()->route('type_of_service.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}