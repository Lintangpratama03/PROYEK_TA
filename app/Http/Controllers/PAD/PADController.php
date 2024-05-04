<?php

namespace App\Http\Controllers\PAD;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\isNull;

class PADController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return view("admin.pad.index");
    }

    public function target_realisasi_pajak(Request $request)
    {
        $thn = $request->tahun;
        // dd($thn);
        // dd($thn);

        // $data['t_hotel'] = $this->get_target_realisasi_pajak($thn,'4.1.01.06.')['target'];
        // $data['t_resto'] = $this->get_target_realisasi_pajak($thn,'4.1.01.07.')['target'];
        // $data['t_hiburan'] = $this->get_target_realisasi_pajak($thn,'4.1.01.08.')['target'];
        // $data['t_reklame'] = $this->get_target_realisasi_pajak($thn,'4.1.01.09.')['target'];
        // $data['t_ppj'] = $this->get_target_realisasi_pajak($thn,'4.1.01.10.')['target'];
        // $data['t_parkir'] = $this->get_target_realisasi_pajak($thn,'4.1.01.11.')['target'];
        // $data['t_pat'] = $this->get_target_realisasi_pajak($thn,'4.1.01.12.')['target'];
        // $data['t_sbw'] = $this->get_target_realisasi_pajak($thn,'4.1.01.13.')['target'];
        // $data['t_mblb'] = $this->get_target_realisasi_pajak($thn,'4.1.01.14.')['target'];
        // $data['t_bphtb'] = $this->get_target_realisasi_pajak($thn,'4.1.01.15.')['target'];
        // $data['t_pbb'] = $this->get_target_realisasi_pajak($thn,'4.1.01.16.')['target'];

        // $data['r_hotel'] = $this->get_target_realisasi_pajak($thn,'4.1.01.06.')['realisasi'];
        // $data['r_resto'] = $this->get_target_realisasi_pajak($thn,'4.1.01.07.')['realisasi'];
        // $data['r_hiburan'] = $this->get_target_realisasi_pajak($thn,'4.1.01.08.')['realisasi'];
        // $data['r_reklame'] = $this->get_target_realisasi_pajak($thn,'4.1.01.09.')['realisasi'];
        // $data['r_ppj'] = $this->get_target_realisasi_pajak($thn,'4.1.01.10.')['realisasi'];
        // $data['r_parkir'] = $this->get_target_realisasi_pajak($thn,'4.1.01.11.')['realisasi'];
        // $data['r_pat'] = $this->get_target_realisasi_pajak($thn,'4.1.01.12.')['realisasi'];
        // $data['r_sbw'] = $this->get_target_realisasi_pajak($thn,'4.1.01.13.')['realisasi'];
        // $data['r_mblb'] = $this->get_target_realisasi_pajak($thn,'4.1.01.14.')['realisasi'];
        // $data['r_bphtb'] = $this->get_target_realisasi_pajak($thn,'4.1.01.15.')['realisasi'];
        // $data['r_pbb'] = $this->get_target_realisasi_pajak($thn,'4.1.01.16.')['realisasi'];

        // $data['s_hotel'] = $this->get_target_realisasi_pajak($thn,'4.1.01.06.')['selisih'];
        // $data['s_resto'] = $this->get_target_realisasi_pajak($thn,'4.1.01.07.')['selisih'];
        // $data['s_hiburan'] = $this->get_target_realisasi_pajak($thn,'4.1.01.08.')['selisih'];
        // $data['s_reklame'] = $this->get_target_realisasi_pajak($thn,'4.1.01.09.')['selisih'];
        // $data['s_ppj'] = $this->get_target_realisasi_pajak($thn,'4.1.01.10.')['selisih'];
        // $data['s_parkir'] = $this->get_target_realisasi_pajak($thn,'4.1.01.11.')['selisih'];
        // $data['s_pat'] = $this->get_target_realisasi_pajak($thn,'4.1.01.12.')['selisih'];
        // $data['s_sbw'] = $this->get_target_realisasi_pajak($thn,'4.1.01.13.')['selisih'];
        // $data['s_mblb'] = $this->get_target_realisasi_pajak($thn,'4.1.01.14.')['selisih'];
        // $data['s_bphtb'] = $this->get_target_realisasi_pajak($thn,'4.1.01.15.')['selisih'];
        // $data['s_pbb'] = $this->get_target_realisasi_pajak($thn,'4.1.01.16.')['selisih'];

        $data['prsn_hotel'] = $this->get_target_realisasi_pajak($thn, '4.1.01.06.0')['persen'];
        $data['prsn_resto'] = $this->get_target_realisasi_pajak($thn, '4.1.01.07.0')['persen'];
        $data['prsn_hiburan'] = $this->get_target_realisasi_pajak($thn, '4.1.01.08.0')['persen'];
        $data['prsn_reklame'] = $this->get_target_realisasi_pajak($thn, '4.1.01.09.0')['persen'];
        $data['prsn_ppj'] = $this->get_target_realisasi_pajak($thn, '4.1.01.10.0')['persen'];
        $data['prsn_parkir'] = $this->get_target_realisasi_pajak($thn, '4.1.01.11.0')['persen'];
        $data['prsn_pat'] = $this->get_target_realisasi_pajak($thn, '4.1.01.12.0')['persen'];
        $data['prsn_bphtb'] = $this->get_target_realisasi_pajak($thn, '4.1.01.16.0')['persen'];
        $data['prsn_pbb'] = $this->get_target_realisasi_pajak($thn, '4.1.01.15.0')['persen'];

        // dd($data);
        return response()->json($data);
    }

    public function get_detail(Request $request)
    {
        $id = $request->id;
        $thn = $request->tahun;

        if (is_null($thn)) {
            $thn = date('Y');;
        } else {
            $thn = $request->tahun;
        }

        if ($id === "hotel") {
            $data['judul'] = "PBJT - Jasa Perhotelan";
            $data['target'] = $this->get_target_realisasi_pajak($thn, '4.1.01.06.0')['target'];
            $data['realisasi'] = $this->get_target_realisasi_pajak($thn, '4.1.01.06.0')['realisasi'];
            $data['selisih'] = $this->get_target_realisasi_pajak($thn, '4.1.01.06.0')['selisih'];
        } elseif ($id === "resto") {
            $data['judul'] = "PBJT - Makanan dan/atau Minuman";
            $data['target'] = $this->get_target_realisasi_pajak($thn, '4.1.01.07.0')['target'];
            $data['realisasi'] = $this->get_target_realisasi_pajak($thn, '4.1.01.07.0')['realisasi'];
            $data['selisih'] = $this->get_target_realisasi_pajak($thn, '4.1.01.07.0')['selisih'];
        } elseif ($id === "hiburan") {
            $data['judul'] = "PBJT - Jasa Kesenian dan Hiburan";
            $data['target'] = $this->get_target_realisasi_pajak($thn, '4.1.01.08.0')['target'];
            $data['realisasi'] = $this->get_target_realisasi_pajak($thn, '4.1.01.08.0')['realisasi'];
            $data['selisih'] = $this->get_target_realisasi_pajak($thn, '4.1.01.08.0')['selisih'];
        } elseif ($id === "reklame") {
            $data['judul'] = "PAJAK REKLAME";
            $data['target'] = $this->get_target_realisasi_pajak($thn, '4.1.01.09.0')['target'];
            $data['realisasi'] = $this->get_target_realisasi_pajak($thn, '4.1.01.09.0')['realisasi'];
            $data['selisih'] = $this->get_target_realisasi_pajak($thn, '4.1.01.09.0')['selisih'];
        } elseif ($id === "pat") {
            $data['judul'] = "PAJAK AIR TANAH";
            $data['target'] = $this->get_target_realisasi_pajak($thn, '4.1.01.12.0')['target'];
            $data['realisasi'] = $this->get_target_realisasi_pajak($thn, '4.1.01.12.0')['realisasi'];
            $data['selisih'] = $this->get_target_realisasi_pajak($thn, '4.1.01.12.0')['selisih'];
        } elseif ($id === "parkir") {
            $data['judul'] = "PBJT - Jasa Parkir";
            $data['target'] = $this->get_target_realisasi_pajak($thn, '4.1.01.11.0')['target'];
            $data['realisasi'] = $this->get_target_realisasi_pajak($thn, '4.1.01.11.0')['realisasi'];
            $data['selisih'] = $this->get_target_realisasi_pajak($thn, '4.1.01.11.0')['selisih'];
        } elseif ($id === "ppj") {
            $data['judul'] = "PBJT - Tenaga Listrik";
            $data['target'] = $this->get_target_realisasi_pajak($thn, '4.1.01.10.0')['target'];
            $data['realisasi'] = $this->get_target_realisasi_pajak($thn, '4.1.01.10.0')['realisasi'];
            $data['selisih'] = $this->get_target_realisasi_pajak($thn, '4.1.01.10.0')['selisih'];
        } elseif ($id === "pbb") {
            $data['judul'] = "PAJAK BUMI DAN BANGUNAN";
            $data['target'] = $this->get_target_realisasi_pajak($thn, '4.1.01.15.0')['target'];
            $data['realisasi'] = $this->get_target_realisasi_pajak($thn, '4.1.01.15.0')['realisasi'];
            $data['selisih'] = $this->get_target_realisasi_pajak($thn, '4.1.01.15.0')['selisih'];
        } elseif ($id === "bphtb") {
            $data['judul'] = "BPHTB";
            $data['target'] = $this->get_target_realisasi_pajak($thn, '4.1.01.16.0')['target'];
            $data['realisasi'] = $this->get_target_realisasi_pajak($thn, '4.1.01.16.0')['realisasi'];
            $data['selisih'] = $this->get_target_realisasi_pajak($thn, '4.1.01.16.0')['selisih'];
        }
        return response()->json($data);
    }

    public function get_target_realisasi_pajak($thn, $koderekening)
    {

        $query = DB::table("data.target_realisasi_pajak")
            ->where('tahun', $thn)
            ->where('kode_rekening', $koderekening)->first();
        if ($query) {
            $target = $query->target;
            $realisasi = $query->realisasi;
            $selisih = $realisasi - $target;
        } else {
            $target = 0;
            $realisasi = 0;
            $selisih = 0;
        }
        if ($realisasi != 0) {
            $persen = round($realisasi / $target * 100, 2);
        } else {
            $persen = 0;
        }

        $nominal['target'] = (int)$target;
        $nominal['realisasi'] = (int)$realisasi;
        $nominal['selisih'] = (int)$selisih;
        $nominal['persen'] = $persen;

        return $nominal;
    }

    public function target_realisasi_retribusi(Request $request)
    {
        $thn = $request->tahun;

        $data['t_umum'] = $this->get_target_realisasi_retribusi($thn, '410201')['target'];
        $data['t_usaha'] = $this->get_target_realisasi_retribusi($thn, '410202')['target'];
        $data['t_izin'] = $this->get_target_realisasi_retribusi($thn, '410203')['target'];

        $data['r_umum'] = $this->get_target_realisasi_retribusi($thn, '410201')['realisasi'];
        $data['r_usaha'] = $this->get_target_realisasi_retribusi($thn, '410202')['realisasi'];
        $data['r_izin'] = $this->get_target_realisasi_retribusi($thn, '410203')['realisasi'];

        $data['s_umum'] = $this->get_target_realisasi_retribusi($thn, '410201')['selisih'];
        $data['s_usaha'] = $this->get_target_realisasi_retribusi($thn, '410202')['selisih'];
        $data['s_izin'] = $this->get_target_realisasi_retribusi($thn, '410203')['selisih'];

        $data['p_umum'] = $this->get_target_realisasi_retribusi($thn, '410201')['persen'];
        $data['p_usaha'] = $this->get_target_realisasi_retribusi($thn, '410202')['persen'];
        $data['p_izin'] = $this->get_target_realisasi_retribusi($thn, '410203')['persen'];

        // dd($data);
        return response()->json($data);
    }

    public function get_target_realisasi_retribusi($thn, $koderekening)
    {

        $query = DB::table("data.target_realisasi_retribusi")
            ->where('tahun', $thn)->where('kode_rekening', $koderekening)->first();
        if ($query) {
            $target = $query->target;
            $realisasi = $query->realisasi;
            $selisih = $realisasi - $target;
            $persen = round(($realisasi / $target) * 100, 0);
        } else {
            $target = 0;
            $realisasi = 0;
            $selisih = 0;
            $persen = 0;
        }

        $nominal['target'] = (int)$target;
        $nominal['realisasi'] = (int)$realisasi;
        $nominal['selisih'] = (int)$selisih;
        $nominal['persen'] = (int)$persen;

        return $nominal;
    }


    public function target_realisasi_pad(Request $request)
    {
        $thn = $request->tahun;
        $data['t_pd'] = $this->get_target_realisasi_pad($thn, '4101')['target'];
        $data['t_rd'] = $this->get_target_realisasi_pad($thn, '4102')['target'];
        $data['t_kd'] = $this->get_target_realisasi_pad($thn, '4103')['target'];
        $data['t_ll'] = $this->get_target_realisasi_pad($thn, '4104')['target'];

        $data['r_pd'] = $this->get_target_realisasi_pad($thn, '4101')['realisasi'];
        $data['r_rd'] = $this->get_target_realisasi_pad($thn, '4102')['realisasi'];
        $data['r_kd'] = $this->get_target_realisasi_pad($thn, '4103')['realisasi'];
        $data['r_ll'] = $this->get_target_realisasi_pad($thn, '4104')['realisasi'];

        $data['s_pd'] = $this->get_target_realisasi_pad($thn, '4101')['selisih'];
        $data['s_rd'] = $this->get_target_realisasi_pad($thn, '4102')['selisih'];
        $data['s_kd'] = $this->get_target_realisasi_pad($thn, '4103')['selisih'];
        $data['s_ll'] = $this->get_target_realisasi_pad($thn, '4104')['selisih'];

        $data['p_pd'] = $this->get_target_realisasi_pad($thn, '4101')['persen'];
        $data['p_rd'] = $this->get_target_realisasi_pad($thn, '4102')['persen'];
        $data['p_kd'] = $this->get_target_realisasi_pad($thn, '4103')['persen'];
        $data['p_ll'] = $this->get_target_realisasi_pad($thn, '4104')['persen'];

        $data['t_pad'] = $data['t_pd'] + $data['t_rd'] + $data['t_kd'] + $data['t_ll'];
        $data['r_pad'] = $data['r_pd'] + $data['r_rd'] + $data['r_kd'] + $data['r_ll'];
        $data['s_pad'] = $data['s_pd'] + $data['s_rd'] + $data['s_kd'] + $data['s_ll'];

        if ($data['r_pad'] != 0) {
            $data['p_pad'] = round($data['r_pad'] / $data['t_pad'] * 100, 0);
        } else {
            $data['p_pad'] = 0;
        }

        // dd($data);
        return response()->json($data);
    }

    public function get_target_realisasi_pad($thn, $koderekening)
    {

        $query = DB::table("data.target_realisasi_pad")->where('tahun', $thn)->where('kode_rekening', $koderekening)->first();
        if ($query) {
            $target = $query->target;
            $realisasi = $query->realisasi;
            $selisih = $realisasi - $target;
            if ($realisasi != 0) {
                $persen = round(($realisasi / $target) * 100, 0);
            } else {
                $persen = 0;
            }
        } else {
            $target = 0;
            $realisasi = 0;
            $selisih = 0;
            $persen = 0;
        }

        $nominal['target'] = (int)$target;
        $nominal['realisasi'] = (int)$realisasi;
        $nominal['selisih'] = (int)$selisih;
        $nominal['persen'] = (int)$persen;

        return $nominal;
    }


    public function komposisi_pad(Request $request)
    {
        $thn = $request->tahun;
        $query = DB::table("data.komposisi_pad")->where('tahun', $thn)->get();
        $data = array();
        // dd($query);
        if ($query->isNotEmpty()) {
            foreach ($query as $key => $value) {
                $data['target'][] = (int)$value->target;
                $data['nama_rekening'][] = $value->nama_rekening;
            }
        } else {
            $data['target'][] = 0;
            $data['nama_rekening'][] = 'Data tidak ditemukan';
        }
        // dd($data);
        return $data;
    }

    public function komposisi_pajak(Request $request)
    {
        $thn = $request->tahun;
        $query = DB::table("data.target_realisasi_pajak")->where('tahun', $thn)->get();
        $data = array();
        // dd($query);
        if ($query->isNotEmpty()) {
            foreach ($query as $key => $value) {
                $data['target'][] = (int)$value->target;
                $data['nama_rekening'][] = $value->nama_rekening;
            }
        } else {
            $data['target'][] = 0;
            $data['nama_rekening'][] = 'Data tidak ditemukan';
        }
        // dd($data);
        return $data;
    }

    public function trend_target_realisasi(Request $request)
    {
        // $rekening = $request->rekening;

        if (!is_null($request->rekening)) {
            $rekening = $request->rekening . '0';
        } else {
            $rekening = $request->rekening;
        }

        $data = array();
        // dd($rekening);
        if (!is_null($rekening)) {
            $query = DB::table("data.target_realisasi")->where('level_rekening', 4)->where('kode_rekening', $rekening)->orderBy('tahun', 'ASC')->get();
            // dd($query);
            foreach ($query as $key => $value) {
                $target = $value->target;
                $target_awal_tahun = $value->target_awal_tahun;
                $realisasi = $value->realisasi;
                $data['target'][] = $target;
                $data['target_awal'][] = $target_awal_tahun;
                $data['realisasi'][] = $realisasi;
                $data['tahun'][] = $value->tahun;
                $data['persen'][] = round(($realisasi / $target) * 100, 0);
            }
        } else {
            $query = DB::table("data.target_realisasi")->where('level_rekening', 4)->where('kode_rekening', '4.1.01.09.0')->orderBy('tahun', 'ASC')->get();
            // dd($query);
            foreach ($query as $key => $value) {
                $target = $value->target;
                $target_awal_tahun = $value->target_awal_tahun;
                $realisasi = $value->realisasi;
                $data['target'][] = $target;
                $data['target_awal'][] = $target_awal_tahun;
                $data['realisasi'][] = $realisasi;
                $data['tahun'][] = $value->tahun;
                $data['persen'][] = round(($realisasi / $target) * 100, 0);
            }
        }
        return $data;
    }

    public function datatable_target_realisasi(Request $request)
    {
        if (!is_null($request->rekening)) {
            $rekening = $request->rekening . '0';
        } else {
            $rekening = $request->rekening;
        }
        $arr = array();
        if (!is_null($rekening)) {
            $query = DB::table("data.target_realisasi")->where('level_rekening', 4)->where('kode_rekening', $rekening)->orderBy('tahun', 'ASC')->get();
        } else {
            $query = DB::table("data.target_realisasi")->where('level_rekening', 4)->where('kode_rekening', '4.1.01.09.0')->orderBy('tahun', 'ASC')->get();
        }
        // dd($query);
        if ($query->count() > 0) {
            foreach ($query as $key => $value) {
                $target = $value->target;
                $realisasi = $value->realisasi;
                $target_awal_tahun = $value->target_awal_tahun;
                $persen = ($realisasi > 0 && $target > 0) ? round(($realisasi / $target) * 100, 0) : 0;
                $persen_awal = ($realisasi > 0 && $target_awal_tahun > 0) ? round(($realisasi / $target_awal_tahun) * 100, 0) : 0;
                $arr[] = array(
                    "target" => number_format($target),
                    "realisasi" => number_format($realisasi),
                    "target_awal_tahun" => number_format($target_awal_tahun),
                    "tahun" => $value->tahun,
                    "persen" => $persen . "%",
                    "persen_awal" => $persen_awal . "%"
                );
            }
        }
        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function show_qty_target_pajak(Request $request)
    {

        $tahun = $request->tahun;
        $triwulan = $request->triwulan;

        if (is_null($tahun) && is_null($triwulan)) {
            $query = DB::table("data.v_rekap_triwulan")->orderBy('tahun', 'ASC')->get();
        } elseif (!is_null($tahun) && is_null($triwulan)) {
            $query = DB::table("data.v_rekap_triwulan")->where('tahun', $tahun)->orderBy('tahun', 'ASC')->get();
        } elseif (is_null($tahun) && !is_null($triwulan)) {
            $query = DB::table("data.v_rekap_triwulan")->where('tw', $triwulan)->orderBy('tahun', 'ASC')->get();
        } else {
            $query = DB::table("data.v_rekap_triwulan")->where('tahun', $tahun)->where('tw', $triwulan)->orderBy('tahun', 'ASC')->get();
        }

        if ($query->isEmpty()) {
            return response()->json([
                "qty_target_tahun" => 0,
                "qty_realisasi" => 0,
                "qty_target_tw_dipilih" => 0,
                "qty_tw_dipilih_persen" => 0,
                "qty_tahun_persen" => 0,
                "qty_selisih_target_tw" => 0,
                "qty_selisih_target_tahun" => 0,
            ]);
        }

        $qty_target_tahun = 0;
        $qty_realisasi = 0;
        $qty_target_tw_dipilih = 0;
        $qty_tw_dipilih_persen = 0;
        $qty_tahun_persen = 0;
        $qty_selisih_target_tw = 0;
        $qty_selisih_target_tahun = 0;

        foreach ($query as $key => $value) {
            $qty_target_tahun +=  $value->target_tahun;
            $qty_realisasi +=  $value->realisasi;
            $qty_target_tw_dipilih +=  $value->target_tw_dipilih;
            $qty_tw_dipilih_persen +=  $value->tw_dipilih_persen;
            $qty_tahun_persen +=  $value->tahun_persen;
            $qty_selisih_target_tw +=  $value->selisih_target_tw;
            $qty_selisih_target_tahun +=  $value->selisih_target_tahun;
        }

        $qty_persen_dipilih_hitung = ($qty_realisasi / $qty_target_tw_dipilih) * 100;
        $qty_tahun_persen_hitung = ($qty_realisasi / $qty_target_tahun) * 100;

        return response()->json([
            "qty_target_tahun" => number_format($qty_target_tahun),
            "qty_realisasi" =>  number_format($qty_realisasi),
            "qty_target_tw_dipilih" =>  number_format($qty_target_tw_dipilih),
            "qty_tw_dipilih_persen" =>  round($qty_persen_dipilih_hitung, 2),
            "qty_tahun_persen" =>  round($qty_tahun_persen_hitung, 2),
            "qty_selisih_target_tw" =>  number_format($qty_selisih_target_tw),
            "qty_selisih_target_tahun" =>  number_format($qty_selisih_target_tahun),
        ]);
    }

    public function datatable_target_pajak(Request $request)
    {

        $tahun = $request->tahun;
        $triwulan = $request->triwulan;

        if (is_null($tahun) && is_null($triwulan)) {
            $query = DB::table("data.v_rekap_triwulan")->orderBy('tahun', 'ASC')->get();
        } elseif (!is_null($tahun) && is_null($triwulan)) {
            $query = DB::table("data.v_rekap_triwulan")->where('tahun', $tahun)->orderBy('tahun', 'ASC')->get();
        } elseif (is_null($tahun) && !is_null($triwulan)) {
            $query = DB::table("data.v_rekap_triwulan")->where('tw', $triwulan)->orderBy('tahun', 'ASC')->get();
        } else {
            $query = DB::table("data.v_rekap_triwulan")->where('tahun', $tahun)->where('tw', $triwulan)->orderBy('tahun', 'ASC')->get();
        }

        $arr = [];
        foreach ($query as $key => $value) {
            $keterangan = ($value->tw_dipilih_persen >= 100) ? "Tercapai" : "Belum Tercapai";
            $arr[] = array(
                "jenis_pajak" => $value->jenis_pajak,
                "target_tahun" => number_format($value->target_tahun),
                "realisasi" => number_format($value->realisasi),
                "target_tw_dipilih" => number_format($value->target_tw_dipilih),
                "tw_dipilih_persen" => $value->tw_dipilih_persen,
                "tahun_persen" => $value->tahun_persen,
                "selisih_target_tw" => number_format($value->selisih_target_tw),
                "selisih_target_tahun" => number_format($value->selisih_target_tahun),
                "keterangan" => $keterangan,
            );
        }

        return Datatables::of($arr)
            ->addIndexColumn()
            ->make(true);
    }

    public function datatable_target_opd(Request $request)
    {
        // dd($request->tahun);
        $retribusi = $request->retribusi;
        //dd($retribusi);
        $tahun = $request->tahun;
        $arr = array();
        $query = DB::table('data.retribusi_opd AS ro')
            ->leftJoin('data.target_opd AS t', 'ro.id', '=', 't.id_retribusi_opd')
            ->leftJoin('data.retribusi AS r', 'ro.id_retribusi', '=', 'r.id')
            ->leftJoin(DB::raw('(SELECT id_retribusi_opd,
                                SUM(target_murni) AS total_target_murni,
                                SUM(COALESCE(target_perubahan, target_murni)) AS total_target_perubahan
                                FROM data.target_opd
                                GROUP BY id_retribusi_opd) AS t_sum'), 'ro.id', '=', 't_sum.id_retribusi_opd')
            ->leftJoin(DB::raw('(SELECT target_opd_id,
                                SUM(realisasi) AS total_realisasi
                                FROM data.detail_realisasi
                                GROUP BY target_opd_id) AS dr'), 't.id', '=', 'dr.target_opd_id')
            ->select(
                'r.nama_retribusi',
                'r.keterangan',
                'r.id',
                't.tahun',
                DB::raw('SUM(total_target_murni) AS total_target_murni'),
                DB::raw('SUM(total_target_perubahan) AS total_target_perubahan'),
                DB::raw('SUM(total_realisasi) AS total_realisasi')
            )
            ->groupBy('r.nama_retribusi', 'r.keterangan', 'r.id', 't.tahun');

        if (!is_null($retribusi)) {
            $query->where("ro.id_retribusi", '=', $retribusi);
        }

        if (!is_null($tahun)) {
            $query->where("t.tahun", '=', $tahun);
        }

        $results = $query->get();
        // dd($results);

        if ($results->count() > 0) {
            foreach ($results as $key => $value) {
                $total_target_murni = $value->total_target_murni;
                $realisasi = $value->total_realisasi;
                $total_target_perubahan = $value->total_target_perubahan;
                $persen = ($realisasi > 0 && $total_target_murni > 0) ? round(($realisasi / $total_target_murni) * 100, 0) : 0;
                $persen_awal = ($realisasi > 0 && $total_target_perubahan > 0) ? round(($realisasi / $total_target_perubahan) * 100, 0) : 0;

                $route = url('pad/target_opd/detail_target_opd') . "/" . $value->tahun . "/" . $value->id;
                $detail_nop = "<a target='_BLANK' href='" . $route . "' ><u>" . $value->nama_retribusi . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";


                $arr[] = array(
                    "total_target_murni" => number_format($total_target_murni),
                    "realisasi" => number_format($realisasi),
                    "total_target_perubahan" => number_format($total_target_perubahan),
                    "tahun" => $value->tahun,
                    "keterangan" => $value->keterangan,
                    "nama_retribusi" => $detail_nop,
                    "persen" => $persen . "%",
                    "persen_awal" => $persen_awal . "%"
                );
            }
        }
        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->rawColumns(['nama_retribusi'])
            ->make(true);
    }

    public function detail_target_opd($tahun, $id_retribusi)
    {
        // dd($tahun, $id_retribusi);
        $tahun = $tahun;
        $id_retribusi = $id_retribusi;
        return view("admin.pad.detail_target_opd")->with(compact('tahun', 'id_retribusi'));
    }

    public function datatable_detail_target_opd(Request $request)
    {
        $tahun = $request->tahun;
        $retribusi = $request->id_retribusi;
        // dd($request->all());
        $results = DB::table('data.retribusi_opd AS ro')
            ->leftJoin('data.target_opd AS t', 'ro.id', '=', 't.id_retribusi_opd')
            ->leftJoin('data.retribusi AS r', 'ro.id_retribusi', '=', 'r.id')
            ->leftJoin('data.opd AS o', 'ro.id_opd', '=', 'o.id_opd')
            ->leftJoin('data.detail_realisasi AS dr', 't.id', '=', 'dr.target_opd_id')
            ->select('t.tahun', 't.target_murni', 't.target_perubahan', 'r.nama_retribusi', 'o.nama_opd', DB::raw('SUM(dr.realisasi) AS real'), 'o.ket_opd')
            ->where('ro.id_retribusi', $retribusi)
            ->where('t.tahun', $tahun)
            ->groupBy('t.tahun', 't.target_murni', 't.target_perubahan', 'r.nama_retribusi', 'o.nama_opd', 'o.ket_opd')
            ->get();

        // dd($results);
        $arr = array();
        // if($sismiop->count() > 0){
        foreach ($results as $key => $d) {
            $target_perubahan = $d->target_perubahan;
            if ($target_perubahan == 0) {
                $target_perubahan = $d->target_murni;
            } else {
                $target_perubahan = $d->target_perubahan;
            }
            $arr[] =
                array(
                    "nama_retribusi" => $d->nama_retribusi,
                    "nama_opd" => $d->nama_opd . '<button type="button" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></button>',
                    "ket_opd" => $d->ket_opd,
                    "tahun" => $d->tahun,
                    "realisasi" => number_format($d->real),
                    "target_murni" => number_format($d->target_murni),
                    "target_perubahan" => number_format($target_perubahan),
                );
        }
        // }
        // dd($arr);
        return Datatables::of($arr)
            ->rawColumns(['nama_opd'])
            ->make(true);
    }
    public function datatable_detail_target_opd_bulan(Request $request)
    {
        $tahun = $request->tahun;
        $retribusi = $request->id_retribusi;

        $results = DB::table('data.retribusi_opd AS ro')
            ->leftJoin('data.target_opd AS t', 'ro.id', '=', 't.id_retribusi_opd')
            ->leftJoin('data.retribusi AS r', 'ro.id_retribusi', '=', 'r.id')
            ->leftJoin('data.opd AS o', 'ro.id_opd', '=', 'o.id_opd')
            ->leftJoin('data.detail_realisasi AS dr', 't.id', '=', 'dr.target_opd_id')
            ->select('t.tahun', 't.target_murni', 't.target_perubahan', 'r.nama_retribusi', 'o.nama_opd', 'dr.*', 'o.ket_opd')
            ->where('ro.id_retribusi', $retribusi)
            ->where('t.tahun', $tahun)
            ->where('o.nama_opd', '=', $request->nama_opd)
            ->orderByRaw("CASE 
                            WHEN bulan = 'Januari' THEN 1 
                            WHEN bulan = 'Februari' THEN 2 
                            WHEN bulan = 'Maret' THEN 3 
                            WHEN bulan = 'April' THEN 4 
                            WHEN bulan = 'Mei' THEN 5 
                            WHEN bulan = 'Juni' THEN 6 
                            WHEN bulan = 'Juli' THEN 7 
                            WHEN bulan = 'Agustus' THEN 8 
                            WHEN bulan = 'September' THEN 9 
                            WHEN bulan = 'Oktober' THEN 10 
                            WHEN bulan = 'November' THEN 11 
                            WHEN bulan = 'Desember' THEN 12 
                        END")
            ->get();
        // dd($results);
        $arr = array();

        foreach ($results as $key => $d) {
            $target_perubahan = $d->target_perubahan;
            if ($target_perubahan == 0) {
                $target_perubahan = $d->target_murni;
            } else {
                $target_perubahan = $d->target_perubahan;
            }

            $arr[] = array(
                "no" => $key + 1,
                "bulan" => $d->bulan,
                "realisasi" => number_format($d->realisasi),
            );
        }

        // Return DataTables dengan data yang sudah diurutkan
        return Datatables::of($arr)
            ->rawColumns(['pembayaran'])
            ->make(true);
    }


    public function penerimaan_peropd(Request $request){
        $retribusi_opd = $request->retribu_opd;
        $tahun = $request->tahun;

        $session =  Session::get("user_app");
        $id_user = decrypt($session['user_id']);
        // dd($retribusi_opd);

        if(is_null($retribusi_opd) || $retribusi_opd == ""){
            $query = DB::table('data.detail_realisasi as dr')
            ->select(
                'dr.bulan',
                DB::raw('SUM(dr.realisasi) as realisasi'),
                'tro.tahun',
                'dr.id_opd'
            )
            ->leftJoin('data.target_opd as tro', 'tro.id', '=', 'dr.target_opd_id')
            ->leftJoin('data.retribusi_opd as rto', 'tro.id_retribusi_opd', '=', 'rto.id')
            ->leftJoin('data.retribusi as r', 'r.id', '=', 'rto.id_retribusi')
            ->where('dr.id_opd',$id_user)
            ->where('tro.tahun',$tahun)
            ->groupBy('dr.bulan', 'tro.tahun', 'dr.id_opd')
            ->orderBy('dr.bulan')
            ->get();
        }else{
            $query = DB::table('data.detail_realisasi as dr')
            ->select(
                'dr.realisasi',
                'dr.bulan',
                'dr.id_opd',
                'tro.id_retribusi_opd',
                'tro.tahun',
                'r.nama_retribusi'
            )
            ->leftJoin('data.target_opd as tro', 'tro.id', '=', 'dr.target_opd_id')
            ->leftJoin('data.retribusi_opd as rto', 'tro.id_retribusi_opd', '=', 'rto.id')
            ->leftJoin('data.retribusi as r', 'rto.id_retribusi', '=', 'r.id')
            ->where('dr.id_opd',$id_user)
            ->where('tro.tahun',$tahun)
            ->where('tro.id_retribusi_opd',$retribusi_opd)
            ->orderBy('tro.tahun')
            ->get();
        }
        
        $bulanHelper = getMonthList();
        $hasilConvert = [];
        foreach ($bulanHelper as $key => $value) {
            foreach ($query as $keys => $vl) {
                if(strtolower($value) == $vl->bulan){
                    $hasilConvert[] = (object)[
                        "bulan" => $key,
                        "realisasi" => $vl->realisasi,
                        "tahun" => $vl->tahun,
                        "id_opd" => $vl->id_opd
                    ];
                }
            }
        }
        // dd($hasilConvert);

        $valBulan = array();
        if (!empty($hasilConvert)) {
            foreach ($hasilConvert as $key => $value) {
                if (!isset($data['penerimaan'][$value->tahun])) {
                    $data['penerimaan'][$value->tahun] = array();
                }

                if(is_null($value->realisasi)){
                    $value->realisasi = 0;
                }
                array_push($data['penerimaan'][$value->tahun],$value->realisasi);
                array_push($valBulan, $value->bulan);
            }
            $bulanText = array();
            // dd($valBulan);
            foreach ($valBulan as $key => $value) {
                // dd($value);
                if (!in_array(getMonth($value), $bulanText)) {
                    array_push($bulanText, getMonth($value));
                }
            }
            $data['bulan'] = $bulanText;
        } else {
            $data['penerimaan'] = 0;
            $data['bulan'] = 0;
        }
        // dd($data);
        return $data;
    }

    public function datatable_laporan_realisasi_retribusi_daerah(Request $request){

        $tahun = $request->tahun;
        $bulanAngka = (int) $request->bulan;

        $dt_bulan = getMonthList();
        $bulanHuruf = "";
        foreach ($dt_bulan as $key => $value) {
            if($key == $bulanAngka){
                $bulanHuruf = strtolower($value);
            }
        }

        $query = "
        select nama_opd, nama_retribusi,kode_rekening,
        coalesce(target_perubahan,target_murni) as target_apbd,
        sd_bln_lalu, bln_ini, sd_bln_lalu+bln_ini as sd_bln_ini,
        (sd_bln_lalu+bln_ini)/coalesce(target_perubahan,target_murni) as persen 
        from data.retribusi_opd a
        left join data.opd
        on a.id_opd = opd.id_opd
        left join data.retribusi ret
        on a.id_retribusi = ret.id
        left join 
        (select id_retribusi_opd, target_perubahan,target_murni, sum(realisasi) sd_bln_lalu 
        from data.target_opd tgtz
        left join 
        (select *,
        case bulan
        when 'januari' then 1
        when 'februari' then 2
        when 'maret' then 3
        when 'april' then 4
        when 'mei' then 5
        when 'juni' then 6
        when 'juli' then 7
        when 'agustus' then 8
        when 'september' then 9
        when 'oktober' then 10
        when 'november' then 11
        when 'desember' then 12
        end as bln2
        from data.detail_realisasi) dtl
        on dtl.target_opd_id = tgtz.id
        --filter thn-bln(masukkan bln angka)
        where tahun=$tahun and bln2 < $bulanAngka
        group by id_retribusi_opd, target_perubahan,target_murni
        ) q1
        on q1.id_retribusi_opd = a.id
        left join 
        (select id_retribusi_opd id_retribusi_opd2, sum(realisasi) bln_ini 
        from data.target_opd tgtz
        left join data.detail_realisasi dtl
        on dtl.target_opd_id = tgtz.id
        --filter thn-bln(masukkan bln huruf)
        where tahun=$tahun and bulan = '$bulanHuruf'
        group by id_retribusi_opd
        ) q2
        on q2.id_retribusi_opd2 = a.id 
        where coalesce(target_perubahan,target_murni) is not null
        ";


        // dd($query);
        $data = DB::select($query);
        $arr = [];
        $nama_opd_old = "";
        $no_data = 0;
        $no = 0;
        $total_per_opd = [];
        $total_semua = [];
        
        // dd(count($data));
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                // dd($data);
                $no_data +=1;
                $no +=1;
                if($value->nama_opd != $nama_opd_old && $nama_opd_old != ""){
                    // foreach ($total_per_opd as $key => $value) {
                        // $no -=1;
                        $arr[] = array(
                                "no" => "",
                                "nama_opd" => "Jumlah",
                                "nama_retribusi" => "",
                                "kode_rekening" => "",
                                "target_apbd" => number_format($total_per_opd[$nama_opd_old]["target_apbd"]),
                                "sd_bln_lalu" => number_format($total_per_opd[$nama_opd_old]["sd_bln_lalu"]),
                                "bln_ini" => number_format($total_per_opd[$nama_opd_old]["bln_ini"]),
                                "sd_bln_ini" => number_format($total_per_opd[$nama_opd_old]["sd_bln_ini"]),
                                "persen" => round((($total_per_opd[$nama_opd_old]["sd_bln_ini"]/$total_per_opd[$nama_opd_old]["target_apbd"]) * 100),2)
                            );
                        
                    // }               
                    
                }

                $nama_opd = $value->nama_opd ?? $value['nama_opd'];
               if (!isset($total_per_opd[$nama_opd])) {
                    $total_per_opd[$value->nama_opd] =[
                        'nama_opd' => $value->nama_opd,
                        'target_apbd' => (int)$value->target_apbd,
                        'sd_bln_lalu' => (int)$value->sd_bln_lalu,
                        'bln_ini' => (int)$value->bln_ini,
                        'sd_bln_ini' => (int)$value->sd_bln_ini
                    ];
                }else{
                    $total_per_opd[$nama_opd]["target_apbd"] += (int)$value->target_apbd;
                    $total_per_opd[$nama_opd]['sd_bln_lalu'] += (int)$value->sd_bln_lalu;
                    $total_per_opd[$nama_opd]['bln_ini'] += (int)$value->bln_ini;
                    $total_per_opd[$nama_opd]['sd_bln_ini'] += (int)$value->sd_bln_ini;
                }

                if (!isset($total_semua["total_semua"])) {
                    $total_semua["total_semua"] =[
                        'target_apbd' => (int)$value->target_apbd,
                        'sd_bln_lalu' => (int)$value->sd_bln_lalu,
                        'bln_ini' => (int)$value->bln_ini,
                        'sd_bln_ini' => (int)$value->sd_bln_ini
                    ];
                    // dd($total_semua);
                }else{
                    $total_semua["total_semua"]["target_apbd"] += (int)$value->target_apbd;
                    $total_semua["total_semua"]['sd_bln_lalu'] += (int)$value->sd_bln_lalu;
                    $total_semua["total_semua"]['bln_ini'] += (int)$value->bln_ini;
                    $total_semua["total_semua"]['sd_bln_ini'] += (int)$value->sd_bln_ini;
                }
                $arr[] = array(
                    "no" => $no,
                    "nama_opd" => $value->nama_opd,
                    "nama_retribusi" => $value->nama_retribusi,
                    "kode_rekening" => $value->kode_rekening,
                    "target_apbd" => number_format((int)$value->target_apbd),
                    "sd_bln_lalu" => number_format((int)$value->sd_bln_lalu),
                    "bln_ini" => number_format((int)$value->bln_ini),
                    "sd_bln_ini" => number_format((int)$value->sd_bln_ini),
                    "persen" => round($value->persen,2)
                );

                if(count($data) == $no_data){
                    $arr[] = array(
                        "no" => "",
                        "nama_opd" => "Jumlah",
                        "nama_retribusi" => "",
                        "kode_rekening" => "",
                        "target_apbd" => number_format($total_per_opd[$nama_opd_old]["target_apbd"]),
                        "sd_bln_lalu" => number_format($total_per_opd[$nama_opd_old]["sd_bln_lalu"]),
                        "bln_ini" => number_format($total_per_opd[$nama_opd_old]["bln_ini"]),
                        "sd_bln_ini" => number_format($total_per_opd[$nama_opd_old]["sd_bln_ini"]),
                        "persen" => round((($total_per_opd[$nama_opd_old]["sd_bln_ini"]/$total_per_opd[$nama_opd_old]["target_apbd"]) * 100),2)
                    );
                    // dd($total_semua);
                    $arr[] = array(
                        "no" => "",
                        "nama_opd" => "Jumlah Total",
                        "nama_retribusi" => "",
                        "kode_rekening" => "",
                        "target_apbd" => number_format($total_semua["total_semua"]["target_apbd"]),
                        "sd_bln_lalu" => number_format($total_semua["total_semua"]["sd_bln_lalu"]),
                        "bln_ini" => number_format($total_semua["total_semua"]["bln_ini"]),
                        "sd_bln_ini" => number_format($total_semua["total_semua"]["sd_bln_ini"]),
                        "persen" => round((($total_semua["total_semua"]["sd_bln_ini"]/$total_semua["total_semua"]["target_apbd"]) * 100),2)
                    );
                }
                $nama_opd_old = $value->nama_opd;
            }
        }
        return Datatables::of($arr)
            // ->addIndexColumn()
            ->make(true);
    }

    public function get_akumulasi_pajak_pad(Request $request){
        $query = DB::table("data.target_realisasi_pajak")
        ->where('tahun', $request->tahun)
        ->selectRaw('SUM(target) as target, SUM(realisasi) as realisasi')
        ->first();

        // dd($query);
        if ($query) {
            $target = $query->target;
            $realisasi = $query->realisasi;
            $selisih = $realisasi - $target;
        } else {
            $target = 0;
            $realisasi = 0;
            $selisih = 0;
        }
        if ($realisasi != 0) {
            $persen = round($realisasi / $target * 100, 2);
        } else {
            $persen = 0;
        }

        $nominal['target'] = (int)$target;
        $nominal['realisasi'] = (int)$realisasi;
        $nominal['selisih'] = (int)$selisih;
        $nominal['persen'] = $persen;

        return response()->json($nominal);
    }

}
