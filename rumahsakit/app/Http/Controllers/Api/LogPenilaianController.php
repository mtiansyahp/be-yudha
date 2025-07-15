<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogPenilaian;
use Illuminate\Http\Request;

class LogPenilaianController extends Controller
{
    /** ← ini yang penting */
    protected $casts = [
        'detail' => 'array',   // otomatis decode/encode JSON ⇄ array
    ];



    public function index()
    {
        $logs = LogPenilaian::with(['user', 'penilaian', 'pelatihan'])->get();
        return response()->json($logs);
    }

    public function show($id)
    {
        $log = LogPenilaian::with(['user', 'penilaian', 'pelatihan'])->findOrFail($id);

        // Decode detail (jika masih string)
        if (is_string($log->detail)) {
            $log->detail = json_decode($log->detail, true);
        }

        return response()->json($log);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'penilaian_id' => 'required|string|exists:penilaians,id',
            'user_id' => 'required|string|exists:users,id',
            'pelatihan_id' => 'required|string|exists:pelatihans,id',
            'tanggal_penilaian' => 'required|date',
            'skor' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'detail' => 'required|json',
            'created_by' => 'nullable|string'
        ]);

        $log = LogPenilaian::create($validated);

        return response()->json($log, 201);
    }

    public function update(Request $request, $id)
    {
        $log = LogPenilaian::findOrFail($id);

        $validated = $request->validate([
            'penilaian_id' => 'sometimes|string|exists:penilaians,id',
            'user_id' => 'sometimes|string|exists:users,id',
            'pelatihan_id' => 'sometimes|string|exists:pelatihans,id',
            'tanggal_penilaian' => 'sometimes|date',
            'skor' => 'sometimes|numeric',
            'keterangan' => 'sometimes|string|nullable',
            'detail' => 'sometimes|json',
            'created_by' => 'sometimes|string|nullable'
        ]);

        $log->update($validated);

        return response()->json($log);
    }

    public function destroy($id)
    {
        $log = LogPenilaian::find($id);

        if (!$log) {
            return response()->json(['message' => 'Log not found'], 404);
        }

        $log->delete();

        return response()->json(['message' => 'Log deleted successfully']);
    }

    /** ambil semua log untuk satu penilaian */
    public function byPenilaian(string $penilaian_id)
    {
        $logs = LogPenilaian::with(['user', 'penilaian', 'pelatihan'])
            ->where('penilaian_id', $penilaian_id)
            ->get();

        return response()->json($logs);
    }

    /** ambil satu log spesifik berdasarkan penilaian_id & user_id */
    public function byPenilaianUser(string $penilaian_id, string $user_id)
    {
        $log = LogPenilaian::with(['user', 'penilaian', 'pelatihan'])
            ->where([
                ['penilaian_id', $penilaian_id],
                ['user_id',      $user_id],
            ])->firstOrFail();

        return response()->json($log);
    }
}
