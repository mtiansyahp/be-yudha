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
        // 1) Validasi input wajib
        $validated = $request->validate([
            'nama'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'role'     => 'required|string',
        ]);

        // 2) Tentukan ID baru—mulai dari 1001, lalu +1 dari nilai terbesar saat ini (numerik)
        $lastId = (int) DB::table('users')
            ->max(DB::raw('CAST(id AS UNSIGNED)'));
        $newId  = $lastId < 1001 ? 1001 : $lastId + 1;
        $validated['id'] = (string) $newId;

        // 3) Hash password
        $validated['password'] = '$2y$12$jtUrTRCto.CqrQHMihoQceA6.4DMmJPO.NlfOkQY4UBG8m8nD3ifC';

        // 4) Ambil semua field opsional jika ada di request
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
            'a5',
        ];
        foreach ($optionalFields as $field) {
            if ($request->filled($field)) {
                $validated[$field] = $request->input($field);
            }
        }

        // 5) Buat user baru
        $user = User::create($validated);

        // 6) Kembalikan response
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
