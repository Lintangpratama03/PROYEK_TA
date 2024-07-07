<?php

namespace App\Http\Controllers\PBB;


use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use PhpParser\Node\Expr\AssignOp\Concat;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table("master.kecamatan")
            ->where('kode_kabupaten', '35.77')
            ->get();
        $akses = get_url_akses();
        if ($akses) {
            return redirect()->route("pad.index");
        } else {
            return view("admin.pbb.dashboard", ['data' => $data]);
        }
    }

    public function get_wilayah()
    {
        $data = DB::table("master.master_wilayah_ta")
            ->selectRaw("distinct(nama_kecamatan) as nama_kecamatan")
            ->get();
        $kec_madiun = [];
        foreach ($data as $key => $value) {
            $kec_madiun[] = [$value->nama_kecamatan];
        }
        return $kec_madiun;
    }

    public function tunggakan_pertahun(Request $request)
    {
        $startYear = $request->input('start_year', date('Y') - 9);
        $endYear = $request->input('end_year', date('Y'));
        $kecamatan = $request->input('kecamatan');
        $kelurahan = $request->input('kelurahan');

        // Generate the range of years
        $tahun = range($startYear, $endYear);

        $data = $this->get_tunggakan_pertahun($tahun, $kecamatan, $kelurahan);
        return response()->json($data);
    }

    public function get_tunggakan_pertahun($tahun, $kecamatan, $kelurahan)
    {
        $query = DB::connection("pgsql_pbb")->table('data.total_tunggakan_per_tahun AS a')
            ->selectRaw("a.tahun_sppt, sum(a.nominal_tunggakan) as pajak_terutang");

        if (!empty($tahun)) {
            $query->whereIn('a.tahun_sppt', $tahun);
        }

        if (!is_null($kecamatan)) {
            $query->where('a.kecamatan', $kecamatan);
        }

        if (!is_null($kelurahan)) {
            $query->where('a.kelurahan', $kelurahan);
        }

        $query->groupBy('a.tahun_sppt');
        $query->orderBy('a.tahun_sppt', 'DESC');

        $results = $query->get();
        $data = ['tunggakan' => [], 'tahun' => []];

        foreach ($results as $value) {
            $data['tunggakan'][$value->tahun_sppt] = $value->pajak_terutang;
            if (!in_array($value->tahun_sppt, $data['tahun'])) {
                $data['tahun'][] = $value->tahun_sppt;
            }
        }
        // dd($data);
        return $data;
    }

    // total
    public function tunggakan_pertahun_total(Request $request)
    {
        $startYear = $request->input('start_year', date('Y') - 9);
        $endYear = $request->input('end_year', date('Y'));
        $kecamatan = $request->input('kecamatan');
        $kelurahan = $request->input('kelurahan');

        // Generate the range of years
        $tahun = range($startYear, $endYear);

        $data = $this->get_tunggakan_pertahun_total($tahun, $kecamatan, $kelurahan);
        return response()->json($data);
    }

    public function get_tunggakan_pertahun_total($tahun, $kecamatan, $kelurahan)
    {
        $query = DB::connection("pgsql_pbb")->table('data.total_tunggakan_per_tahun AS a')
            ->selectRaw("a.tahun_sppt, sum(a.total_tunggakan) as pajak_terutang");

        if (!empty($tahun)) {
            $query->whereIn('a.tahun_sppt', $tahun);
        }

        if (!is_null($kecamatan)) {
            $query->where('a.kecamatan', $kecamatan);
        }

        if (!is_null($kelurahan)) {
            $query->where('a.kelurahan', $kelurahan);
        }

        $query->groupBy('a.tahun_sppt');
        $query->orderBy('a.tahun_sppt', 'DESC');

        $results = $query->get();
        $data = ['tunggakan' => [], 'tahun' => []];

        foreach ($results as $value) {
            $data['tunggakan'][$value->tahun_sppt] = $value->pajak_terutang;
            if (!in_array($value->tahun_sppt, $data['tahun'])) {
                $data['tahun'][] = $value->tahun_sppt;
            }
        }
        // dd($data);
        return $data;
    }



    public function detail_tunggakan_pertahun($tahun, $kecamatan = null, $kelurahan = null)
    {
        // dd($tahun, $kecamatan, $kelurahan);
        $tahun = $tahun;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pbb.detail_tunggakan_tahunan")->with(compact('tahun', 'kecamatan', 'kelurahan'));
    }

    public function datatable_detail_tunggakan_pertahun(Request $request)
    {
        // dd($request->all());
        $tahun = $request->tahun;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;

        $query = DB::connection("pgsql_pbb")->table('data.detail_tunggakan_pbb AS a')
            ->join("data.objek_pajak as b", "b.nop", "=", "a.nop", "left")
            ->selectRaw("
                    a.tahun_sppt,
                    a.pbb_terutang,
                    a.nominal_denda,
                    a.nominal_tunggakan,
                    b.npwp,
                    a.kecamatan,
                    a.kelurahan,
                    b.alamat_objek_pajak,
                    b.nama_subjek_pajak,
                    a.nop
            ");

        if (!is_null($tahun)) {
            if (is_array($tahun)) {
                $query->whereIn('a.tahun_sppt', $tahun);
            } else {
                $query->where('a.tahun_sppt', $tahun);
            }
        }

        if (!is_null($kecamatan)) {
            if (is_array($kecamatan)) {
                $query->whereIn('a.kecamatan', $kecamatan);
            } else {
                $query->where('a.kecamatan', $kecamatan);
            }
        }

        if (!is_null($kelurahan)) {
            if (is_array($kelurahan)) {
                $query->whereIn('a.kelurahan', $kelurahan);
            } else {
                $query->where('a.kelurahan', $kelurahan);
            }
        }
        $query->orderBy('a.tahun_sppt', 'DESC');

        $results = $query->get();
        // dd($results);
        // $dbconnect = DB::connection("pgsql_pdl");
        // dd($dbconnect);
        $arr = array();
        foreach ($results as $key => $d) {
            $arr[] = array(
                "no" => $key + 1,
                "npwp" => $d->npwp,
                "nop" => $d->nop,
                "nama_subjek_pajak" => $d->nama_subjek_pajak,
                "alamat_objek_pajak" => $d->alamat_objek_pajak,
                "kecamatan" => $d->kecamatan,
                "kelurahan" => $d->kelurahan,
                "tahun_sppt" => $d->tahun_sppt,
                "nominal_tunggakan" => rupiahFormat($d->nominal_tunggakan),
                "nominal_denda" => rupiahFormat($d->nominal_denda),
                "pbb_terutang" => rupiahFormat($d->pbb_terutang),
            );
        }
        return Datatables::of($arr)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
