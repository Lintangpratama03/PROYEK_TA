<?php

namespace App\Http\Controllers\PDL;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

class TunggakanPDLController extends Controller
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
            return view("admin.pdl.tunggakan", ['data' => $data]);
        }
    }

    public function datatable_tunggakan_pdl(Request $request)
    {
        $tahun = $request->input('tahun', []);
        $nama_rekening = $request->nama_rekening;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;

        $yearnow = date('Y');
        $lastyear = $yearnow - 1;


        // dd($nama_rekening);
        $query = DB::table("data.rekap_tunggakan_pdl")
            ->selectRaw(
                "
            tahun,
            nama_rekening,
            kode_rekening,
            sum(nominal) as nominal,
            sum(jumlah) as jumlah,
            kecamatan,
            kelurahan"
            )
            ->groupBy('tahun', 'kode_rekening', 'nama_rekening', 'kecamatan', 'kelurahan')
            ->orderBy('tahun', 'DESC');

        if (!count($tahun) == 0) {
            $query->whereIn('tahun', $tahun);
        }

        if (!is_null($nama_rekening)) {
            $query->where('nama_rekening', $nama_rekening);
        }
        if (!is_null($kecamatan)) {
            $query->where('kecamatan', $kecamatan);
        }

        if (!is_null($kecamatan) && !is_null($kelurahan)) {
            $query->where('kelurahan', $kelurahan);
        }

        // dd($query);
        $results = $query->get();

        // dd($results);
        $arr = array();
        if ($results->count() > 0) {
            foreach ($results as $key => $d) {
                $route = url('pdl/tunggakan/detail') . "/" . $d->kode_rekening . "/" . $d->tahun . "/" . $d->kecamatan . "/" . $d->kelurahan;
                $detail = " <a href='" . $route . "' ><u>" . $d->nama_rekening . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $arr[] =
                    array(
                        "tahun" => $d->tahun,
                        "nama_rekening" => $detail,
                        "jumlah" => number_format($d->jumlah),
                        "nominal" => rupiahFormat($d->nominal),
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan
                    );
            }
        }

        return Datatables::of($arr)
            ->rawColumns(['nama_rekening'])
            ->make(true);
    }

    public function detail($pajak, $tahun = null, $kecamatan = null, $kelurahan = null)
    {
        $pajak = $pajak;
        $tahun = $tahun;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pdl.detail_tunggakan")->with(compact('pajak', 'tahun', 'kecamatan', 'kelurahan'));
    }

    public function datatable_detail(Request $request)
    {
        //   dd($request->all());

        // dd("masuk");
        $pajak = $request->pajak;
        $tahun = $request->tahun;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;

        $where = "and masa_pajak_tahun::int=".$tahun." and b.kode_rekening='".$pajak."'";
        if (is_null($kelurahan)) {
            $where .= "and kec.nama_kecamatan =  = '$kecamatan'";
        } else {
            $where .= "and kec.nama_kecamatan = '$kecamatan' and des.nama_desa = '$kelurahan'";
        }

        
        $query = DB::connection("pgsql_pdl")->select("
        SELECT A.nop,
                A.npwpd,
                masa_pajak_tahun :: INT AS tahun,
                masa_pajak_bulan :: INT AS bulan,
                b.nama_pajak AS nama_rekening,
                b.kode_rekening,
                A.jumlah_pembayaran :: INT AS nominal_ketetapan,
                A.nama_wp AS nama_subjek_pajak,
                A.alamat_wp AS alamat_subjek_pajak,
                C.nama AS nama_objek_pajak,
                C.jalan AS alamat_objek_pajak,
                d.tanggal_pendataan AS tanggal_ketetapan,
                A.masa_awal,
                A.masa_akhir,
                A.jatuh_tempo AS tanggal_jatuh_tempo,
                'SIMPADAMA' AS sumber_data,
                now() as tanggal_update,
                nama_kecamatan kecamatan,
                nama_desa kelurahan 
        FROM
                DATA.tb_penerimaan A 
                LEFT JOIN master.tb_jenis_pajak b ON A.kode_akun_pajak :: INT = b.ID 
                LEFT JOIN tb_op C ON C.nop = A.nop
                LEFT JOIN tb_ketetapan d ON d.ID = A.id_ketetapan
                LEFT JOIN DATA.tb_op e ON A.nop = e.nop
                LEFT JOIN master.kecamatan kec ON e.kode_kecamatan = kec.kode_kecamatan
                LEFT JOIN master.desa des ON e.kode_desa = des.kode_desa  
        WHERE
                A.ntpp IS NULL 
                AND A.deleted_at IS NULL 
        ".$where."        
        ORDER BY
                b.kode_rekening,
                d.tanggal_pendataan DESC
        ");

        // $query = DB::table('data.detail_tunggakan')
        // ->select(
        //     'id',
        //     'nop',
        //     'npwpd',
        //     'tahun',
        //     'bulan',
        //     'kecamatan',
        //     'kelurahan',
        //     'nama_rekening',
        //     'nama_subjek_pajak as nama_wp',
        //     'alamat_subjek_pajak as alamat_wp',
        //     'nama_objek_pajak as nama_op',
        //     'alamat_objek_pajak as alamat_op',
        //     'masa_awal',
        //     'masa_akhir',
        //     'tanggal_jatuh_tempo',
        //     'nominal_ketetapan',
        // );

        // if(!is_null($pajak)){
        //     $query->where('nama_rekening',$pajak);
        // }
        // if(!is_null($tahun)){
        //     $query->where('tahun',$tahun);
        // }
        // if(!is_null($kecamatan)){
        //     $query->where('kecamatan',strtoupper($kecamatan));
        // }
        // if(!is_null($kelurahan)){
        //     $query->where('kelurahan',strtoupper($kelurahan));
        // }
        // dd("berhasil");
        // dd($query->get());
        $arr = array();
        // if($query->count() > 0){
        foreach ($query as $key => $d) {
            $arr[] =
                array(
                    "nop" => $d->nop,
                    "npwpd" => $d->npwpd,
                    "nama_objek_pajak" => $d->nama_objek_pajak,
                    "alamat_objek_pajak" => $d->alamat_objek_pajak,
                    "nama_subjek_pajak" => $d->nama_subjek_pajak,
                    "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                    "nama_rekening" => $d->nama_rekening,
                    "tahun" => $d->tahun,
                    "bulan" => ($d->bulan) ? getMonth($d->bulan) : "-",
                    "masa_awal" => tgl_full($d->masa_awal, 0),
                    "masa_akhir" => tgl_full($d->masa_akhir, 0),
                    "tanggal_jatuh_tempo" => $d->tanggal_jatuh_tempo,
                    "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan)
                );
        }
        // }
        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function datatable_tunggakan_wp(Request $request)
    {
        $tahun = $request->input('tahun', []);
        $nama_rekening = $request->nama_rekening;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;

        $yearnow = date('Y');
        $lastyear = $yearnow - 1;
        $query = DB::table("data.detail_tunggakan")
            ->selectRaw("nama_subjek_pajak,
                    nama_objek_pajak,
                    nop,
                    npwpd,
                    count(nama_objek_pajak) AS jumlah,
                    nama_rekening,
                    kecamatan,
                    tahun,
                    kelurahan")
            ->groupBy('nama_subjek_pajak', 'nama_objek_pajak', 'nop', 'npwpd', 'nama_rekening', 'kecamatan', 'tahun', 'kelurahan')
            ->orderBy(DB::raw("count(nama_objek_pajak)"));

        if (!count($tahun) == 0) {
            $query->whereIn('tahun', $tahun);
        }


        if (!is_null($nama_rekening)) {
            $query->where('nama_rekening', $nama_rekening);
        }


        if (!is_null($kecamatan)) {
            $query->where('kecamatan', $kecamatan);
        }

        if (!is_null($kecamatan) && !is_null($kelurahan)) {
            $query->where('kelurahan', $kelurahan);
        }

        // dd($query);
        $results = $query->get();

        $arr = array();
        if ($results->count() > 0) {
            foreach ($results as $key => $d) {
                $route_op = url('pdl/tunggakan/detail_op') . "/" . $d->nop . "/" . $d->nama_rekening . "/" . $d->tahun . "/" . $d->kecamatan . "/" . $d->kelurahan;
                $op = " <a href='" . $route_op . "' ><u>" . $d->nama_objek_pajak . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                // $route_wp = url('pdl/tunggakan/detail_wp')."/".$d->npwpd;
                // $wp = " <a href='".$route_wp."' ><u>". $d->nama_subjek_pajak ."</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $arr[] =
                    array(
                        "wp" => $d->nama_subjek_pajak,
                        "op" => $op,
                        "jumlah" => $d->jumlah,
                        "tahun" => $d->tahun,
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan
                    );
            }
        }

        return Datatables::of($arr)
            ->rawColumns(['wp', 'op'])
            ->make(true);
    }

    public function detail_op($nop, $pajak, $tahun, $kecamatan = null, $kelurahan = null)
    {
        $nop = $nop;
        $pajak = $pajak;
        $tahun = $tahun;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pdl.detail_tunggakan_op")->with(compact('nop', 'pajak', 'tahun', 'kecamatan', 'kelurahan'));
    }

    public function datatable_detail_op(Request $request)
    {
        $nop = $request->nop;
        $pajak = $request->pajak;
        $tahun = $request->tahun;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;


        // dd($kecamatan);
        $query = DB::table("data.detail_tunggakan")
            ->where('nop', $nop)
            ->where('nama_rekening', $pajak)
            ->where('tahun', $tahun);

        if (!is_null($kecamatan)) {
            $query->where('kecamatan', $kecamatan);
        }

        if (!is_null($kecamatan) && !is_null($kelurahan)) {
            $query->where('kelurahan', $kelurahan);
        }
        $result = $query->get();
        $arr = array();
        if ($result->count() > 0) {
            foreach ($result as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "nama_rekening" => $d->nama_rekening,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "masa_awal" => tgl_full($d->masa_awal, 0),
                        "masa_akhir" => tgl_full($d->masa_akhir, 0),
                        "tanggal_jatuh_tempo" => $d->tanggal_jatuh_tempo,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan)
                    );
            }
        }
        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function detail_wp($npwpd)
    {
        $npwpd = $npwpd;
        return view("admin.pdl.detail_tunggakan_wp")->with(compact('npwpd'));
    }

    public function datatable_detail_wp(Request $request)
    {
        //   dd($request->all());
        $npwpd = $request->npwpd;
        // dd("masuk");
        // $tahun = $request->tahun;
        $query = DB::table("data.detail_tunggakan")->where('npwpd', $npwpd)->get();
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "nama_rekening" => $d->nama_rekening,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "masa_awal" => tgl_full($d->masa_awal, 0),
                        "masa_akhir" => tgl_full($d->masa_akhir, 0),
                        "tanggal_jatuh_tempo" => $d->tanggal_jatuh_tempo,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan)
                    );
            }
        }
        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function get_kec_madiun(){
        $data = DB::table("master.kecamatan")
        ->selectRaw("distinct(nama_kecamatan) as nama_kecamatan")
        ->where("kode_kabupaten","35.77")
        ->get();
        $kec_madiun = [];
        foreach ($data as $key => $value) {
            $kec_madiun[] = [strtoupper($value->nama_kecamatan)];
        }
        return $kec_madiun;
    }

    public function get_wilayah(Request $request)
    {
        $wilayah = $request->wilayah;
        $value = $request->data;
        // dd($request->value);
        $data = DB::table("data.penerimaan_harian")
            ->selectRaw("distinct(kelurahan) as kelurahan")
            ->where($wilayah, $value)
            ->get();
        // dd($data);
        return response()->json($data);
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
