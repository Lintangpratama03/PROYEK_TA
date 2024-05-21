<?php

namespace App\Http\Controllers\PBB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetaTunggakanController extends Controller
{
    public function index()
    {
        $query_total = DB::connection("pgsql_pbb")
            ->table("v_tunggakan_level_daerah")
            ->selectRaw("
                v_tunggakan_level_daerah.kecamatan,
                v_tunggakan_level_daerah.kelurahan,
                v_tunggakan_level_daerah.total_nominal_tunggakan,
                v_tunggakan_level_daerah.total_jumlah_tunggakan,
                v_tunggakan_level_daerah.total_jumlah_nop,
                wilayah_geometri.geometry
            ")
            ->leftJoinSub(
                DB::connection("pgsql_pbb")
                    ->table("data.wilayah_ta")
                    ->selectRaw("kecamatan, kelurahan, ST_AsGeoJSON(geometry) as geometry"),
                "wilayah_geometri",
                function ($join) {
                    $join->on("v_tunggakan_level_daerah.kecamatan", "=", "wilayah_geometri.kecamatan")
                        ->on("v_tunggakan_level_daerah.kelurahan", "=", "wilayah_geometri.kelurahan");
                }
            )
            ->orderBy("kecamatan", "DESC")
            ->get();

        $avg_nop = $query_total->avg('total_jumlah_nop');
        $avg_nominal = $query_total->avg('total_nominal_tunggakan');

        foreach ($query_total as $row) {
            $cluster = '';
            $backgroundColor = '';
            $borderColor = '';

            if ($row->total_jumlah_nop < $avg_nop && $row->total_nominal_tunggakan < $avg_nominal) {
                $cluster = 'Hijau';
                $backgroundColor = 'rgba(0, 255, 0, 0.6)';
                $borderColor = 'rgba(0, 255, 0, 1)';
            } elseif ($row->total_jumlah_nop < $avg_nop && $row->total_nominal_tunggakan >= $avg_nominal) {
                $cluster = 'Kuning';
                $backgroundColor = 'rgba(255, 255, 0, 0.6)';
                $borderColor = 'rgba(255, 255, 0, 1)';
            } elseif ($row->total_jumlah_nop > $avg_nop && $row->total_nominal_tunggakan <= $avg_nominal) {
                $cluster = 'Orange';
                $backgroundColor = 'rgba(255, 165, 0, 0.6)';
                $borderColor = 'rgba(255, 165, 0, 1)';
            } elseif ($row->total_jumlah_nop > $avg_nop && $row->total_nominal_tunggakan >= $avg_nominal) {
                $cluster = 'Merah';
                $backgroundColor = 'rgba(255, 0, 0, 0.6)';
                $borderColor = 'rgba(255, 0, 0, 1)';
            }

            $row->cluster = $cluster;
            $row->backgroundColor = $backgroundColor;
            $row->borderColor = $borderColor;
        }
        // dd($query_total);
        return view('admin.pbb.peta.peta', compact('query_total'));
    }

    // ... (Sisa kode tidak diubah)
}
