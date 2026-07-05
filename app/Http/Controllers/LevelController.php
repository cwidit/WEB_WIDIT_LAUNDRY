<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use Illuminate\Support\Facades\Validator;


class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $levels = Level::latest()->get();

    return view('level.index', compact('levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('level.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'level_name' => 'required|max:50',
    ]);

    Level::create([
        'level_name' => $request->level_name,
    ]);

    return redirect()->route('level.index')
        ->with('success', 'Data level berhasil ditambahkan.');
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
    public function edit(Level $level)
    {
    return view('level.edit', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
    $request->validate([
        'level_name' => 'required|max:50',
    ]);

    $level->update([
        'level_name' => $request->level_name,
    ]);

    return redirect()->route('level.index')
                     ->with('success','Data berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Tembak ID-nya langsung, cari datanya
        $level = \App\Models\Level::findOrFail($id);
        
        // Sikat hapus!
        $level->delete();

        return redirect()->route('level.index')
            ->with('success', 'Data Level berhasil dihapus');
    }
}
