<?php

namespace App\Http\Controllers\PBB;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Phpml\Clustering\KMeans;

class ClusterTunggakanController extends Controller
{
    public function index()
    {
        $akses = get_url_akses();
        if ($akses) {
            return redirect()->route("pad.index");
        } else {
            return view("admin.pbb.cluster");
        }
    }
    public function get_wilayah(Request $request)
    {
        $value = $request->data;
        $data = DB::table("master.master_wilayah_ta")
            ->selectRaw("distinct(nama_kelurahan) as kelurahan")
            ->where("nama_kecamatan", $value)
            ->get();
        // dd($data);
        return response()->json($data);
    }
    public function datatable_tunggakan_wilayah(Request $request)
    {
        // dd($request->all());
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;
        // dd($wilayah);
        $view = '';
        $view = '( SELECT 
            kecamatan,
            kelurahan,
            total_jumlah_tunggakan_berat,
            total_jumlah_tunggakan_sedang,
            total_jumlah_tunggakan_ringan,
            nop_ringan,
            nop_sedang,
            nop_berat,
            nominal_ringan,
            nominal_sedang,
            nominal_berat
        FROM data.v_tunggakan_level_daerah
        GROUP BY 
            kecamatan,
            kelurahan,
            total_jumlah_tunggakan_berat,
            total_jumlah_tunggakan_sedang,
            total_jumlah_tunggakan_ringan,
            nop_ringan,
            nop_sedang,
            nop_berat,
            nominal_ringan,
            nominal_sedang,
            nominal_berat
        ORDER BY kecamatan DESC) AS a';

