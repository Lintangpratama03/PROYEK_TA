<?php

namespace App\Http\Controllers\PBB;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class PenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $akses = get_url_akses();
        if ($akses) {
            return redirect()->route("pad.index");
        } else {
            return view("admin.pbb.penerimaan");
        }
    }
    public function get_wilayah(Request $request)
    {
        $wilayah = $request->wilayah;
        $value = $request->data;
        if ($wilayah == 'uptd') {
            $data = DB::table("master.master_wilayah")
                ->selectRaw("distinct(nama_kecamatan) as nama_kecamatan")
                ->get();
        } else {
            $data = DB::table("master.master_wilayah")
                ->selectRaw("distinct(nama_kelurahan) as kelurahan")
                ->where("nama_kecamatan", $value)
                ->get();
        }
        return response()->json($data);
    }
    public function penerimaan_perbulan(Request $request)
    {
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
        $data = $this->get_penerimaan_perbulan($tahun, $bulan, $kecamatan, $kelurahan);
        return $data;
    }

    public function get_penerimaan_perbulan($tahun, $bulan, $kecamatan, $kelurahan)
    {
        // dd($bulan);
        if (is_null($kecamatan) && is_null($kelurahan)) {

            $query = DB::table("data.penerimaan_bulanan as pb")
                ->where('pb.nama_rekening', 'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)')
                ->selectRaw("pb.tahun,
                pb.bulan,
                pb.nama_rekening,
                pb.kode_rekening,
                sum(pb.penerimaan_per_bulan) penerimaan_per_bulan,	
                sum(pb.jumlah_transaksi_per_bulan) jumlah_transaksi_per_bulan")
                ->whereIn('pb.tahun', $tahun)
                ->whereIn('pb.bulan', $bulan)
                ->whereIn('pb.kecamatan', $this->get_kec_madiun())
                ->groupby('pb.tahun')
                ->groupby('pb.bulan')
                ->groupby('pb.nama_rekening')
                ->groupby('pb.kode_rekening')
                ->orderBy('pb.bulan', 'ASC')->get();
        } elseif (!is_null($kecamatan) && is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->where('nama_rekening', 'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)')
                ->selectRaw("tahun,
                bulan,
                nama_rekening,
                kode_rekening,
                sum(penerimaan_per_bulan) penerimaan_per_bulan,	
                sum(jumlah_transaksi_per_bulan) jumlah_transaksi_per_bulan")
                ->whereIn('tahun', $tahun)
                ->whereIn('bulan', $bulan)
                ->where('kecamatan', $kecamatan)
                ->groupby('tahun')
                ->groupby('bulan')
                ->groupby('nama_rekening')
                ->groupby('kode_rekening')
                ->orderBy('bulan', 'ASC')->get();
        } elseif (!is_null($kecamatan) && !is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->where('nama_rekening', 'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)')
                ->selectRaw("tahun,
                bulan,
                nama_rekening,
                kode_rekening,
                sum(penerimaan_per_bulan) penerimaan_per_bulan,	
                sum(jumlah_transaksi_per_bulan) jumlah_transaksi_per_bulan")
                ->whereIn('tahun', $tahun)->whereIn('bulan', $bulan)
                ->where('kecamatan', $kecamatan)
                ->where('kelurahan', $kelurahan)
                ->groupby('tahun')
                ->groupby('bulan')
                ->groupby('nama_rekening')
                ->groupby('kode_rekening')
                ->orderBy('bulan', 'ASC')->get();
        }
        $valBulan = array();
        if ($query->isNotEmpty()) {
            foreach ($query as $key => $value) {
                if (!isset($data['penerimaan'][$value->tahun])) {
                    $data['penerimaan'][$value->tahun] = array();
                }

                array_push($data['penerimaan'][$value->tahun], $value->penerimaan_per_bulan);
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
            $data['penerimaan'] = 0;
            $data['penerimaan'] = 0;
            $data['penerimaan'] = 0;
            $data['bulan'] = 0;
        }
        // dd($data['tahun']);
        return $data;
    }

    public function penerimaan_akumulasi(Request $request)
    {
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

        $data = $this->get_penerimaan_akumulasi($tahun, $bulan, $kecamatan, $kelurahan);
        return $data;
    }

    public function get_penerimaan_akumulasi($tahun, $bulan, $kecamatan, $kelurahan)
    {
        if (is_null($kecamatan) && is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_bulanan as pb")
                ->where('pb.nama_rekening', 'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)')
                ->selectRaw("pb.tahun,
                pb.bulan,
                pb.nama_rekening,
                pb.kode_rekening,
                sum(pb.penerimaan_per_bulan) penerimaan_per_bulan,	
                sum(pb.penerimaan_akumulasi) penerimaan_akumulasi")
                ->whereIn('pb.tahun', $tahun)
                ->whereIn('pb.bulan', $bulan)
                ->whereIn('pb.kecamatan', $this->get_kec_madiun())
                ->groupby('pb.tahun')
                ->groupby('pb.bulan')
                ->groupby('pb.nama_rekening')
                ->groupby('pb.kode_rekening')
                ->orderBy('pb.bulan', 'ASC')->get();
        } elseif (!is_null($kecamatan) && is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->where('nama_rekening', 'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)')
                ->selectRaw("tahun,
                bulan,
                nama_rekening,
                kode_rekening,
                sum(penerimaan_per_bulan) penerimaan_per_bulan,	
                sum(penerimaan_akumulasi) penerimaan_akumulasi")
                ->whereIn('tahun', $tahun)
                ->whereIn('bulan', $bulan)
                ->where('kecamatan', $kecamatan)
                ->groupby('tahun')
                ->groupby('bulan')
                ->groupby('nama_rekening')
                ->groupby('kode_rekening')
                ->orderBy('bulan', 'ASC')->get();
        } elseif (!is_null($kecamatan) && !is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->where('nama_rekening', 'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)')
                ->selectRaw("tahun,
                bulan,
                nama_rekening,
                kode_rekening,
                sum(penerimaan_per_bulan) penerimaan_per_bulan,	
                sum(penerimaan_akumulasi) penerimaan_akumulasi")
                ->whereIn('tahun', $tahun)
                ->whereIn('bulan', $bulan)
                ->where('kecamatan', $kecamatan)
                ->where('kelurahan', $kelurahan)
                ->groupby('tahun')
                ->groupby('bulan')
                ->groupby('nama_rekening')
                ->groupby('kode_rekening')
                ->orderBy('bulan', 'ASC')->get();
        }

        $valBulan = array();
        if ($query->isNotEmpty()) {
            foreach ($query as $key => $value) {
                if (!isset($data['penerimaan'][$value->tahun])) {
                    $data['penerimaan'][$value->tahun] = array();
                }

                array_push($data['penerimaan'][$value->tahun], $value->penerimaan_akumulasi);
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
            $data['penerimaan'] = 0;
            $data['penerimaan'] = 0;
            $data['penerimaan'] = 0;
            $data['bulan'] = 0;
        }
        return $data;
    }

    function datatable_penerimaan_tahun(Request $request)
    {
        $tahun = $request->tahun;
        $select = ' SUM(nominal_terima) AS nominal, SUM(nop) AS nop, tahun_sppt';


        if (!is_null($tahun)) {
            $d_data = DB::table("data.penerimaan_tahun_sppt")
                // ->where('tahun_sppt','=', $tahun)
                ->where('tahun_bayar', '=', $tahun)
                ->groupBy('tahun_sppt')
                ->orderBy('tahun_sppt', 'DESC')
                ->select(DB::raw($select))->get();
            // dd($d_data);
            // 
        } else {
            // dd("masuk2");
            $d_data = DB::table("data.penerimaan_tahun_sppt")
                ->groupBy('tahun_sppt')
                ->orderBy('tahun_sppt', 'DESC')
                ->select(DB::raw($select))->get();
        }
        $arr = array();
        if ($d_data->count() > 0) {
            foreach ($d_data as $key => $d) {
                $route = url('pbb/penerimaan/detail_penerimaan_tahun_kecamatan') . "/" . $d->tahun_sppt . "/" . $tahun;
                $detail_nop = "<a target='_BLANK' href='" . $route . "' ><u>" . number_format($d->nop) . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $arr[] =
                    array(
                        "tahun" => $d->tahun_sppt,
                        "nominal" => rupiahFormat($d->nominal),
                        "nop" => $detail_nop
                    );
            }
        }

        return Datatables::of($arr)
            ->rawColumns(['nop'])
            ->make(true);
    }

    public function detail_penerimaan_tahun_kecamatan($tahun_sppt, $tahun_bayar)
    {
        $tahun_sppt = $tahun_sppt;
        $tahun_bayar = $tahun_bayar;
        return view("admin.pbb.detail_penerimaan_tahun_kecamatan")->with(compact('tahun_sppt', 'tahun_bayar'));
    }

    public function datatable_penerimaan_tahun_kecamatan(Request $request)
    {
        $tahun_sppt = $request->tahun_sppt;
        $tahun_bayar = $request->tahun_bayar;

        $query_sismiop = "
            select x.*, nm_kecamatan,nm_kelurahan from (
            SELECT KD_KECAMATAN,KD_KELURAHAN,COUNT(KD_KECAMATAN) jumlah FROM PEMBAYARAN_SPPT
            where EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT)=" . $tahun_bayar . " and cast(REGEXP_REPLACE(THN_PAJAK_SPPT, '[^0-9]+', '') as int) = " . $tahun_sppt . "
            group by KD_KECAMATAN,KD_KELURAHAN
            ) x
            left join REF_KECAMATAN kec on x.KD_KECAMATAN = kec.KD_KECAMATAN
            left join REF_KELURAHAN kel on  x.KD_KECAMATAN = kel.KD_KECAMATAN and x.KD_KELURAHAN = kel.KD_KELURAHAN
        ";

        $sismiop = DB::connection("oracle")->select($query_sismiop);

        $arr = array();
        // if($sismiop->count() > 0){
        foreach ($sismiop as $key => $d) {
            $route = url('pbb/penerimaan/detail_penerimaan_tahun_wp') . "/" . $tahun_sppt . "/" . $tahun_bayar . "/" . $d->kd_kecamatan . "/" . $d->kd_kelurahan;
            $detail = "<a target='_BLANK' href='" . $route . "' ><u>" . number_format($d->jumlah) . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";
            $arr[] =
                array(
                    "kecamatan" => $d->nm_kecamatan,
                    "kelurahan" => $d->nm_kelurahan,
                    "pembayaran" => $detail
                );
        }
        // }
        return Datatables::of($arr)
            ->rawColumns(['pembayaran'])
            ->make(true);
    }

    public function detail_penerimaan_tahun_wp($tahun_sppt, $tahun_bayar, $kecamatan, $kelurahan)
    {
        // dd($tahun, $wilayah, $nama_wilayah);
        $tahun_sppt = $tahun_sppt;
        $tahun_bayar = $tahun_bayar;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pbb.detail_penerimaan_tahun_wp")->with(compact('kecamatan', 'kelurahan', 'tahun_sppt', 'tahun_bayar'));
    }

    public function datatable_penerimaan_tahun_wp(Request $request)
    {
        $tahun_sppt = $request->tahun_sppt;
        $tahun_bayar = $request->tahun_bayar;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;

        $query_sismiop = "
        select 
        nop,
        nama_subjek_pajak,
        alamat_subjek_pajak,
        alamat_objek_pajak,
        NM_KECAMATAN as kecamatan,
        NM_KELURAHAN as kelurahan,
        nominal
        from (
        SELECT 
                KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
                        KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop,     
                KD_KECAMATAN,
                KD_KELURAHAN,
                JML_SPPT_YG_DIBAYAR as nominal,
                        cast(REGEXP_REPLACE(THN_PAJAK_SPPT, '[^0-9]+', '') as int) as thn_pajak
        FROM PEMBAYARAN_SPPT
        WHERE EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT)=" . $tahun_bayar . " and cast(REGEXP_REPLACE(THN_PAJAK_SPPT, '[^0-9]+', '') as int) = " . $tahun_sppt . "
        AND cast(KD_KECAMATAN as int) = " . $kecamatan . " and cast(KD_KELURAHAN as int) = " . $kelurahan . "
        ) x
        left join
        (SELECT KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
                        KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop2, JALAN_OP as alamat_objek_pajak
        FROM DAT_OBJEK_PAJAK
        ) y
        on x.nop = y.nop2
        left join REF_KECAMATAN kec on x.KD_KECAMATAN = kec.KD_KECAMATAN
        left join REF_KELURAHAN kel on  x.KD_KECAMATAN = kel.KD_KECAMATAN and x.KD_KELURAHAN = kel.KD_KELURAHAN
        left join 
        (select KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
                        KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop3,
                        NM_WP_SPPT as nama_subjek_pajak,
                JLN_WP_SPPT as alamat_subjek_pajak,
                        THN_PAJAK_SPPT
        from SPPT
        where THN_PAJAK_SPPT=" . $tahun_sppt . "
        ) z
        on x.nop = z.nop3 and z.THN_PAJAK_SPPT=x.thn_pajak
        ";

        $sismiop = DB::connection("oracle")->select($query_sismiop);

        $arr = array();
        // if($sismiop->count() > 0){
        foreach ($sismiop as $key => $d) {
            $arr[] =
                array(
                    "nop" => $d->nop,
                    "nama_wp" => $d->nama_subjek_pajak,
                    "alamat_wp" => $d->alamat_subjek_pajak,
                    "alamat_op" => $d->alamat_objek_pajak,
                    "kecamatan" => $d->kecamatan,
                    "kelurahan" => $d->kelurahan,
                    "pembayaran" => rupiahFormat($d->nominal)
                );
        }
        // }
        return Datatables::of($arr)->make(true);
    }

    public function penerimaan_harian(Request $request)
    {
        $tanggal = $request->tanggal;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;
        $rangeDate = explode(" - ", $tanggal);
        $startDate = Carbon::parse($rangeDate[0])->format('Y-m-d');
        $endDate = Carbon::parse($rangeDate[1])->format('Y-m-d');
        $rekening = 'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)';
        // dd($startDate);
        // data nya kosong karena nama rekening nya kosong
        if (is_null($kecamatan) && is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_harian as pb")
                // ->where('pb.nama_rekening', $rekening)
                ->selectRaw("
                pb.tanggal,
                sum(pb.penerimaan) penerimaan_harian")
                ->whereBetween('pb.tanggal', [$startDate, $endDate])
                ->groupby('pb.tanggal')
                ->orderBy('pb.tanggal', 'ASC')->get();
        } elseif (!is_null($kecamatan) && is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_harian")
                // ->where('nama_rekening', $rekening)
                ->selectRaw("
                tanggal,
                sum(penerimaan) penerimaan_harian")
                ->where('kecamatan', $kecamatan)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->groupby('tanggal')
                ->orderBy('tanggal', 'ASC')->get();
        } elseif (!is_null($kecamatan) && !is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_harian")
                // ->where('nama_rekening', $rekening)
                ->selectRaw("
                tanggal,
                sum(penerimaan) penerimaan_harian")
                ->where('kecamatan', $kecamatan)
                ->where('kelurahan', $kelurahan)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->groupby('tanggal')
                ->orderBy('tanggal', 'ASC')->get();
        }
        // dd($query);
        if ($query->isNotEmpty()) {
            foreach ($query as $key => $value) {
                $data['tanggal'][] = $value->tanggal;
                $data['penerimaan'][] = $value->penerimaan_harian;
            }
        } else {
            $data['tanggal'][] = 0;
            $data['penerimaan'][] = 0;
        }
        return $data;
    }

    public function detail_penerimaan_perbulan($tahun, $bulan, $kecamatan = null, $kelurahan = null)
    {
        $tahun = $tahun;
        $bulan = $bulan;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pbb.detail_penerimaan_perbulan")->with(compact('tahun', 'bulan', 'kecamatan', 'kelurahan'));
    }

    public function datatable_detail_penerimaan_perbulan(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;


        if (!is_null($kelurahan)) {
            $kelurahan = DB::table('master.master_wilayah')->select('kd_kel')->where('nama_kelurahan', $kelurahan)->first()->kd_kel;
        }

        if (!is_null($kecamatan)) {
            $kecamatan = DB::table('master.master_wilayah')->select('kd_kec')->where('nama_kecamatan', $kecamatan)->first()->kd_kec;
        }

        $arrbul = getMonthList();
        foreach ($arrbul as $key => $value) {
            // dd($bulan);
            if ($bulan === $value) {
                $valBulan = $key;
                // dd($valBulan);
            }
        }
        // dd($valBulan);
        if (!is_null($kecamatan) && is_null($kelurahan)) {
            $whre_condition = "WHERE KD_KECAMATAN = " . $kecamatan . " and extract(month from tgl_pembayaran_sppt) =" . $valBulan . " and extract(year from tgl_pembayaran_sppt) = " . $tahun;
        } elseif (!is_null($kecamatan) && !is_null($kelurahan)) {
            $whre_condition = "WHERE KD_KECAMATAN = " . $kecamatan . " and KD_KELURAHAN = " . $kelurahan . " and extract(month from tgl_pembayaran_sppt) = " . $valBulan . " and extract(year from tgl_pembayaran_sppt) = " . $tahun;
        } else {
            $whre_condition = "WHERE extract(month from tgl_pembayaran_sppt) = " . $valBulan . " and extract(year from tgl_pembayaran_sppt) = " . $tahun;
        }
        // dd($whre_condition);
        $select = "SELECT
        nop,
        tgl_pembayaran_sppt,
        tahun_sppt,
        nama_subjek_pajak,
        alamat_subjek_pajak,
        alamat_objek_pajak,
        NM_KECAMATAN AS kecamatan,
        NM_KELURAHAN AS kelurahan,
        nominal 
FROM
        (
        SELECT
                KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP AS nop,
                THN_PAJAK_SPPT AS tahun_sppt,
                kd_kecamatan,
                kd_kelurahan,
                tgl_pembayaran_sppt,
                JML_SPPT_YG_DIBAYAR - DENDA_SPPT AS nominal 
        FROM
                pembayaran_sppt 
                -- filter thn,bln,kec,kel yg dipilih
                -- WHERE 
                -- extract( year FROM TGL_PEMBAYARAN_SPPT ) = 2024 
                -- AND extract( month FROM TGL_PEMBAYARAN_SPPT ) = 1 
                -- AND kd_kecamatan = '080' 
                -- AND kd_kelurahan = '003' 
                " . $whre_condition . "
        ) x
        LEFT JOIN (
        SELECT
                KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP AS nop2,
                THN_PAJAK_SPPT,
                NM_WP_SPPT AS nama_subjek_pajak,
                JLN_WP_SPPT AS alamat_subjek_pajak 
        FROM
                SPPT 
        ) y ON x.nop = y.nop2 
        AND x.tahun_sppt = y.THN_PAJAK_SPPT
        LEFT JOIN (
        SELECT
                KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP AS nop3,
                JALAN_OP AS alamat_objek_pajak 
        FROM
                DAT_OBJEK_PAJAK 
        ) z ON x.nop = z.nop3
        LEFT JOIN REF_KECAMATAN kec ON x.KD_KECAMATAN = kec.KD_KECAMATAN
        LEFT JOIN REF_KELURAHAN kel ON x.KD_KECAMATAN = kel.KD_KECAMATAN 
        AND x.KD_KELURAHAN = kel.KD_KELURAHAN
               ";

        $sismiop = DB::connection("oracle")->select($select);
        // dd($sismiop);

        // dd($sismiop);
        $arr = array();
        // if($sismiop->count() > 0){
        foreach ($sismiop as $key => $d) {
            // dd($key);
            $arr[] =
                array(
                    "no" => $key + 1,
                    "nop" => $d->nop,
                    "nominal" => rupiahFormat($d->nominal),
                    "tgl_pembayaran_sppt" => $d->tgl_pembayaran_sppt,
                    "tahun_sppt" => $d->tahun_sppt,
                    "nama_subjek_pajak" => $d->nama_subjek_pajak,
                    "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                    "alamat_objek_pajak" => $d->alamat_objek_pajak,
                    "kecamatan" => $d->kecamatan,
                    "kelurahan" => $d->kelurahan
                );
        }
        // }

        return Datatables::of($arr)
            ->make(true);
    }

    public function detail_penerimaan_harian($tanggal, $kecamatan = null, $kelurahan = null)
    {
        // dd($kelurahan, $tanggal);
        $tanggal = $tanggal;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pbb.detail_penerimaan_harian")->with(compact('tanggal', 'kecamatan', 'kelurahan'));
    }

    public function datatable_detail_penerimaan_harian(Request $request)
    {
        $tanggal = $request->tanggal;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;


        // dd($kelurahan, $tanggal);

        $whre_condition = "";
        if (!is_null($kecamatan) && is_null($kelurahan)) {
            $whre_condition = "WHERE NM_KECAMATAN = '" . $kecamatan . "'  and tgl_pembayaran_sppt = TO_DATE('" . $tanggal . "', 'YYYY-MM-DD')";
        } elseif (!is_null($kecamatan) && !is_null($kelurahan)) {
            $whre_condition = "WHERE tgl_pembayaran_sppt = TO_DATE('" . $tanggal . "', 'YYYY-MM-DD') and NM_KECAMATAN = '" . $kecamatan . "' and NM_KELURAHAN = '" . $kelurahan . "'";
        } else {
            $whre_condition = "WHERE tgl_pembayaran_sppt = TO_DATE('" . $tanggal . "', 'YYYY-MM-DD')";
        }

        // dd($whre_condition);

        $select = "SELECT
                nop,
                tgl_pembayaran_sppt,
                tahun_sppt,
                nama_subjek_pajak,
                alamat_subjek_pajak,
                alamat_objek_pajak,
                NM_KECAMATAN AS kecamatan,
                NM_KELURAHAN AS kelurahan,
                DENDA_SPPT,
                JML_SPPT_YG_DIBAYAR,
                nominal
        FROM
                (
                SELECT
                        KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP AS nop,
                        THN_PAJAK_SPPT AS tahun_sppt,
                        kd_kecamatan,
                        kd_kelurahan,
                        tgl_pembayaran_sppt,
                        JML_SPPT_YG_DIBAYAR,
                        DENDA_SPPT,
                        JML_SPPT_YG_DIBAYAR - DENDA_SPPT AS nominal 
                FROM
                        pembayaran_sppt
                ) x
                LEFT JOIN (
                SELECT
                        KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP AS nop2,
                        THN_PAJAK_SPPT,
                        NM_WP_SPPT AS nama_subjek_pajak,
                        JLN_WP_SPPT AS alamat_subjek_pajak 
                FROM
                        SPPT
                ) y ON x.nop = y.nop2 
                AND x.tahun_sppt = y.THN_PAJAK_SPPT
                LEFT JOIN (
                SELECT
                        KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP AS nop3,
                        JALAN_OP AS alamat_objek_pajak 
                FROM
                        DAT_OBJEK_PAJAK 
                ) z ON x.nop = z.nop3
                LEFT JOIN REF_KECAMATAN kec ON x.KD_KECAMATAN = kec.KD_KECAMATAN
                LEFT JOIN REF_KELURAHAN kel ON x.KD_KECAMATAN = kel.KD_KECAMATAN 
                AND x.KD_KELURAHAN = kel.KD_KELURAHAN " . $whre_condition;


        // $select = "SELECT * FROM pembayaran_sppt limit 5";

        // dd($select);
        $sismiop = DB::connection("oracle")->select($select);
        // dd($sismiop);
        $arr = array();

        // dd($sismiop->count());
        // if($sismiop->count() > 0){
        foreach ($sismiop as $key => $d) {
            // dd($key);
            $arr[] =
                array(
                    "no" => $key + 1,
                    "nop" => $d->nop,
                    "tgl_pembayaran_sppt" => $d->tgl_pembayaran_sppt,
                    "tahun_sppt" => $d->tahun_sppt,
                    "nama_subjek_pajak" => $d->nama_subjek_pajak,
                    "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                    "alamat_objek_pajak" => $d->alamat_objek_pajak,
                    "kecamatan" => $d->kecamatan,
                    "kelurahan" => $d->kelurahan,
                    "nominal" => rupiahFormat($d->nominal),
                    "denda" => rupiahFormat($d->denda_sppt),
                    "total" => rupiahFormat($d->jml_sppt_yg_dibayar)
                );
        }
        // }

        return Datatables::of($arr)
            ->make(true);
    }

    public function get_kec_madiun()
    {
        $data = DB::table("master.master_wilayah")
            ->selectRaw("distinct(nama_kecamatan) as nama_kecamatan")
            ->get();
        $kec_madiun = [];
        foreach ($data as $key => $value) {
            $kec_madiun[] = [$value->nama_kecamatan];
        }
        return $kec_madiun;
    }
    function datatable_rekap_penerimaan(Request $request)
    {
        $tahun = $request->input('tahun', []);
        if (!is_array($tahun)) {
            $tahun = [$tahun];
        }

        // dd($kec_madiun);
        $d_data = DB::table("data.v_rekap_pbb_kecamatan")
            ->whereIn('tahun_sppt', $tahun)
            // ->whereIn('kecamatan', $this->get_kec_madiun())
            ->orderBy('kecamatan', 'ASC')
            ->get();
        // dd($d_data);
        $arr = array();
        if ($d_data->count() > 0) {
            $no = 1;
            foreach ($d_data as $key => $d) {
                $arr[] =
                    array(
                        "no" => $no,
                        "tahun" => $d->tahun_sppt,
                        "kecamatan" => $d->kecamatan,
                        "ketetapan_sppt" => number_format($d->ketetapan_sppt),
                        "ketetapan_target" => rupiahFormat($d->ketetapan_target),
                        "realisasi_stts" => number_format($d->realisasi_stts),
                        "realisasi_setoran" => rupiahFormat($d->realisasi_setoran),
                        "realisasi_persen" => $d->realisasi_persen . " %",
                        "sisa_sppt" => number_format($d->sisa_sppt),
                        "sisa_target" => rupiahFormat($d->sisa_target),
                        "sisa_persen" => $d->sisa_persen . " %"
                    );
                $no++;
            }
        }
        return Datatables::of($arr)
            // ->rawColumns(['nop'])
            ->make(true);
    }

    public function show_qty_penerimaan(Request $request)
    {

        $tahun = $request->input('tahun', []);
        if (!is_array($tahun)) {
            $tahun = [$tahun];
        }
        $d_data = DB::table("data.v_rekap_pbb_kecamatan")
            ->whereIn('tahun_sppt', $tahun)
            // ->whereIn('kecamatan', $this->get_kec_madiun())
            ->orderBy('kecamatan', 'ASC')->get();
        // dd($d_data);
        if ($d_data->isEmpty()) {
            return response()->json([
                "qty_ketetapan_sppt" => 0,
                "qty_ketetapan_target" => 0,
                "qty_realisasi_stts" => 0,
                "qty_realisasi_setoran" => 0,
                "qty_realisasi_persen" => 0,
                "qty_sisa_sppt" => 0,
                "qty_sisa_target" => 0,
                "qty_sisa_persen" => 0
            ]);
        }
        $qty_ketetapan_sppt = 0;
        $qty_ketetapan_target = 0;
        $qty_realisasi_setoran = 0;
        $qty_realisasi_stts = 0;
        $qty_realisasi_persen = 0;
        $qty_sisa_sppt = 0;
        $qty_sisa_target = 0;
        $qty_sisa_persen = 0;

        foreach ($d_data as $key => $value) {
            $qty_ketetapan_sppt +=  $value->ketetapan_sppt;
            $qty_ketetapan_target +=  $value->ketetapan_target;
            $qty_realisasi_setoran +=  $value->realisasi_setoran;
            $qty_realisasi_stts +=  $value->realisasi_stts;
            $qty_realisasi_persen +=  $value->realisasi_persen;
            $qty_sisa_sppt +=  $value->sisa_sppt;
            $qty_sisa_target +=  $value->sisa_target;
            $qty_sisa_persen +=  $value->sisa_persen;
        }

        $qty_realisasi_persen_hitung = ($qty_realisasi_setoran / $qty_ketetapan_target) * 100;
        $qty_sisa_persen_hitung = (1 - ($qty_realisasi_setoran / $qty_ketetapan_target)) * 100;

        return response()->json([
            "qty_ketetapan_sppt" => number_format($qty_ketetapan_sppt),
            "qty_ketetapan_target" => rupiahFormat($qty_ketetapan_target),
            "qty_realisasi_stts" => number_format($qty_realisasi_stts),
            "qty_realisasi_setoran" => rupiahFormat($qty_realisasi_setoran),
            "qty_realisasi_persen" => round($qty_realisasi_persen_hitung, 2) . " %",
            "qty_sisa_sppt" => number_format($qty_sisa_sppt),
            "qty_sisa_target" => rupiahFormat($qty_sisa_target),
            "qty_sisa_persen" => round($qty_sisa_persen_hitung, 2) . " %"
        ]);
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
