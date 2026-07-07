<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Level;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('level')->orderBy('id', 'desc')->get();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        $levels = Level::orderBy('level_name')->get();
        return view('user.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'id_level' => 'required|exists:level,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_level' => $request->id_level,
        ]);

        return redirect()->route('user.index')->with('success', 'Data user berhasil disimpan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $levels = Level::orderBy('level_name')->get();
        return view('user.edit', compact('user', 'levels'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'id_level' => 'required|exists:level,id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'id_level' => $request->id_level,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Data user berhasil dihapus.');
    }
}
