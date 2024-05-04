<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardIframeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tapping_box()
    {
        try {
            // DB::table('data.tapping_box')->truncate();
            $ketetapan = DB::table("data.tb_ketetapan")->where('jenis_pajak','6')->get();
            // dd($ketetapan);
            foreach ($ketetapan as $key => $value) {
                $tahun = $value->tahun;
                $bulan = $value->bulan;
                $nop = $value->nop;
                $id_ketetapan = $value->id;

                $nilai_tapping = ApiTappingBox($nop,$tahun,$bulan);
                // $nilai_tapping = (float)$tapping;
                
                $insert['nop'] = $nop;
                $insert['bulan'] = $bulan;
                $insert['tahun'] = $tahun;
                $insert['id_ketetapan'] = $id_ketetapan;
                $insert['nilai_tapping'] = $nilai_tapping;

                
                DB::table('data.tapping_box')->insert($insert);
            }
            return 'sukses import data tapping box';

        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function index()
    {
        return view("dashboard");
    }

    public function index_iframe()
    {
        return view("dashboard_iframe");
    }

    public function nominal_target(Request $request)
    {
        $thn = date('Y');
        $target_hotel = DB::table("data.tb_target_realisasi")->where('tahun_realisasi',$thn)->where('id_jenis_pajak','1')->groupBy('id_jenis_pajak')
        ->select(DB::raw('id_jenis_pajak,SUM(target_awal_tahun) AS awal_tahun, SUM(target_setelah_papbd) AS papbd '))->first();

        dd($target_hotel);
        return response()->json($target_hotel); 
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

    public function nominalPenerimaan(Request $request)
    {
        // dd($request->all());
        // dd();
        $mingguSearch = ($request->mingguSearch)?date("W", strtotime($request->mingguSearch)):null;
        // $bulanSearch = ($request->bulanSearch)?$request->bulanSearch:date('n');
        $bulanSearch = ($request->bulanSearch)?$request->bulanSearch:null;
        $tahunSearch = date("Y");
        $jenis_pajak = $request->jenis_pajak;
        // dd($tahunSearch);
        $data_penerimaan = DB::table("dashboard.v_informasi_penerimaan")
        ->where("jenis_objek",$jenis_pajak)
        ->when($mingguSearch,function($query) use($mingguSearch){
            $query->where("minggu",$mingguSearch);
        })
        ->when($bulanSearch,function($query) use($bulanSearch){
            $query->where("bulan",$bulanSearch);
        })
        ->when($tahunSearch,function($query) use($tahunSearch){
            $query->where("tahun",$tahunSearch);
        })
        ->selectRaw("
            sum(jumlah_pembayaran) as jumlah_pembayaran
        ")
        ->groupBy("jenis_objek")
        ->first();

        if (isset($data_penerimaan->jumlah_pembayaran)) {
            $jumlah_nominal = rupiahFormat($data_penerimaan->jumlah_pembayaran);
            $angka_nominal = $data_penerimaan->jumlah_pembayaran;
        }else{
            $jumlah_nominal = 0;
            $angka_nominal = 0;
        }
        $data['jumlah_nominal'] = $jumlah_nominal;
        $data['angka_nominal'] = $angka_nominal;

        return response()->json($data); 
    }

    public function print_penerimaan(Request $request)
    {
        $mingguSearch = ($request->mingguSearch)?date("W", strtotime($request->mingguSearch)):null;
        $bulanSearch = ($request->bulanSearch)?$request->bulanSearch:date('n');
        $tahunSearch = date("Y");
        $arr_realisasi = array();
        // dd($bulanSearch);
        // dd($request->all());
        $data_penerimaan = DB::table("dashboard.v_informasi_penerimaan as vip")
        ->join("master.tb_jenis_pajak as tjp","tjp.id","vip.jenis_objek")
        ->join("master.v_mapping_pajak as vmp","vmp.id_detail_pajak",DB::raw("cast(vip.kode_rekening as int)"))
        ->leftJoin("data.tb_target_realisasi as ttr",function($query) use($tahunSearch){
            $query->on("ttr.id_sub_jenis_pajak","vmp.id_detail_pajak")
            ->where("ttr.tahun_realisasi",$tahunSearch);
        })
        ->when($mingguSearch,function($query) use($mingguSearch){
            $query->where("vip.minggu",$mingguSearch);
        })
        ->when($bulanSearch,function($query) use($bulanSearch){
            $query->where("vip.bulan",$bulanSearch);
        })
        ->when($tahunSearch,function($query) use($tahunSearch){
            $query->where("vip.tahun",$tahunSearch);
        })
        ->selectRaw("
            tjp.nama_pajak,
            tjp.kode_rekening as kode_rekening_pajak,
            vmp.nama_detail as nama_sub_pajak,
            vmp.kode_rekening as kode_rekening_sub_pajak,
            ttr.target_awal_tahun,
            ttr.target_setelah_papbd,
            sum(jumlah_pembayaran) as jumlah_realisasi
        ")
        ->groupBy("vip.jenis_objek","tjp.nama_pajak","tjp.kode_rekening","vmp.nama_detail","vmp.kode_rekening","ttr.target_awal_tahun","ttr.target_setelah_papbd")
        ->get();
        // dd($data_penerimaan);
        
        foreach ($data_penerimaan as $key => $value) {
            if (!isset($arr_realisasi[$value->nama_pajak])) {
                $arr_realisasi[$value->nama_pajak] = [
                    'nama_pajak'=>$value->nama_pajak,
                    'total_realisasi'=>0,
                    'kode_rekening'=>$value->kode_rekening_pajak,
                    'sub_jenis_pajak'=>array(),
                    "total_target_awal_tahun" => 0,
                    "total_target_setelah_papbd" => 0
                ];
            };

            $data_subjenis_pajak = [
                "kode_rekening" => $value->kode_rekening_sub_pajak,
                "nama_subjenis_pajak" => $value->nama_sub_pajak,
                "jumlah_realisasi" => $value->jumlah_realisasi,
                "target_awal_tahun" => ($value->target_awal_tahun)?$value->target_awal_tahun:0,
                "target_setelah_papbd" => ($value->target_setelah_papbd)?$value->target_setelah_papbd:0

            ];

            // total target awal tahun
            if ($value->target_awal_tahun) {
                $arr_realisasi[$value->nama_pajak]['total_target_awal_tahun'] = $arr_realisasi[$value->nama_pajak]['total_target_awal_tahun'] + (int)$value->target_awal_tahun;
            }
            // total setelah papbd
            if($value->target_setelah_papbd){
                $arr_realisasi[$value->nama_pajak]['total_target_setelah_papbd'] = $arr_realisasi[$value->nama_pajak]['total_target_setelah_papbd'] + (int)$value->target_setelah_papbd;
            }

            $arr_realisasi[$value->nama_pajak]['total_realisasi'] = $arr_realisasi[$value->nama_pajak]['total_realisasi'] + (int)$value->jumlah_realisasi;
            

            array_push($arr_realisasi[$value->nama_pajak]['sub_jenis_pajak'],$data_subjenis_pajak);
        }
        // dd($arr_realisasi);
        $data['arr_realisasi'] = $arr_realisasi;
        $data['bulan'] = $bulanSearch;
        $data['tahun'] = $tahunSearch;

        $pdf = Pdf::loadView('dashboard.print.target_realisasi', $data)
        ->setPaper('F4', 'landscape');

        // download PDF file with download method
        return $pdf->stream("Target dan Realisasi $tahunSearch.pdf");
    }

    public function nominal_bphtb(Request $request)
    {
        // dd($request->all());
        $mingguSearch = ($request->mingguSearch)?date("W", strtotime($request->mingguSearch)):null;
         // $bulanSearch = ($request->bulanSearch)?$request->bulanSearch:date('n');
        $bulanSearch = ($request->bulanSearch)?$request->bulanSearch:null;
        $tahunSearch = date("Y");

        if (is_null($bulanSearch)) {
            $where_bulan = "";
        }else{
            $where_bulan = "AND EXTRACT(MONTH FROM TGL_PEMBAYARAN_SPPT) = $bulanSearch";
        }

        $data_penerimaan = DB::select(DB::raw("SELECT sum(\"JUMLAHYGDISETOR\") FROM data.tb_sspd_bphtb
        WHERE \"NOSTPD\" IS NOT NULL
        $where_bulan
        AND EXTRACT(YEAR FROM \"TANGGALBAYAR\") = $tahunSearch"));

        // $data_penerimaan = DB::table("data.tb_sspd_bphtb")
        // ->whereRaw(" \"NOSTPD\" IS NOT NULL ")
        // // ->when($mingguSearch,function($query) use($mingguSearch){
        // //     $query->where("minggu",$mingguSearch);
        // // })
        // ->when($bulanSearch,function($query) use($bulanSearch){
        //     $query->whereRaw(" EXTRACT(MONTH FROM \"TANGGALBAYAR\") =  02 ");
        // })
        // ->when($tahunSearch,function($query) use($tahunSearch){
        //     $query->where(" EXTRACT(YEAR FROM \"TANGGALBAYAR\") = 2023 ");
        // })
        // ->selectRaw("
        //     SUM(\"JUMLAHYGDISETOR\") as jumlah_pembayaran
        // ")
        // ->first();
        $nominal = $data_penerimaan[0]->sum;

        if (isset($nominal)) {
            $jumlah_nominal = rupiahFormat($nominal);
            $angka_nominal = $nominal;
        }else{
            $angka_nominal = 0;
            $jumlah_nominal = 0;
        }
        $data['jumlah_nominal'] = $jumlah_nominal;
        $data['angka_nominal'] = $angka_nominal;

        return response()->json($data); 
    }

    public function nominal_pbb(Request $request)
    {
        // dd($request->all());
        $mingguSearch = ($request->mingguSearch)?date("W", strtotime($request->mingguSearch)):null;
         // $bulanSearch = ($request->bulanSearch)?$request->bulanSearch:date('n');
        $bulanSearch = ($request->bulanSearch)?$request->bulanSearch:null;
        $tahunSearch = date("Y");

        if (is_null($bulanSearch)) {
            $where_bulan = "";
        }else{
            $where_bulan = "AND EXTRACT(MONTH FROM TGL_PEMBAYARAN_SPPT) = $bulanSearch";
        }
        $data_penerimaan = DB::connection("oracle")->select(DB::raw("SELECT SUM(JML_SPPT_YG_DIBAYAR) as nominal FROM PEMBAYARAN_SPPT WHERE EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT) = $tahunSearch $where_bulan "));
        // dd($data_penerimaan);
        $nominal = $data_penerimaan[0]->nominal;

        if (isset($nominal)) {
            $jumlah_nominal = rupiahFormat($nominal);
            $angka_nominal = $nominal;
        }else{
            $angka_nominal = 0;
            $jumlah_nominal = 0;
        }
        $data['jumlah_nominal'] = $jumlah_nominal;
        $data['angka_nominal'] = $angka_nominal;

        return response()->json($data); 
    }

    public function grafik_wp(Request $request)
    {
        $k1=0; $k2=0; $k3=0; $k4=0; $k5=0; $k6=0; $k7=0; $k8=0; $k9=0; $k10=0; $k11=0; $k12=0;
        $p1=0; $p2=0; $p3=0; $p4=0; $p5=0; $p6=0; $p7=0; $p8=0; $p9=0; $p10=0; $p11=0; $p12=0;
        $sesion = getSession();
        $npwpd = $sesion['npwpd'];
        $tahun = date('Y');

        $data_ketetapan = DB::table("data.tb_penerimaan")
        ->where("masa_pajak_tahun",$tahun)
        ->where("npwpd",$npwpd)
        ->groupBy("masa_pajak_bulan")
        ->selectRaw(" masa_pajak_bulan,SUM(jumlah_pembayaran) AS nominal ")
        ->get();
        // dd($data_ketetapan);
        foreach ($data_ketetapan as $key => $value) {
            $bln = $value->masa_pajak_bulan;
            if ($bln == '1') {
                $k1 = $value->nominal;
            }
            if ($bln == '2') {
                $k2 = $value->nominal;
            }
            if ($bln == '3') {
                $k3 = $value->nominal;
            }
            if ($bln == '4') {
                $k4 = $value->nominal;
            }
            if ($bln == '5') {
                $k5 = $value->nominal;
            }
            if ($bln == '6') {
                $k6 = $value->nominal;
            }
            if ($bln == '7') {
                $k7 = $value->nominal;
            }
            if ($bln == '8') {
                $k8 = $value->nominal;
            }
            if ($bln == '9') {
                $k9 = $value->nominal;
            }
            if ($bln == '10') {
                $k10 = $value->nominal;
            }
            if ($bln == '11') {
                $k11 = $value->nominal;
            }
            if ($bln == '12') {
                $k12 = $value->nominal;
            }   
        }

        $data_penerimaan = DB::table("data.tb_penerimaan")
        ->where("masa_pajak_tahun",$tahun)
        ->where("npwpd",$npwpd)
        ->whereNotNull("ntpp")
        ->whereNotNull("tanggal_setor")
        ->groupBy("masa_pajak_bulan")
        ->selectRaw(" masa_pajak_bulan,SUM(jumlah_pembayaran) AS nominal ")
        ->get();
        // dd($data_penerimaan);
        foreach ($data_penerimaan as $key => $value) {
            $bln = $value->masa_pajak_bulan;
            if ($bln == '1') {
                $p1 = $value->nominal;
            }
            if ($bln == '2') {
                $p2 = $value->nominal;
            }
            if ($bln == '3') {
                $p3 = $value->nominal;
            }
            if ($bln == '4') {
                $p4 = $value->nominal;
            }
            if ($bln == '5') {
                $p5 = $value->nominal;
            }
            if ($bln == '6') {
                $p6 = $value->nominal;
            }
            if ($bln == '7') {
                $p7 = $value->nominal;
            }
            if ($bln == '8') {
                $p8 = $value->nominal;
            }
            if ($bln == '9') {
                $p9 = $value->nominal;
            }
            if ($bln == '10') {
                $p10 = $value->nominal;
            }
            if ($bln == '11') {
                $p11 = $value->nominal;
            }
            if ($bln == '12') {
                $p12 = $value->nominal;
            }   
        }

        // $data['nominal_ketetapan'] = [(int)$k1,(int)$k2,(int)$k3,(int)$k4,(int)$k5,(int)$k6,(int)$k7,(int)$k8,(int)$k9,(int)$k10,(int)$k11,(int)$k12];
        // $data['nominal_penerimaan'] = [$p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11,$p12];

        $data['k1'] = (int)$k1;  $data['k2'] = (int)$k2;  $data['k3'] = (int)$k3; $data['k4'] = (int)$k4; $data['k5'] = (int)$k5; $data['k6'] = (int)$k6; $data['k7'] = (int)$k7; $data['k8'] = (int)$k8; $data['k9'] = (int)$k9; $data['k10'] = (int)$k10; $data['k11'] = (int)$k11; $data['k12'] = (int)$k12;
        $data['p1'] = (int)$p1;  $data['p2'] = (int)$p2;  $data['p3'] = (int)$p3; $data['p4'] = (int)$p4; $data['p5'] = (int)$p5; $data['p6'] = (int)$p6; $data['p7'] = (int)$p7; $data['p8'] = (int)$p8; $data['p9'] = (int)$p9; $data['p10'] = (int)$p10; $data['p11'] = (int)$p11; $data['p12'] = (int)$p12; 
        // dd($data);
        return response()->json($data); 
    }
}
