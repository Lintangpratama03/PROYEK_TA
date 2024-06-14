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

    public function tunggakan_perbulan(Request $request)
    {
        //dd($request->all());
        $tahun = $request->input('tahun', []);
        $bulan = $request->input('bulan', []);
        $kecamatan = $request->input('kecamatan');
        $kelurahan = $request->input('kelurahan');
        $yearnow = date('Y');
        $lastyear = $yearnow - 1;
        if (count($tahun) == 0) {
            $tahun = array($yearnow, (string)$lastyear);
        }
        if (count($bulan) == 0) {
            $bulan = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
        }
        $data = $this->get_tunggakan_perbulan($tahun, $bulan, $kecamatan, $kelurahan);
        return $data;
    }

    public function get_tunggakan_perbulan($tahun, $bulan, $kecamatan, $kelurahan)
    {
        // dd($kelurahan);
        $query = DB::connection("pgsql_pbb")->table('data.total_tunggakan_per_bulan AS a')
            ->selectRaw("
        a.tahun,
        a.bulan,
        sum(a.total_tunggakan) as total_tunggakan
    ");

        if (!is_null($bulan)) {
            if (is_array($bulan)) {
                $query->whereIn('a.bulan', $bulan);
            } else {
                $query->where('a.bulan', $bulan);
            }
        }

        if (!is_null($tahun)) {
            if (is_array($tahun)) {
                $query->whereIn('a.tahun', $tahun);
            } else {
                $query->where('a.tahun', $tahun);
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

        $query->groupBy('a.tahun', 'a.bulan');
        $query->orderBy('a.tahun', 'DESC')->orderBy('a.bulan', 'ASC');

        $results = $query->get();
        // dd($results);
        $valBulan = array();
        if ($results->isNotEmpty()) {
            foreach ($results as $key => $value) {
                if (!isset($data['tunggakan'][$value->tahun])) {
                    $data['tunggakan'][$value->tahun] = array();
                }

                array_push($data['tunggakan'][$value->tahun], $value->total_tunggakan);
                array_push($valBulan, $value->bulan);
            }
            $bulanText = array();
            foreach ($valBulan as $key => $value) {
                if (!in_array(getMonth($value), $bulanText)) {
                    array_push($bulanText, getMonth($value));
                }
            }
            $data['bulan'] = $bulanText;
        } else {
            $data['tunggakan'] = 0;
            $data['tunggakan'] = 0;
            $data['tunggakan'] = 0;
            $data['bulan'] = 0;
        }
        // dd($data);
        // dd($data['tahun']);
        return $data;
    }

    public function detail_tunggakan_perbulan($tahun, $bulan, $kecamatan = null, $kelurahan = null)
    {
        $tahun = $tahun;
        $bulan = $bulan;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pdl.detail_tunggakan_bulanan")->with(compact('pajak', 'tahun', 'bulan', 'kecamatan', 'kelurahan'));
    }

    public function datatable_detail_tunggakan_perbulan(Request $request)
    {
        // dd($request->all());
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;

        $whre_condition = "";
        if (!is_null($kecamatan) && is_null($kelurahan)) {
            $whre_condition = "and nama_kecamatan = '" . $kecamatan . "' and extract(year from a.tanggal_diterima) = '$tahun'";
        } elseif (!is_null($kecamatan) && !is_null($kelurahan)) {
            $whre_condition = "and nama_kecamatan = '$kecamatan' and nama_desa = '$kelurahan' and extract(year from a.tanggal_diterima) = '$tahun'";
        } else {
            $whre_condition = "and extract(year from a.tanggal_diterima) = '$tahun'";
        }
        $select = "select 
        npwpd,  c.nama nama_op, c.jalan alamat_op, nama_wp, alamat_wp, masa_pajak_tahun, masa_pajak_bulan, masa_awal, masa_akhir, a.jumlah_pembayaran::int as nominal
        from data.tb_tunggakan a
        left join master.tb_jenis_pajak b
        on a.kode_akun_pajak::int = b.id
        left join data.tb_op c
        on a.nop = c.nop
        LEFT JOIN master.kecamatan kec ON C.kode_kecamatan = kec.kode_kecamatan
        LEFT JOIN master.desa des ON C.kode_desa = des.kode_desa 
        where ntpp is not null and a.deleted_at is null

        and b.nama_pajak= '$pajak'
        $whre_condition
        and extract(month from a.tanggal_diterima) = $bulan";
        //dd($select);
        $sismiop = DB::connection("pgsql_pdl")->select($select);
        // $dbconnect = DB::connection("pgsql_pdl");
        // dd($dbconnect);
        $arr = array();
        $Bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $nama_bln = $Bulan[$bulan];
        foreach ($sismiop as $key => $d) {
            $arr[] = array(
                "no" => $key + 1,
                "npwd" => $d->npwpd,
                "nama_op" => $d->nama_op,
                "nama_wp" => $d->nama_wp,
                "alamat_op" => $d->alamat_op,
                "alamat_wp" => $d->alamat_wp,
                "masa_pajak_tahun" => $d->masa_pajak_tahun,
                "masa_pajak_bulan" => $nama_bln,
                "nominal" => rupiahFormat($d->nominal),
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