        // Execute the query
        $query = DB::connection("pgsql_pbb")->table(DB::connection("pgsql_pbb")->raw($view))
            ->selectRaw("
                    a.kecamatan,
                    a.kelurahan,
                    a.total_jumlah_tunggakan_berat,
                    a.total_jumlah_tunggakan_sedang,
                    a.total_jumlah_tunggakan_ringan,
                    a.nop_ringan,
                    a.nop_sedang,
                    a.nop_berat,
                    a.nominal_ringan,
                    a.nominal_sedang,
                    a.nominal_berat
                ");
        if (!is_null($kecamatan)) {
            $query->where('a.kecamatan', $kecamatan);
        }

        if (!is_null($kelurahan)) {
            $query->where('a.kelurahan', $kelurahan);
        }

        $query = $query->orderBy("a.kecamatan", "DESC")->get();

        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $row) {
                $nominal_ringan = $row->nominal_ringan ?? 0;
                $nominal_sedang = $row->nominal_sedang ?? 0;
                $nominal_berat = $row->nominal_berat ?? 0;
                $nop_ringan = $row->nop_ringan ?? 0;
                $nop_sedang = $row->nop_sedang ?? 0;
                $nop_berat = $row->nop_berat ?? 0;

                $route = url('pbb/tunggakan/sub_tunggakan_wilayah') . "/" . $row->kelurahan;
                $rowetail_kelurahan = "<a target='_BLANK' href='" . $route . "' ><u>" . $row->kelurahan . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $arr[] = [
                    'kecamatan' => $row->kecamatan,
                    'kelurahan' => $rowetail_kelurahan,
                    'total_jumlah_tunggakan_berat' => $row->total_jumlah_tunggakan_berat,
                    'total_jumlah_tunggakan_sedang' => $row->total_jumlah_tunggakan_sedang,
                    'total_jumlah_tunggakan_ringan' => $row->total_jumlah_tunggakan_ringan,
                    'nominal_ringan' => number_format($nominal_ringan),
                    'nominal_sedang' => number_format($nominal_sedang),
                    'nominal_berat' => number_format($nominal_berat),
                    'nop_ringan' => $nop_ringan,
                    'nop_sedang' => $nop_sedang,
                    'nop_berat' => $nop_berat,
                ];
            }
        }
        return Datatables::of($arr)
            ->rawColumns(['kelurahan'])
            ->make(true);
    }
    public function datatable_tunggakan_wilayah_cluster(Request $request)
    {
        // dd($request->all());
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;
        // dd($wilayah);
        $view = '';
        $view = '( SELECT 
            kecamatan,
            kelurahan,
            total_nominal_tunggakan,
            total_jumlah_nop
        FROM data.v_tunggakan_level_daerah
        GROUP BY 
            kecamatan,
            kelurahan,
            total_nominal_tunggakan,
            total_jumlah_nop
        ORDER BY kecamatan DESC) AS a';

        // Execute the query
        $query = DB::connection("pgsql_pbb")->table(DB::connection("pgsql_pbb")->raw($view))
            ->selectRaw("
                    a.kecamatan,
                    a.kelurahan,
                    a.total_nominal_tunggakan,
                    a.total_jumlah_nop
                ");
        if (!is_null($kecamatan)) {
            $query->where('a.kecamatan', $kecamatan);
        }

        if (!is_null($kelurahan)) {
            $query->where('a.kelurahan', $kelurahan);
        }

        $query = $query->orderBy("a.kecamatan", "DESC")->get();

        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $row) {
                $route = url('pbb/tunggakan/sub_tunggakan_wilayah') . "/" . $row->kelurahan;
                $rowetail_kelurahan = "<a target='_BLANK' href='" . $route . "' ><u>" . $row->kelurahan . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $arr[] = [
                    'kecamatan' => $row->kecamatan,
                    'kelurahan' => $rowetail_kelurahan,
                    'jumlah' => number_format($row->total_jumlah_nop),
                    'nominal' => number_format($row->total_nominal_tunggakan)
                ];
            }
        }
        return Datatables::of($arr)
            ->rawColumns(['kelurahan'])
            ->make(true);
    }

    // public function datatable_tunggakan_cluster_hasil(Request $request)
    // {
    //     $kecamatan = $request->kecamatan;
    //     $kelurahan = $request->kelurahan;

    //     // Mendapatkan data dari view
    //     $view = '( SELECT 
    //         kecamatan,
    //         kelurahan,
    //         total_nominal_tunggakan,
    //         total_jumlah_tunggakan,
    //         total_jumlah_nop
    //         FROM data.v_tunggakan_level_daerah
    //         GROUP BY 
    //             kecamatan,
    //             kelurahan,
    //             total_nominal_tunggakan,
    //             total_jumlah_tunggakan,
    //             total_jumlah_nop) AS a';

    //     $query = DB::connection("pgsql_pbb")->table(DB::connection("pgsql_pbb")->raw($view))
    //         ->selectRaw("
    //             a.kecamatan,
    //             a.kelurahan,
    //             a.total_nominal_tunggakan,
    //             a.total_jumlah_tunggakan,
    //             a.total_jumlah_nop
    //         ");

    //     if (!is_null($kecamatan)) {
    //         $query->where('a.kecamatan', $kecamatan);
    //     }

    //     if (!is_null($kelurahan)) {
    //         $query->where('a.kelurahan', $kelurahan);
    //     }

    //     $query = $query->orderBy("a.kecamatan", "DESC")->get();

    //     // Memformat data untuk K-Means
    //     $data = $query->map(function ($row) {
    //         return [
    //             $row->total_jumlah_tunggakan,
    //             $row->total_nominal_tunggakan,
    //         ];
    //     })->toArray();

    //     // Terapkan K-Means
    //     $kmeans = new KMeans(3); // Menentukan 3 kluster
    //     $clusters = $kmeans->cluster($data);

    //     $arr = [];
    //     $colorMap = [
    //         0 => ['cluster' => 'Hijau', 'backgroundColor' => 'rgba(0, 128, 0, 0.6)', 'borderColor' => 'rgba(0, 128, 0, 1)'],
    //         1 => ['cluster' => 'Kuning', 'backgroundColor' => 'rgba(255, 255, 0, 0.6)', 'borderColor' => 'rgba(255, 255, 0, 1)'],
    //         2 => ['cluster' => 'Merah', 'backgroundColor' => 'rgba(255, 0, 0, 0.6)', 'borderColor' => 'rgba(255, 0, 0, 1)'],
    //     ];

    //     foreach ($clusters as $i => $cluster) {
    //         foreach ($cluster as $point) {
    //             $index = array_search($point, $data);
    //             $row = $query[$index];

    //             $arr[] = [
    //                 'kecamatan' => $row->kecamatan,
    //                 'kelurahan' => $row->kelurahan,
    //                 'total_jumlah_tunggakan' => $row->total_jumlah_tunggakan,
    //                 'total_jumlah_nop' => $row->total_jumlah_nop,
    //                 'total_nominal_tunggakan' => $row->total_nominal_tunggakan,
    //                 'cluster' => $colorMap[$i]['cluster'],
    //                 'backgroundColor' => $colorMap[$i]['backgroundColor'],
    //                 'borderColor' => $colorMap[$i]['borderColor']
    //             ];
    //         }
    //     }
    //     // dd($arr);
    //     return Datatables::of($arr)
    //         ->rawColumns(['kelurahan'])
    //         ->make(true);
    // }

    // public function data_tunggakan_wilayah_cluster(Request $request)
    // {
    //     $kecamatan = $request->kecamatan;
    //     $kelurahan = $request->kelurahan;

    //     // Mendapatkan data dari view
    //     $view = '( SELECT 
    //         kecamatan,
    //         kelurahan,
    //         total_nominal_tunggakan,
    //         total_jumlah_tunggakan,
    //         total_jumlah_nop
    //         FROM data.v_tunggakan_level_daerah
    //         GROUP BY 
    //             kecamatan,
    //             kelurahan,
    //             total_nominal_tunggakan,
    //             total_jumlah_tunggakan,
    //             total_jumlah_nop) AS a';

    //     $query = DB::connection("pgsql_pbb")->table(DB::connection("pgsql_pbb")->raw($view))
    //         ->selectRaw("
    //             a.kecamatan,
    //             a.kelurahan,
    //             a.total_nominal_tunggakan,
    //             a.total_jumlah_tunggakan,
    //             a.total_jumlah_nop
    //         ");

    //     if (!is_null($kecamatan)) {
    //         $query->where('a.kecamatan', $kecamatan);
    //     }

    //     if (!is_null($kelurahan)) {
    //         $query->where('a.kelurahan', $kelurahan);
    //     }

    //     $query = $query->orderBy("a.kecamatan", "DESC")->get();

    //     // Memformat data untuk K-Means
    //     $data = $query->map(function ($row) {
    //         return [
    //             $row->total_jumlah_tunggakan,
    //             $row->total_nominal_tunggakan,
    //         ];
    //     })->toArray();

    //     // Terapkan K-Means
    //     $kmeans = new KMeans(3); // Menentukan 3 kluster
    //     $clusters = $kmeans->cluster($data);

    //     $arr = [];
    //     $colorMap = [
    //         0 => ['cluster' => 'Hijau', 'backgroundColor' => 'rgba(0, 128, 0, 0.6)', 'borderColor' => 'rgba(0, 128, 0, 1)'],
    //         1 => ['cluster' => 'Kuning', 'backgroundColor' => 'rgba(255, 255, 0, 0.6)', 'borderColor' => 'rgba(255, 255, 0, 1)'],
    //         2 => ['cluster' => 'Merah', 'backgroundColor' => 'rgba(255, 0, 0, 0.6)', 'borderColor' => 'rgba(255, 0, 0, 1)'],
    //     ];

    //     foreach ($clusters as $i => $cluster) {
    //         foreach ($cluster as $point) {
    //             $index = array_search($point, $data);
    //             $row = $query[$index];

    //             $arr[] = [
    //                 'kecamatan' => $row->kecamatan,
    //                 'kelurahan' => $row->kelurahan,
    //                 'total_jumlah_tunggakan' => $row->total_jumlah_tunggakan,
    //                 'total_nominal_tunggakan' => $row->total_nominal_tunggakan,
    //                 'cluster' => $colorMap[$i]['cluster'],
    //                 'backgroundColor' => $colorMap[$i]['backgroundColor'],
    //                 'borderColor' => $colorMap[$i]['borderColor']
    //             ];
    //         }
    //     }

    //     return response()->json($arr);
    // }

    public function datatable_tunggakan_cluster_hasil(Request $request)
    {
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;
        // query untuk menghitung rata rata
        $view_total = '( SELECT 
        kecamatan,
        kelurahan,
        total_nominal_tunggakan,
        total_jumlah_tunggakan,
        total_jumlah_nop
        FROM data.v_tunggakan_level_daerah
        GROUP BY 
            kecamatan,
            kelurahan,
            total_nominal_tunggakan,
            total_jumlah_tunggakan,
            total_jumlah_nop) AS a';

        $query_total = DB::connection("pgsql_pbb")->table(DB::connection("pgsql_pbb")->raw($view_total))
            ->selectRaw("
            a.kecamatan,
            a.kelurahan,
            a.total_nominal_tunggakan,
            a.total_jumlah_tunggakan,
            a.total_jumlah_nop
        ");

        $query_total = $query_total->orderBy("a.kecamatan", "DESC")->get();

        // Hitung rata-rata jumlah nop dan total nominal tunggakan
        $avg_tunggakan = $query_total->avg('total_jumlah_tunggakan');
        $avg_nominal = $query_total->avg('total_nominal_tunggakan');

        $view = '( SELECT 
        kecamatan,
        kelurahan,
        total_nominal_tunggakan,
        total_jumlah_tunggakan,
        total_jumlah_nop
            FROM data.v_tunggakan_level_daerah
            GROUP BY 
                kecamatan,
                kelurahan,
                total_nominal_tunggakan,
                total_jumlah_tunggakan,
                total_jumlah_nop
            ORDER BY kecamatan DESC) AS a';

        $query = DB::connection("pgsql_pbb")->table(DB::connection("pgsql_pbb")->raw($view))
            ->selectRaw("
            a.kecamatan,
            a.kelurahan,
            a.total_nominal_tunggakan,
            a.total_jumlah_tunggakan,
            a.total_jumlah_nop
        ");

        if (!is_null($kecamatan)) {
            $query->where('a.kecamatan', $kecamatan);
        }

        if (!is_null($kelurahan)) {
            $query->where('a.kelurahan', $kelurahan);
        }

        $query = $query->orderBy("a.kecamatan", "DESC")->get();


        $arr = [];
        if ($query->count() > 0) {

            // dd($avg_nop);
            foreach ($query as $key => $row) {
                // Proses clustering
                $cluster = '';
                if ($row->total_jumlah_tunggakan < $avg_tunggakan && $row->total_nominal_tunggakan < $avg_nominal) {
                    $cluster = 'Hijau';
                } elseif ($row->total_jumlah_tunggakan < $avg_tunggakan && $row->total_nominal_tunggakan >= $avg_nominal) {
                    $cluster = 'Kuning';
                } elseif ($row->total_jumlah_tunggakan > $avg_tunggakan && $row->total_nominal_tunggakan <= $avg_nominal) {
                    $cluster = 'Orange';
                } elseif ($row->total_jumlah_tunggakan > $avg_tunggakan && $row->total_nominal_tunggakan >= $avg_nominal) {
                    $cluster = 'Merah';
                }

                // Buat link untuk kelurahan
                $route = url('pbb/tunggakan/sub_tunggakan_wilayah') . "/" . $row->kelurahan;
                $row_detail_kelurahan = "<a target='_BLANK' href='" . $route . "' ><u>" . $row->kelurahan . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $arr[] = [
                    'kecamatan' => $row->kecamatan,
                    'kelurahan' => $row_detail_kelurahan,
                    'jumlah' => $row->total_jumlah_tunggakan,
                    'jumlah_tunggakan' => $row->total_jumlah_tunggakan,
                    'nominal' => number_format($row->total_nominal_tunggakan),
                    'cluster' => $cluster
                ];
            }
        }
        // dd($arr);
        return Datatables::of($arr)
            ->rawColumns(['kelurahan'])
            ->make(true);
    }

    public function data_tunggakan_wilayah_cluster(Request $request)
    {
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;

        $view_total = '( SELECT 
        kecamatan,
        kelurahan,
        total_nominal_tunggakan,
        total_jumlah_tunggakan,
        total_jumlah_nop
        FROM data.v_tunggakan_level_daerah
        GROUP BY 
            kecamatan,
            kelurahan,
            total_nominal_tunggakan,
            total_jumlah_tunggakan,
            total_jumlah_nop) AS a';

        $query_total = DB::connection("pgsql_pbb")->table(DB::connection("pgsql_pbb")->raw($view_total))
            ->selectRaw("
            a.kecamatan,
            a.kelurahan,
            a.total_nominal_tunggakan,
            a.total_jumlah_tunggakan,
            a.total_jumlah_nop
        ");

        $query_total = $query_total->orderBy("a.kecamatan", "DESC")->get();
        $avg_tunggakan = $query_total->avg('total_jumlah_tunggakan');
        $avg_nominal = $query_total->avg('total_nominal_tunggakan');

        // dd($avg_tunggakan, $avg_nominal);
        $view = '( SELECT 
        kecamatan,
        kelurahan,
        total_nominal_tunggakan,
        total_jumlah_tunggakan,
        total_jumlah_nop
        FROM data.v_tunggakan_level_daerah
        GROUP BY 
            kecamatan,
            kelurahan,
            total_nominal_tunggakan,
            total_jumlah_tunggakan,
            total_jumlah_nop
        ORDER BY kecamatan DESC) AS a';

        $query = DB::connection("pgsql_pbb")->table(DB::connection("pgsql_pbb")->raw($view))
            ->selectRaw("
            a.kecamatan,
            a.kelurahan,
            a.total_nominal_tunggakan,
            a.total_jumlah_tunggakan,
            a.total_jumlah_nop
        ");

        if (!is_null($kecamatan)) {
            $query->where('a.kecamatan', $kecamatan);
        }

        if (!is_null($kelurahan)) {
            $query->where('a.kelurahan', $kelurahan);
        }

        $query = $query->orderBy("a.kecamatan", "DESC")->get();

        $arr = [];
        if ($query->count() > 0) {
            // Calculate average values
            foreach ($query as $row) {
                $cluster = '';
                $backgroundColor = '';
                $borderColor = '';

                if ($row->total_jumlah_tunggakan < $avg_tunggakan && $row->total_nominal_tunggakan < $avg_nominal) {
                    $cluster = 'Hijau';
                    $backgroundColor = 'rgba(0, 255, 0, 0.6)';
                    $borderColor = 'rgba(0, 255, 0, 1)';
                } elseif ($row->total_jumlah_tunggakan < $avg_tunggakan && $row->total_nominal_tunggakan >= $avg_nominal) {
                    $cluster = 'Kuning';
                    $backgroundColor = 'rgba(255, 255, 0, 0.6)';
                    $borderColor = 'rgba(255, 255, 0, 1)';
                } elseif ($row->total_jumlah_tunggakan > $avg_tunggakan && $row->total_nominal_tunggakan <= $avg_nominal) {
                    $cluster = 'Orange';
                    $backgroundColor = 'rgba(255, 165, 0, 0.6)';
                    $borderColor = 'rgba(255, 165, 0, 1)';
                } elseif ($row->total_jumlah_tunggakan > $avg_tunggakan && $row->total_nominal_tunggakan >= $avg_nominal) {
                    $cluster = 'Merah';
                    $backgroundColor = 'rgba(255, 0, 0, 0.6)';
                    $borderColor = 'rgba(255, 0, 0, 1)';
                }

                $arr[] = [
                    'kecamatan' => $row->kecamatan,
                    'kelurahan' => $row->kelurahan,
                    'total_jumlah_tunggakan' => $row->total_jumlah_tunggakan,
                    'total_nominal_tunggakan' => $row->total_nominal_tunggakan,
                    'cluster' => $cluster,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor
                ];
            }
        }

        return response()->json($arr);
    }
}
