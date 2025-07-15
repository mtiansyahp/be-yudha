<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary()
    {
        try {
            $summary = DB::table('vw_dashboard_summary')->get();

            // Jika ingin group by `jenis` untuk front-end mudah render
            $grouped = $summary->groupBy('jenis')->map(function ($items) {
                return $items->map(function ($item) {
                    return [
                        'label' => $item->label,
                        'total' => $item->total
                    ];
                });
            });

            return response()->json([
                'success' => true,
                'data' => $grouped
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data summary dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
