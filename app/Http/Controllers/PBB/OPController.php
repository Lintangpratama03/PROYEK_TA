<?php

namespace App\Http\Controllers\PBB;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

class OPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $akses = get_url_akses();
        if($akses){
           return redirect()->route("pad.index");
        }else{
            return view("admin.pbb.op");
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

    public function get_kec_madiun(){
        $data = DB::table("master.master_wilayah")
        ->selectRaw("distinct(nama_kecamatan) as nama_kecamatan")
        ->get();
        $kec_madiun = [];
        foreach ($data as $key => $value) {
            $kec_madiun[] = [$value->nama_kecamatan];
        }
        return $kec_madiun;
    }

    public function datatable_kepatuhan_wp(Request $request)
    {
        $tahun = $request->tahun ?? null;
        // Tambahkan default null jika tidak dipilih
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;
        $query = DB::table("data.kepatuhan_objek as ko");
        // $query->whereIn("kecamatan", $this->get_kec_madiun());
        if (!is_null($kecamatan)) {
            $query->where("kecamatan", $kecamatan);
        }
        if (!is_null($kelurahan)) {
            $query->where("kelurahan", $kelurahan);
        }
        if (is_null($tahun)) {
            $query->selectRaw("tahun, 
                       SUM(nop_baku) as total_nop_baku, 
                       SUM(nop_bayar) as total_nop_bayar");
        } else {
            $query->where('tahun', $tahun)
                ->selectRaw("tahun, 
                       SUM(nop_baku) as total_nop_baku, 
                       SUM(nop_bayar) as total_nop_bayar");
        }
        $query->groupBy("tahun");
        $query->orderBy('tahun', 'DESC');
        $result = $query->get();
        $arr = array();
        if ($result->count() > 0) {
            foreach ($result as $key => $data) {
                $persen = number_format(($data->total_nop_bayar / $data->total_nop_baku) * 100, 2) . "%";
                $arr[] = array(
                    "tahun" => $data->tahun,
                    "nop_baku" => $data->total_nop_baku,
                    "nop_bayar" => $data->total_nop_bayar,
                    "persen" => $persen
                );
            }
        }

        return Datatables::of($arr)
            ->rawColumns(['tahun'])
            ->make(true);
    }



    public function datatable_pembayaran_awal(Request $request)
    {
        $tahun = $request->tahun ?? date('y');
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;


        // catatan

        $whre_condition = "";
        $kec_madiun = $this->get_kec_madiun();
        // dd(array_column($kec_madiun, 0));
        $whre_condition = "WHERE NM_KECAMATAN IN ('" . implode("', '", array_column($kec_madiun, 0)) . "')";


        if (!is_null($kecamatan) && is_null($kelurahan)) {
            $whre_condition .= " AND NM_KECAMATAN = '" . $kecamatan . "'";
        } elseif (!is_null($kecamatan) && !is_null($kelurahan)) {
            $whre_condition .= " AND NM_KELURAHAN = '" . $kelurahan . "'";
        }

        $query = "
        SELECT * FROM (
            SELECT
                x.thn_pajak_sppt,
                x.tgl_pembayaran_sppt,
                x.nop,
                (SELECT DISTINCT NM_WP_SPPT FROM SPPT WHERE THN_PAJAK_SPPT = '" . $tahun . "' AND SPPT.KD_PROPINSI = x.x1 AND SPPT.KD_DATI2 = x.x2 AND SPPT.KD_KECAMATAN = x.x3 AND SPPT.KD_KELURAHAN = x.x4 AND SPPT.KD_BLOK = x.x5 AND SPPT.NO_URUT = x.x6 AND SPPT.KD_JNS_OP = x.x7) AS nm_wp,
                (SELECT DISTINCT JLN_WP_SPPT FROM SPPT WHERE THN_PAJAK_SPPT = '" . $tahun . "' AND SPPT.KD_PROPINSI = x.x1 AND SPPT.KD_DATI2 = x.x2 AND SPPT.KD_KECAMATAN = x.x3 AND SPPT.KD_KELURAHAN = x.x4 AND SPPT.KD_BLOK = x.x5 AND SPPT.NO_URUT = x.x6 AND SPPT.KD_JNS_OP = x.x7) AS alamat_wp,
                (SELECT DISTINCT JALAN_OP FROM DAT_OBJEK_PAJAK WHERE DAT_OBJEK_PAJAK.KD_PROPINSI = x.x1 AND DAT_OBJEK_PAJAK.KD_DATI2 = x.x2 AND DAT_OBJEK_PAJAK.KD_KECAMATAN = x.x3 AND DAT_OBJEK_PAJAK.KD_KELURAHAN = x.x4 AND DAT_OBJEK_PAJAK.KD_BLOK = x.x5 AND DAT_OBJEK_PAJAK.NO_URUT = x.x6 AND DAT_OBJEK_PAJAK.KD_JNS_OP = x.x7) AS alamat_op,
                NM_KECAMATAN AS kecamatan,
                NM_KELURAHAN AS kelurahan
            FROM (
                SELECT
                    KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP AS nop,
                    KD_PROPINSI x1, KD_DATI2 x2, KD_KECAMATAN x3, KD_KELURAHAN x4, KD_BLOK x5, NO_URUT x6, KD_JNS_OP x7,
                    THN_PAJAK_SPPT, TGL_PEMBAYARAN_SPPT
                FROM PEMBAYARAN_SPPT
                WHERE THN_PAJAK_SPPT = '" . $tahun . "'
                    AND TGL_PEMBAYARAN_SPPT BETWEEN TO_DATE('" . $tahun . "-01-01', 'YYYY-MM-DD') AND TO_DATE('" . $tahun . "-02-28', 'YYYY-MM-DD')
            ) x
            LEFT JOIN REF_KECAMATAN kec ON x.x3 = kec.KD_KECAMATAN
            LEFT JOIN REF_KELURAHAN kel ON x.x3 = kel.KD_KECAMATAN AND x.x4 = kel.KD_KELURAHAN
            " . $whre_condition . "
            ORDER BY TGL_PEMBAYARAN_SPPT
        )
        WHERE ROWNUM <= 20
        ";

        $sismiop = DB::connection("oracle")->select($query);
        $arr = array();

        foreach ($sismiop as $key => $d) {
            $arr[] = array(
                "tgl_bayar" => tgl_full($d->tgl_pembayaran_sppt, 0),
                "nop" => $d->nop,
                "wp" => $d->nm_wp,
                "alamatop" => $d->alamat_op . ", " . $d->kelurahan . ", " . $d->kecamatan
            );
        }

        return Datatables::of($arr)->make(true);
    }

    public function datatable_pembayaran_tinggi(Request $request)
    {
        $tahun = $request->tahun ?? date('y');
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;
        $whre_condition = "";
        $kec_madiun = $this->get_kec_madiun();
        // dd(array_column($kec_madiun, 0));
        $whre_condition = "WHERE NM_KECAMATAN IN ('" . implode("', '", array_column($kec_madiun, 0)) . "')";


        if (!is_null($kecamatan) && is_null($kelurahan)) {
            $whre_condition .= " AND NM_KECAMATAN = '" . $kecamatan . "'";
        } elseif (!is_null($kecamatan) && !is_null($kelurahan)) {
            $whre_condition .= " AND NM_KELURAHAN = '" . $kelurahan . "'";
        }
        
        $query = "
            SELECT
                *
            FROM
                (
                    SELECT
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
                            WHERE
                                extract(year FROM TGL_PEMBAYARAN_SPPT) = $tahun
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
                        LEFT JOIN REF_KECAMATAN kec ON x.kd_kecamatan = kec.KD_KECAMATAN
                        LEFT JOIN REF_KELURAHAN kel ON x.kd_kecamatan = kel.KD_KECAMATAN
                        AND x.kd_kelurahan = kel.KD_KELURAHAN
                        " . $whre_condition . "
                    ORDER BY
                        nominal DESC
                ) x2
            WHERE
                ROWNUM <= 20
        ";

        $sismiop = DB::connection("oracle")->select($query);
        $arr = array();

        foreach ($sismiop as $key => $d) {
            $arr[] = array(
                "nop" => $d->nop,
                "wp" => $d->nama_subjek_pajak,
                "alamatop" => $d->alamat_objek_pajak . ", " . $d->kelurahan . ", " . $d->kecamatan,
                "nominal" => rupiahFormat($d->nominal)
            );
        }

        return Datatables::of($arr)->make(true);
    }


    public function datatable_jumlah_transaksi_pbb(Request $request){
        $tahun = $request->tahun;
        $metode_pembayaran = $request->metode_pembayaran;

        $query = "
        select * from (
            SELECT ABC.TAHUN, ABC.TEMPAT_PEMBAYARAN, round(AVG(ABC.NOP),2) AS RATA2_NOP, ABC.JAM,
             ABC.ANGKA_HARI
            FROM
            (SELECT EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT) AS TAHUN, COUNT(*) AS NOP,
            EXTRACT(HOUR FROM CAST(TGL_REKAM_BYR_SPPT AS TIMESTAMP)) AS JAM, EXTRACT(MONTH FROM TGL_PEMBAYARAN_SPPT) AS BULAN,
            TO_CHAR(TGL_PEMBAYARAN_SPPT, 'D') angka_hari,
            CASE WHEN KD_TP='04'  THEN 'BANK JATIM'
            ELSE 'LAINNYA' END AS TEMPAT_PEMBAYARAN
            FROM PEMBAYARAN_SPPT
            WHERE EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT) = $tahun
            GROUP BY
             EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT),
              KD_TP, NIP_REKAM_BYR_SPPT, EXTRACT(MONTH FROM TGL_PEMBAYARAN_SPPT), 
              EXTRACT(HOUR FROM CAST(TGL_REKAM_BYR_SPPT AS TIMESTAMP)),
             TO_CHAR(TGL_PEMBAYARAN_SPPT, 'D')
            ) ABC
            GROUP BY ABC.TAHUN, ABC.TEMPAT_PEMBAYARAN, ABC.JAM, ABC.ANGKA_HARI
            ORDER BY ABC.JAM
            ) x
            pivot ( 
              sum(rata2_nop) for angka_hari in ( 
                2 SENIN, 3 SELASA, 4 RABU, 5 KAMIS, 6 JUMAT, 7 SABTU, 1 MINGGU 
              )
            )
            order by TEMPAT_PEMBAYARAN, TAHUN desc, JAM
        ";



        $sismiop = DB::connection("oracle")->select($query);
        // dd($sismiop);
        $arr = array();

        foreach ($sismiop as $key => $d) {
            $arr[] = array(
                "jam" => $d->jam,
                "senin" => $d->senin,
                "selasa" => $d->selasa,
                "rabu" => $d->rabu,
                "kamis" => $d->kamis,
                "jumat" => $d->jumat,
                "sabtu" => $d->sabtu,
                "minggu" => $d->minggu
            );
        }

        return Datatables::of($arr)->make(true);
    }

    public function datatable_op_wilayah(Request $request)
    {
        $tahun = $request->tahun ?? date('y');
        $query = DB::table("data.objek_pajak_wilayah")
            ->whereIn("kecamatan", $this->get_kec_madiun())
            ->where('tahun', $tahun)
            ->orderBy('kecamatan', 'ASC')
            ->get();

        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                $arr[] =
                    array(
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan,
                        "nop" => number_format($d->nop),
                        "nominal" => rupiahFormat($d->nominal)
                    );
            }
        }
        //dd($arr);
        return Datatables::of($arr)
            ->rawColumns(['nop'])
            ->make(true);
    }

    public function get_chart_op_wilayah(Request $request)
    {

        $tahun = $request->tahun ?? date('y');
        $kategori = $request->kategori;
        $query = DB::table("data.objek_pajak_wilayah")
            ->where('tahun', $tahun)
            ->whereIn("kecamatan", $this->get_kec_madiun())
            ->groupBy('kecamatan')
            ->orderBy('kecamatan', 'ASC')
            ->selectRaw('kecamatan as series,sum(nop) as nop,sum(nominal) as nominal')
            ->get();
        if ($query->isNotEmpty()) {
            foreach ($query as $key => $value) {
                if ($kategori == 'nop') {
                    $arr = $value->nop;
                } else {
                    $arr = $value->nominal;
                }
                if (!isset($data['nilai'])) {
                    $data['nilai'] = array();
                }
                array_push($data['nilai'], $arr);

                if (!isset($data['series'])) {
                    $data['series'] = array();
                }
                array_push($data['series'], $value->series);
            }
        } else {
            $data['nilai'] = 0;
            $data['series'] = '';
        }
        return $data;
    }

    public function search()
    {
        return view("admin.pbb.search");
    }

    function query_search(Request $request)
    {
        // dd($request);
        $arr = array();
        $kategori = $request->kategori;
        $search = str_replace(".", "", $request->search);

        $KD_PROPINSI = substr($search, 0, 2);
        $KD_DATI2 = substr($search, 2, 2);
        $KD_KECAMATAN = substr($search, 4, 3);
        $KD_KELURAHAN = substr($search, 7, 3);
        $KD_BLOK = substr($search, 10, 3);
        $NO_URUT = substr($search, 13, 4);
        $KD_JNS_OP = substr($search, 17, 1);

        // CASE
        //     WHEN CEIL(MONTHS_BETWEEN(SYSDATE, SPPT.TGL_JATUH_TEMPO_SPPT)) > 24
        //     THEN CEIL((SPPT.PBB_YG_HARUS_DIBAYAR_SPPT * 0.48))
        //     WHEN CEIL(MONTHS_BETWEEN(SYSDATE, SPPT.TGL_JATUH_TEMPO_SPPT)) < 1
        //     THEN 0
        //     ELSE CEIL((SPPT.PBB_YG_HARUS_DIBAYAR_SPPT * CEIL(MONTHS_BETWEEN(SYSDATE, SPPT.TGL_JATUH_TEMPO_SPPT)) * 0.02))
        // END AS DENDA_DARI_SPPT,
        // dd($KD_JNS_OP);
        // dd($KD_PROPINSI.'-'.$KD_DATI2.'-'.$KD_KECAMATAN.'-'.$KD_KELURAHAN.'-'.$KD_BLOK.'-'.$NO_URUT.'-'.$KD_JNS_OP);
        $query = "
        SELECT SPPT.*,
        DAT_OBJEK_PAJAK.JALAN_OP,
        DAT_OBJEK_PAJAK.BLOK_KAV_NO_OP,
        DAT_OBJEK_PAJAK.RW_OP,
        DAT_OBJEK_PAJAK.RT_OP,
        REF_KECAMATAN.NM_KECAMATAN,
        REF_KELURAHAN.NM_KELURAHAN,
        
        0 AS DENDA_DARI_SPPT,
        PEMBAYARAN_SPPT.TGL_PEMBAYARAN_SPPT AS TGL_BAYAR,
        PEMBAYARAN_SPPT.DENDA_SPPT AS DENDA_DARI_PEMBAYARAN,
        PEMBAYARAN_SPPT.JML_SPPT_YG_DIBAYAR AS YANG_DIBAYAR
        FROM SPPT
        LEFT JOIN PEMBAYARAN_SPPT
        ON PEMBAYARAN_SPPT.KD_PROPINSI     = SPPT.KD_PROPINSI
        AND PEMBAYARAN_SPPT.KD_DATI2       = SPPT.KD_DATI2
        AND PEMBAYARAN_SPPT.KD_KECAMATAN   = SPPT.KD_KECAMATAN
        AND PEMBAYARAN_SPPT.KD_KELURAHAN   = SPPT.KD_KELURAHAN
        AND PEMBAYARAN_SPPT.KD_BLOK        = SPPT.KD_BLOK
        AND PEMBAYARAN_SPPT.NO_URUT        = SPPT.NO_URUT
        AND PEMBAYARAN_SPPT.KD_JNS_OP      = SPPT.KD_JNS_OP
        AND PEMBAYARAN_SPPT.THN_PAJAK_SPPT = SPPT.THN_PAJAK_SPPT

        LEFT JOIN DAT_OBJEK_PAJAK
        ON DAT_OBJEK_PAJAK.KD_PROPINSI     = SPPT.KD_PROPINSI
        AND DAT_OBJEK_PAJAK.KD_DATI2       = SPPT.KD_DATI2
        AND DAT_OBJEK_PAJAK.KD_KECAMATAN   = SPPT.KD_KECAMATAN
        AND DAT_OBJEK_PAJAK.KD_KELURAHAN   = SPPT.KD_KELURAHAN
        AND DAT_OBJEK_PAJAK.KD_BLOK        = SPPT.KD_BLOK
        AND DAT_OBJEK_PAJAK.NO_URUT        = SPPT.NO_URUT
        AND DAT_OBJEK_PAJAK.KD_JNS_OP      = SPPT.KD_JNS_OP

        LEFT JOIN REF_KECAMATAN
        ON REF_KECAMATAN.KD_PROPINSI     = SPPT.KD_PROPINSI
        AND REF_KECAMATAN.KD_DATI2       = SPPT.KD_DATI2
        AND REF_KECAMATAN.KD_KECAMATAN   = SPPT.KD_KECAMATAN

        LEFT JOIN REF_KELURAHAN
        ON REF_KELURAHAN.KD_PROPINSI     = SPPT.KD_PROPINSI
        AND REF_KELURAHAN.KD_DATI2       = SPPT.KD_DATI2
        AND REF_KELURAHAN.KD_KECAMATAN   = SPPT.KD_KECAMATAN
        AND REF_KELURAHAN.KD_KELURAHAN   = SPPT.KD_KELURAHAN

        WHERE SPPT.KD_PROPINSI             = '" . $KD_PROPINSI . "'
        AND SPPT.KD_DATI2                  = '" . $KD_DATI2 . "'
        AND SPPT.KD_KECAMATAN              = '" . $KD_KECAMATAN . "'
        AND SPPT.KD_KELURAHAN              = '" . $KD_KELURAHAN . "'
        AND SPPT.KD_BLOK                   = '" . $KD_BLOK . "'
        AND SPPT.NO_URUT                   = '" . $NO_URUT . "'
        AND SPPT.KD_JNS_OP                 = '" . $KD_JNS_OP . "'
        
        Order By SPPT.THN_PAJAK_SPPT DESC
       ";

        $d_data = DB::connection("oracle")->select($query);
        // dd($d_data);
        // if(count($d_data) > 0){
        foreach ($d_data as $key => $d) {
            # code...
            $arr['profil'] =
                array(
                    "nop" => $request->search,
                    "nama_wp" => $d->nm_wp_sppt,
                    "kota_wp" => $d->kota_wp_sppt,
                    "kelurahan_wp" => $d->kelurahan_wp_sppt,
                    "alamat_wp" => "RT." . $d->rt_wp_sppt . "/ RW." . $d->rw_wp_sppt . " " . $d->jln_wp_sppt . " ," . $d->blok_kav_no_wp_sppt,
                    // "jln_wp"=>$d->jln_wp_sppt,
                    // "rt_wp"=>$d->rt_wp_sppt,
                    // "rw_wp"=>$d->rw_wp_sppt,
                    // "blok_kav_no_wp_sppt"=>$d->blok_kav_no_wp_sppt,


                    "alamat_op" => "RT." . $d->rt_op . "/ RW." . $d->rw_op . " " . $d->jalan_op . " ," . $d->blok_kav_no_op,
                    "kecamatan_op" => $d->nm_kecamatan,
                    "kelurahan_op" => $d->nm_kelurahan
                    // "blok_kav_no_op"=>$d->blok_kav_no_op,
                    // "jalan_op"=>$d->jalan_op,
                    // "rt_op"=>$d->rt_op_sppt,
                    // "rw_op"=>$d->rw_op_sppt,
                );

            if ($d->status_pembayaran_sppt == 0 && $d->tgl_bayar == "") {
                $status = "Belum Bayar";
            } else {
                $status = "Lunas";
            }

            if (is_null($d->tgl_bayar)) {
                $tglbyr = "-";
            } else {
                $tglbyr = tgl_full($d->tgl_bayar, 0);
            }

            $arr['history'][] =
                array(
                    "tahun" => $d->thn_pajak_sppt,
                    "tgl_bayar" => $tglbyr,
                    "pajak_terhutang" => number_format($d->pbb_yg_harus_dibayar_sppt),
                    "status_pembayaran" => $status,
                );
        }
        // }

        return response()->json($arr);
    }

    public function detail_kepatuhan_wp($tahun, $kecamatan, $kelurahan)
    {
        $tahun = $request->tahun ?? date('y');
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pbb.detail_kepatuhan_wp")->with(compact('tahun', 'kecamatan', 'kelurahan'));
    }

    public function datatable_detail_kepatuhan_wp(Request $request)
    {
        $tahun = $request->tahun ?? date('y');
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;

        $query_sismiop = `SELECT
                nop,
                tahun_sppt,
                nama_subjek_pajak,
                alamat_subjek_pajak,
                alamat_objek_pajak,
                NM_KECAMATAN AS kecamatan,
                NM_KELURAHAN AS kelurahan,
                tgl_pembayaran_sppt 
            FROM
                (
                SELECT
                        KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP AS nop,
                        THN_PAJAK_SPPT AS tahun_sppt,
                        kd_kecamatan,
                        kd_kelurahan,
                        tgl_pembayaran_sppt 
                FROM
                        pembayaran_sppt 
                WHERE
                CASE
                                
                                WHEN extract( year FROM TGL_PEMBAYARAN_SPPT ) = to_number( REGEXP_REPLACE( THN_PAJAK_SPPT, '[^0-9]+', '' ) ) 
                                AND extract( month FROM TGL_PEMBAYARAN_SPPT ) <= 10 THEN
                                        1 ELSE 0 
                                END = 1 
                                
                                -- filter thn pajak,kec,kel yg dipilih
                        AND to_number( REGEXP_REPLACE( THN_PAJAK_SPPT, '[^0-9]+', '' ) ) = ` . $tahun . ` 
                        AND NM_KECAMATAN = ` . $kecamatan . ` 
                        AND NM_KELURAHAN = ` . $kelurahan . `  
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
                AND x.KD_KELURAHAN = kel.KD_KELURAHAN`;

        $sismiop = DB::connection("oracle")->select($query_sismiop);
        // dd($sismiop);
        $arr = array();
        // if($sismiop->count() > 0){
        foreach ($sismiop as $key => $d) {
            $arr[] =
                array(
                    "no" => $key + 1,
                    "nop" => $d->nop,
                    "tahun_sppt" => $d->tahun_sppt,
                    "nama_subjek_pajak" => $d->nama_subjek_pajak,
                    "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                    "alamat_objek_pajak" => $d->alamat_objek_pajak,
                    "kecamatan" => $d->kecamatan,
                    "kelurahan" => $d->kelurahan,
                    "tgl_pembayaran_sppt" => $d->tgl_pembayaran_sppt
                );
        }
        // }

        return Datatables::of($arr)
            // ->rawColumns(['nop'])
            ->make(true);;
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
