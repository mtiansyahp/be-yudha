<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use App\Models\LogPenilaian;
use App\Models\User;
use App\Models\Pelatihan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenilaianController extends Controller
{
    public function index()
    {

        return response()->json(Penilaian::with(['user', 'pelatihan'])->get());
    }

    public function show($id)
    {
        $penilaian = Penilaian::with(['user', 'pelatihan'])->find($id);
        if (!$penilaian) return response()->json(['message' => 'Not found'], 404);

        return response()->json($penilaian);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|unique:penilaians',
            'user_id' => 'required|exists:users,id',
            'pelatihan_id' => 'required|exists:pelatihans,id',
            'skor' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $penilaian = Penilaian::create($data);
        return response()->json($penilaian, 201);
    }

    public function update(Request $request, $id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $penilaian->update($request->all());

        return response()->json($penilaian);
    }

    public function destroy($id)
    {
        $penilaian = Penilaian::find($id);
        if (!$penilaian) return response()->json(['message' => 'Not found'], 404);

        $penilaian->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function prosesPenilaian($userId, $pelatihanId)
    {
        $user = User::findOrFail($userId);
        $pelatihan = Pelatihan::findOrFail($pelatihanId);

        // Hitung nilai fuzzy setiap variabel berdasarkan DOMAIN dari gambar
        $fuzzy = [
            'T1' => $this->hitungFuzzy(array_slice($user->only(['b1', 'b2', 'b3', 'b4', 'b5']), 0, 5)),
            'T2' => $this->hitungFuzzy(array_slice($user->only(['a1', 'a2', 'a3', 'a4', 'a5']), 0, 5)),
            'Pendidikan' => $this->pendidikanScore($user->pendidikan_terakhir),
            'Umur' => $this->umurScore($user->umur),
            'Sertifikasi' => floatval($user->sertifikasi),
            'PernahPelatihan' => floatval($user->ikut_pelatihan),
            'Jurusan' => $this->jurusanScore($user->jurusan, $pelatihan->jurusan),
            'Posisi' => $this->posisiScore($user->posisi, $pelatihan->posisi),
        ];

        // Skor akhir dihitung dari rata-rata
        $skor = round(array_sum($fuzzy) / count($fuzzy) * 100, 2);

        // Keterangan berdasarkan skor
        $keterangan = match (true) {
            $skor < 50 => 'Sangat Kurang',
            $skor < 65 => 'Kurang',
            $skor < 75 => 'Cukup',
            $skor < 85 => 'Baik',
            default => 'Sangat Baik'
        };

        // Simpan ke tabel penilaians
        $id = $pelatihanId . '-' . $userId;

        // Cek apakah penilaian sudah pernah dibuat
        $existing = Penilaian::find($id);
        if ($existing) {
            return response()->json([
                'message' => 'Penilaian sudah pernah dilakukan.',
                'data' => $existing
            ], 409); // 409 = Conflict
        }

        // Simpan ke tabel penilaians jika belum ada
        $penilaian = Penilaian::create([
            'id' => $id,
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'skor' => $skor,
            'keterangan' => $keterangan
        ]);


        // Simpan ke log_penilaians
        LogPenilaian::create([
            'penilaian_id' => $penilaian->id,
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'tanggal_penilaian' => Carbon::now(),
            'skor' => $skor,
            'keterangan' => $keterangan,
            'detail' => $fuzzy,   // otomatis diparsing oleh Eloquent → JSON
            'created_by' => auth()->user()?->id ?? 'system'
        ]);

        return response()->json([
            'message' => 'Penilaian berhasil disimpan',
            'skor' => $skor,
            'keterangan' => $keterangan
        ]);
    }

    private function hitungFuzzy($data)
    {
        $nilai = array_sum($data);
        $max = count($data); // normalisasi
        return round($nilai / $max, 4); // domain: 0.0 – 1.0
    }

    private function pendidikanScore($pendidikan)
    {
        return match (strtolower($pendidikan)) {
            'sma' => 0.2,
            'd3' => 0.4,
            's1' => 0.6,
            's2' => 0.8,
            default => 1.0,
        };
    }

    private function umurScore($umur)
    {
        if ($umur <= 25) return 1.0;
        if ($umur <= 30) return 0.85;
        if ($umur <= 35) return 0.7;
        if ($umur <= 40) return 0.5;
        return 0.3;
    }

    private function jurusanScore($jurusanUser, $jurusanPelatihan)
    {
        return strtolower($jurusanUser) === strtolower($jurusanPelatihan) ? 1.0 : 0.5;
    }

    private function posisiScore($posisiUser, $posisiPelatihan)
    {
        return strtolower($posisiUser) === strtolower($posisiPelatihan) ? 1.0 : 0.0;
    }

    public function prosesBatch($pelatihanId)
    {
        $pelatihan = Pelatihan::with('users')->find($pelatihanId);
        if (!$pelatihan) {
            return response()->json(['message' => 'Pelatihan tidak ditemukan'], 404);
        }

        $count = 0;
        $results = [];

        foreach ($pelatihan->users as $user) {
            $id = $pelatihan->id . '-' . $user->id;

            if (Penilaian::find($id)) {
                continue; // skip duplikat
            }

            $fuzzy = [
                'T1' => $this->hitungFuzzy(array_slice($user->only(['b1', 'b2', 'b3', 'b4', 'b5']), 0, 5)),
                'T2' => $this->hitungFuzzy(array_slice($user->only(['a1', 'a2', 'a3', 'a4', 'a5']), 0, 5)),
                'Pendidikan' => $this->pendidikanScore($user->pendidikan_terakhir),
                'Umur' => $this->umurScore($user->umur),
                'Sertifikasi' => floatval($user->sertifikasi),
                'PernahPelatihan' => floatval($user->ikut_pelatihan),
                'Jurusan' => $this->jurusanScore($user->jurusan, $pelatihan->jurusan),
                'Posisi' => $this->posisiScore($user->posisi, $pelatihan->posisi),
            ];

            $skor = round(array_sum($fuzzy) / count($fuzzy) * 100, 2);
            $keterangan = match (true) {
                $skor < 50 => 'Sangat Kurang',
                $skor < 65 => 'Kurang',
                $skor < 75 => 'Cukup',
                $skor < 85 => 'Baik',
                default => 'Sangat Baik'
            };

            // Simpan ke penilaians
            Penilaian::create([
                'id' => $id,
                'user_id' => $user->id,
                'pelatihan_id' => $pelatihan->id,
                'skor' => $skor,
                'keterangan' => $keterangan
            ]);

            // Simpan ke log_penilaian
            LogPenilaian::create([
                'penilaian_id' => $id,
                'user_id' => $user->id,
                'pelatihan_id' => $pelatihan->id,
                'tanggal_penilaian' => now(),
                'skor' => $skor,
                'keterangan' => $keterangan,
                'detail' => json_encode($fuzzy),
                'created_by' => auth()->user()?->id ?? 'system'
            ]);

            $count++;
            $results[] = [
                'user' => $user->nama,
                'pelatihan' => $pelatihan->nama_pelatihan,
                'skor' => $skor,
                'keterangan' => $keterangan
            ];
        }

        return response()->json([
            'message' => "Berhasil memproses $count penilaian untuk pelatihan '{$pelatihan->nama_pelatihan}'.",
            'data' => $results
        ]);
    }
    public function prosesPenilaianTsukamoto($pelatihanId, $userId)
    {
        $pelatihan = Pelatihan::findOrFail($pelatihanId)
            ->makeHidden(['created_at', 'updated_at']); // rapikan
        $user       = User::findOrFail($userId);

        // id unik ↔︎ "pelatihanId-userId"
        $pk = "{$pelatihan->id}-{$user->id}";
        if (Penilaian::find($pk)) {
            return response()->json([
                'message' => 'Penilaian sudah ada',
            ], 409);
        }

        /* ---------------------------------------------------------- *
     | 1)  FUZZY MEMBERSHIP  (0.0 – 1.0)                         |
     * ---------------------------------------------------------- */
        $mu = [
            'T1'     => $this->hitungFuzzy($user->only(['b1', 'b2', 'b3', 'b4', 'b5'])),
            'T2'     => $this->hitungFuzzy($user->only(['a1', 'a2', 'a3', 'a4', 'a5'])),
            'Pendidikan'     => $this->pendidikanScore($user->pendidikan_terakhir),
            'Umur'           => $this->umurScore($user->umur),
            'Sertifikasi'    => (float) $user->sertifikasi,
            'PernahPelatihan' => (float) $user->ikut_pelatihan,
            'Jurusan'        => $this->jurusanScore($user->jurusan, $pelatihan->jurusan),
            'Posisi'         => $this->posisiScore($user->posisi, $pelatihan->posisi),
        ];

        /* ---------------------------------------------------------- *
     | 2)  BOBOT (RULE) DARI KOLOM PELATI🚀HAN                   |
     |     b1-b5  = var wajib ; a1-a5 = var lanjutan            |
     * ---------------------------------------------------------- */
        $w = [
            'T1'              => $pelatihan->b1,
            'T2'              => $pelatihan->b2,
            'Pendidikan'      => $pelatihan->b3,
            'Umur'            => $pelatihan->b4,
            'Sertifikasi'     => $pelatihan->b5,
            'PernahPelatihan' => $pelatihan->a1,
            'Jurusan'         => $pelatihan->a2,
            'Posisi'          => $pelatihan->a3,
        ];

        /* ---------------------------------------------------------- *
     | 3)  TSUKAMOTO: z  = Σ (µ × w)  / Σ(w)                    |
     * ---------------------------------------------------------- */
        $numerator   = 0;
        $denominator = 0;
        foreach ($mu as $key => $val) {
            if (! isset($w[$key])) continue;           // jaga2
            $numerator   += $val * $w[$key];
            $denominator += $w[$key];
        }
        $z    = $denominator ? $numerator / $denominator : 0; // 0-1
        $skor = round($z * 100, 2);

        /* ---------------------------------------------------------- *
     | 4)  LABEL KUALITATIF                                     |
     * ---------------------------------------------------------- */
        $keterangan = match (true) {
            $skor < 50 => 'Sangat Kurang',
            $skor < 65 => 'Kurang',
            $skor < 75 => 'Cukup',
            $skor < 85 => 'Baik',
            default    => 'Sangat Baik'
        };

        /* ---------------------------------------------------------- *
     | 5)  SIMPAN PENILAIAN + LOG                                |
     * ---------------------------------------------------------- */
        DB::transaction(function () use (
            $pk,
            $user,
            $pelatihan,
            $skor,
            $keterangan,
            $mu,
            $w
        ) {
            Penilaian::create([
                'id'           => $pk,
                'user_id'      => $user->id,
                'pelatihan_id' => $pelatihan->id,
                'skor'         => $skor,
                'keterangan'   => $keterangan,
            ]);

            // detail JSON:  [ 'variabel' => ['µ' => …, 'w' => …, 'µw'=>…], … ]
            $detail = [];
            foreach ($mu as $k => $val) {
                $detail[$k] = [
                    'mu'       => $val,
                    'weight'   => $w[$k] ?? 0,
                    'product'  => ($w[$k] ?? 0) * $val
                ];
            }

            LogPenilaian::create([
                'penilaian_id'    => $pk,
                'user_id'         => $user->id,
                'pelatihan_id'    => $pelatihan->id,
                'tanggal_penilaian' => Carbon::now(),
                'skor'            => $skor,
                'keterangan'      => $keterangan,
                'detail'          => $detail,          // ← otomatis cast → JSON
            ]);
        });

        /* ---------------------------------------------------------- *
     | 6)  RESPONSE                                              |
     * ---------------------------------------------------------- */
        return response()->json([
            'message'     => 'Penilaian berhasil disimpan',
            'skor'        => $skor,
            'keterangan'  => $keterangan,
            'detail'      => [
                'membership' => $mu,
                'weights'    => $w,
                'produk'     => $numerator,
                'normalisasi' => $denominator,
                'z'          => $z,
            ]
        ], 201);
    }

    public function prosesBatchTsukamoto($pelatihanId)
    {
        $pelatihan = Pelatihan::with('users')->findOrFail($pelatihanId);
        $processed = [];

        foreach ($pelatihan->users as $user) {
            // skip jika sudah ada
            if (Penilaian::find("{$pelatihan->id}-{$user->id}")) continue;

            // panggil private helper di atas; praktisnya panggil lewat this
            $response = $this->prosesPenilaianTsukamoto($pelatihan->id, $user->id);

            // response()->json → ambil body
            $processed[] = $response->getData(true);
        }

        return response()->json([
            'message' => "Selesai memproses " . count($processed) . " peserta",
            'data'    => $processed
        ]);
    }
    public function destroyByPelatihan($pelatihanId)
    {
        $ids = Penilaian::where('pelatihan_id', $pelatihanId)->pluck('id')->toArray();

        // Hapus log terlebih dahulu
        LogPenilaian::whereIn('penilaian_id', $ids)->delete();

        // Lalu hapus penilaian
        Penilaian::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'Penilaian dan log berhasil dihapus']);
    }
}
