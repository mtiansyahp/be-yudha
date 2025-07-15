<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelatihanController extends Controller
{
    // Get semua pelatihan beserta user yang ikut
    public function index()
    {
        // Ambil semua pelatihan dan relasi users
        $pelatihans = Pelatihan::with('users')->get();

        return response()->json($pelatihans);
    }

    // Get detail 1 pelatihan + user yang ikut
    public function show($id)
    {
        $pelatihan = Pelatihan::with('users')->find($id);

        if (!$pelatihan) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($pelatihan);
    }


    // Simpan pelatihan baru

    public function store(Request $request)
    {
        // 1. validasi field inti + peserta
        $payload = $request->validate([
            'nama_pelatihan' => 'required|string',
            'tanggal'        => 'required|date',
            'peserta'        => 'array',          // ← peserta optional array
            'peserta.*'      => 'exists:users,id' // tiap elemen harus id user
        ]);

        // 2. dapatkan next id (seperti kode Anda)
        $nextId = DB::transaction(function () {
            $last = DB::table('pelatihans')->lockForUpdate()->max('id') ?? 0;
            return $last + 1;
        });
        $payload['id'] = $nextId;

        // 3. pisahkan peserta
        $peserta = $payload['peserta'] ?? [];
        unset($payload['peserta']);

        // 4. simpan pelatihan
        $pelatihan = Pelatihan::create($payload);

        // 5. simpan pivot
        if ($peserta) {
            $pelatihan->users()->attach($peserta);   // ← isi tabel pelatihan_user
        }

        // 6. kembalikan dengan relasi
        return response()->json(
            $pelatihan->load('users'),  // sudah berisi daftar users
            201
        );
    }

    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        $pelatihan->update($request->all()); // ✅ ini akan update semua field jika sudah terisi

        return response()->json($pelatihan->load('users')); // ⬅️ kirim juga relasi
    }


    // Hapus pelatihan
    public function destroy($id)
    {
        $pelatihan = Pelatihan::find($id);

        if (!$pelatihan) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $pelatihan->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
