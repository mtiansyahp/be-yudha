<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'role' => 'required|string',
            // tambahkan validasi lain sesuai kebutuhan kolom
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->except(['password']);
        if ($request->password) {
            $validated['password'] = Hash::make($request->password);
        }
        $user->update($validated);

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
