<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        ]);

        // Ambil nilai terakhir dari ID, lalu +1
        $lastId = User::orderByDesc('id')->first()?->id;
        $newId = $lastId ? (string)((int)$lastId + 1) : '1';
        $validated['id'] = $newId;

        // Password di-hash
        $validated['password'] = Hash::make($validated['password']);

        // Ambil semua field tambahan dari payload request
        $optionalFields = [
            'posisi',
            'jurusan',
            'pendidikan_terakhir',
            'umur',
            'tempat_lahir',
            'tanggal_lahir',
            'no_telepon',
            'jabatan',
            'statusAkun',
            'sertifikasi',
            'ikut_pelatihan',
            'nilai',
            'b1',
            'b2',
            'b3',
            'b4',
            'b5',
            'a1',
            'a2',
            'a3',
            'a4',
            'a5'
        ];

        foreach ($optionalFields as $field) {
            if ($request->has($field)) {
                $validated[$field] = $request->$field;
            }
        }

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
