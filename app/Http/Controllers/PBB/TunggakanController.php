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

class TunggakanController extends Controller
{

    public function index()
    {
        $akses = get_url_akses();
        if ($akses) {
            return redirect()->route("pad.index");
        } else {
            return view("admin.pbb.tunggakan");
        }
    }
    public function get_kec()
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

    public function datatable_tunggakan_nop(Request $request)
    {
        // dd($request->all());
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;
        // dd($kecamatan, $kelurahan);
        $tahun = $request->input('tahun', []);
        if (!$tahun) {
            $tahun = [date('Y')];
        }
        // dd($wilayah);
        $view = '';
        $view = '( SELECT 
            nop,
            npwpd,
            tahun,
            bulan,
            nama_rekening,
            nominal_ketetapan,
            nominal_denda,
            nominal_tunggakan,
            kecamatan,
            kelurahan,
            nama_subjek_pajak,
            nama_objek_pajak
        FROM data.detail_tunggakan_nop
        GROUP BY 
            nop,
            npwpd,
            tahun,
            bulan,
            nama_rekening,
            nominal_ketetapan,
            nominal_denda,
            nominal_tunggakan,
            kecamatan,
            kelurahan,
            nama_subjek_pajak,
            nama_objek_pajak
        ORDER BY tahun DESC) AS a';

        // Execute the query
        $query = DB::connection("pgsql_pbb")->table(DB::connection("pgsql_pbb")->raw($view))
            ->selectRaw("
                    a.nop,
                    a.npwpd,
                    a.tahun,
                    a.bulan,
                    a.nama_rekening,
                    a.nominal_ketetapan,
                    a.nominal_denda,
                    a.nominal_tunggakan,
                    a.kecamatan,
                    a.kelurahan,
                    a.nama_subjek_pajak,
                    a.nama_objek_pajak
                ")
            ->whereIn('a.tahun', $tahun);
        if (!is_null($kecamatan)) {
            $query->where('a.kecamatan', $kecamatan);
        }

        if (!is_null($kelurahan)) {
            $query->where('a.kelurahan', $kelurahan);
        }

        $query = $query->orderBy("a.tahun", "DESC")->get();

        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                $route = url('pbb/tunggakan/sub_tunggakan_nop') . "/" . $d->nop;
                $detail_nop = "<a target='_BLANK' href='" . $route . "' ><u>" . $d->nop . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $arr[] = array(
                    "nop" => $detail_nop,
                    "npwpd" => $d->npwpd,
                    "tahun" => $d->tahun,
                    "bulan" => $d->bulan,
                    "nama_rekening" => $d->nama_rekening,
                    "nominal_ketetapan" => number_format($d->nominal_ketetapan),
                    "nominal_denda" => number_format($d->nominal_denda),
                    "nominal_tunggakan" => number_format($d->nominal_tunggakan),
                    "kecamatan" => $d->kecamatan,
                    "kelurahan" => $d->kelurahan,
                    "nama_subjek_pajak" => $d->nama_subjek_pajak,
                    "nama_objek_pajak" => $d->nama_objek_pajak
                );
            }
        }
        return Datatables::of($arr)
            ->rawColumns(['nop'])
            ->make(true);
    }

    public function datatable_tunggakan_level(Request $request)
    {
        // dd($request->all());
        $wilayah = $request->wilayah;
        if (!is_null($wilayah)) {
            if ($wilayah == 'Kelurahan') {
                $query = DB::connection("pgsql_pbb")->table("data.v_tunggakan_level_daerah")
                    ->selectRaw("CONCAT(kecamatan, ' - ', kelurahan) as wilayah,
                    sum(nominal_ringan) AS nominal_ringan,
                    sum(nominal_sedang) AS nominal_sedang,
                    sum(nominal_berat) AS nominal_berat,
                    sum(nop_ringan) AS nop_ringan,
                    sum(nop_sedang) AS nop_sedang,
                    sum(nop_berat) AS nop_berat
                ")
                    ->whereIn('kecamatan', $this->get_kec())
                    ->groupBy('kecamatan')
                    ->groupBy('kelurahan')
                    ->orderBy('kecamatan', 'ASC')
                    ->orderBy('kelurahan', 'ASC')
                    ->get();
            } elseif ($wilayah == 'Kecamatan') {
                $query = DB::connection("pgsql_pbb")->table("data.v_tunggakan_level_daerah")
                    ->selectRaw("kecamatan as wilayah,
                    sum(nominal_ringan) AS nominal_ringan,
                    sum(nominal_sedang) AS nominal_sedang,
                    sum(nominal_berat) AS nominal_berat,
                    sum(nop_ringan) AS nop_ringan,
                    sum(nop_sedang) AS nop_sedang,
                    sum(nop_berat) AS nop_berat
                ")
                    ->whereIn('kecamatan', $this->get_kec())
                    ->groupBy('kecamatan')
                    ->orderBy('kecamatan', 'ASC')
                    ->get();
            } else {
                $query = DB::connection("pgsql_pbb")->table("data.v_tunggakan_level_daerah")
                    ->selectRaw(" 'Kota Senyum' as wilayah,
                    sum(nominal_ringan) AS nominal_ringan,
                    sum(nominal_sedang) AS nominal_sedang,
                    sum(nominal_berat) AS nominal_berat,
                    sum(nop_ringan) AS nop_ringan,
                    sum(nop_sedang) AS nop_sedang,
                    sum(nop_berat) AS nop_berat
                ")
                    ->whereIn('kecamatan', $this->get_kec())
                    ->get();
            }
        } else {
            $query = DB::connection("pgsql_pbb")->table("data.v_tunggakan_level_daerah")
                ->selectRaw(" 'Kota Senyum' as wilayah,
                sum(nominal_ringan) AS nominal_ringan,
                sum(nominal_sedang) AS nominal_sedang,
                sum(nominal_berat) AS nominal_berat,
                sum(nop_ringan) AS nop_ringan,
                sum(nop_sedang) AS nop_sedang,
                sum(nop_berat) AS nop_berat
            ")
                ->whereIn('kecamatan', $this->get_kec())
                ->get();
        }
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                // dd($wilayah);
                $route = url('pbb/tunggakan/detail_tunggakan_level') . "/" . $wilayah . "/" . $d->wilayah;
                $detail = "<a target='_BLANK' href='" . $route . "' ><u>" . $d->wilayah . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $route_ringan = url('pbb/tunggakan/detail_tunggakan_level') . "/RINGAN/" . $wilayah . "/" . $d->wilayah;
                $detail_ringan = "<a target='_BLANK' href='" . $route_ringan . "' ><u>" . number_format($d->nop_ringan) . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $route_sedang = url('pbb/tunggakan/detail_tunggakan_level') . "/SEDANG/" . $wilayah . "/" . $d->wilayah;
                $detail_sedang = "<a target='_BLANK' href='" . $route_sedang . "' ><u>" . number_format($d->nop_sedang) . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $route_berat = url('pbb/tunggakan/detail_tunggakan_level') . "/BERAT/" . $wilayah . "/" . $d->wilayah;
                $detail_berat = "<a target='_BLANK' href='" . $route_berat . "' ><u>" . number_format($d->nop_berat) . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

                $arr[] =
                    array(
                        "wilayah" => $d->wilayah,
                        "nominal_ringan" => number_format($d->nominal_ringan),
                        "nominal_sedang" => number_format($d->nominal_sedang),
                        "nominal_berat" => number_format($d->nominal_berat),
                        "nop_ringan" => $detail_ringan,
                        "nop_sedang" => $detail_sedang,
                        "nop_berat" => $detail_berat
                    );
            }
        }

        return Datatables::of($arr)
            ->rawColumns(['wilayah', 'nop_ringan', 'nop_sedang', 'nop_berat'])
            ->make(true);
    }

    public function detail()
    {
        return view("admin.pbb.detail_tunggakan");
    }

    public function datatable_detail_tunggakan()
    {

        $query = DB::table("data.detail_tunggakan")->whereIn('kecamatan', $this->get_kec())->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "tahun" => $d->tahun,
                        "bulan" => $d->bulan,
                        "nama_rekening" => $d->nama_rekening,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan),
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function datatable_tunggakan_paling_tinggi(Request $request)
    {
        // dd($request->tahun);
        $tahun = $request->tahun ?? date('Y');

        // dd($tahun);
        if (!is_null($request->tahun)) {
            // Fetching records for a range between requested year and the current year
            $d_data = DB::connection("pgsql_pbb")->table("data.v_detail_tunggakan_level_ta")
                ->where('tahun_pajak', $tahun)
                ->select('tahun_pajak', DB::raw('SUM(jumlah_tunggakan) as total_tunggakan'), 'kecamatan', 'kelurahan', 'nop', 'nominal_tunggakan')
                ->groupBy('tahun_pajak', 'kecamatan', 'kelurahan', 'nop', 'nominal_tunggakan')
                ->orderBy('total_tunggakan', 'DESC')
                ->orderBy('tahun_pajak', 'DESC')
                ->limit(5)
                ->get();
        } else {
            // Fetching records for all years
            $d_data = DB::connection("pgsql_pbb")->table("data.v_detail_tunggakan_level_ta")
                ->select('tahun_pajak', DB::raw('SUM(jumlah_tunggakan) as total_tunggakan'), 'kecamatan', 'kelurahan', 'nop', 'nominal_tunggakan')
                ->groupBy('tahun_pajak', 'kecamatan', 'kelurahan', 'nop', 'nominal_tunggakan')
                ->orderBy('total_tunggakan', 'DESC')
                ->orderBy('tahun_pajak', 'DESC')
                ->limit(5)
                ->get();
        }
        // dd($d_data);

        $arr = array();
        if ($d_data->count() > 0) {
            $no = 1;  // Inisialisasi counter untuk nomor urut
            foreach ($d_data as $key => $d) {
                $route = url('pbb/tunggakan/detail_tunggakan_paling_tinggi') . "/" . $d->nop . "/" . $tahun;
                $detail = "<a target='_BLANK' href='" . $route . "' ><u>" . number_format($d->total_tunggakan) . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";
                $arr[] = array(
                    "no" => $no++,  // Tambahkan nomor urut yang bertambah setiap iterasi
                    "nop" => $d->nop,
                    "jumlah_tunggakan" => $detail,
                    "nominal" => rupiahFormat($d->nominal_tunggakan)
                );
            }
        }
        return Datatables::of($arr)
            ->rawColumns(['jumlah_tunggakan'])
            ->make(true);
    }

    public function detail_tunggakan_paling_tinggi($nop, $tahun)
    {
        $tahun = $tahun;
        $nop = $nop;
        return view("admin.pbb.detail_tunggakan_paling_tinggi")->with(compact('nop', 'tahun'));
    }

    public function datatable_detail_tunggakan_paling_tinggi(Request $request)
    {
        $nop = $request->nop;
        $tahun = $request->tahun;

        $query = "
            SELECT * 
            FROM \"data\".\"detail_tunggakan_nop\"
            WHERE \"nop\" = :nop AND \"tahun\" = :tahun
        ";

        // Menjalankan query dengan DB facade dan memasukkan hasilnya ke dalam variabel
        $results = DB::connection('pgsql_pbb')->select($query, ['nop' => $nop, 'tahun' => $tahun]);
        // dd($results);
        $arr = array();
        // if($tinggi->count() > 0){
        foreach ($results as $key => $d) {
            $arr[] = array(
                "nama_subjek_pajak" => $d->nama_subjek_pajak,
                "nama_objek_pajak" => $d->nama_objek_pajak,
                "nama_rekening" => $d->nama_rekening,
                "kecamatan" => $d->kecamatan,
                "kelurahan" => $d->kelurahan,
                "bulan" => $d->bulan,
                "nominal_ketetapan" => $d->nominal_ketetapan,
                "nominal_denda" => $d->nominal_denda,
                "nominal_tunggakan" => $d->nominal_tunggakan
            );
        }
        // }
        // dd($arr);
        return Datatables::of($arr)
            ->rawColumns(['pembayaran'])
            ->make(true);
    }

    public function detail_pembayaran_tunggakan_wp($tahun_sppt, $tahun_bayar, $kecamatan, $kelurahan)
    {
        // dd($tahun, $wilayah, $nama_wilayah);
        $tahun_sppt = $tahun_sppt;
        $tahun_bayar = $tahun_bayar;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pbb.detail_pembayaran_tunggakan_wp")->with(compact('kecamatan', 'kelurahan', 'tahun_sppt', 'tahun_bayar'));
    }

    public function datatable_pembayaran_tunggakan_wp(Request $request)
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

    public function datatable_tunggakan_buku(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $kecamatan =  $request->kecamatan;
        $kelurahan =  $request->kelurahan;
        $buku = $request->buku;
        $select = 'tahun_sppt, buku, nominal_baku,nominal_pokok,nominal_denda,nominal_terima,nop_baku,nop_bayar,kecamatan,kelurahan';

        $d_data = DB::table("data.tunggakan_buku")
            ->when($tahun, function ($query) use ($tahun) {
                $query->where('tahun_sppt', $tahun);
            })->when($buku, function ($query) use ($buku) {
                $query->where('buku', $buku);
            })->when($kecamatan, function ($query) use ($kecamatan) {
                // dd($kecamatan);
                $query->where('kecamatan', $kecamatan);
            })->when($kelurahan, function ($query) use ($kelurahan) {
                $query->where('kelurahan', $kelurahan);
            })->select(DB::raw($select))
            ->whereIn('kecamatan', $this->get_kec())
            ->get();
        // dd($d_data);

        $arr = array();
        if ($d_data->count() > 0) {
            foreach ($d_data as $key => $d) {

                $route_detail = url('pbb/tunggakan/detail_tunggakan_buku') . "/" . $d->buku . "/" . $d->tahun_sppt . "/" . $d->kecamatan . "/" . $d->kelurahan;
                if ($d->nop_bayar > $d->nop_baku) {
                    $jumlah_tunggakan = 0;
                } else {
                    $jumlah_tunggakan = $d->nop_baku - $d->nop_bayar;
                }
                $detail = "<a target='_BLANK' href='" . $route_detail . "' ><u>" . $jumlah_tunggakan . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";
                $arr[] =
                    array(
                        "tahun" => $d->tahun_sppt,
                        "buku" => $d->buku,
                        "nominal_baku" => rupiahFormat($d->nominal_baku),
                        "nominal_pokok" => rupiahFormat($d->nominal_pokok),
                        "nominal_denda" => rupiahFormat($d->nominal_denda),
                        "nominal_terima" => rupiahFormat($d->nominal_terima),
                        "nop_baku" => number_format($d->nop_baku),
                        "nop_bayar" => number_format($d->nop_bayar),
                        "jumlah_tunggakan" => $detail,
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan,
                    );
            }
        }
        // dd($buku, $kecamatan, $kelurahan, $tahun,$arr);
        return Datatables::of($arr)
            ->rawColumns(['jumlah_tunggakan'])
            ->make(true);
    }

    public function sub_tunggakan_nop($nop)
    {
        $nop = $nop;
        return view("admin.pbb.sub_tunggakan_nop")->with(compact('nop'));
    }

    public function datatable_sub_tunggakan_nop(Request $request)
    {
        $nop = $request->nop;

        // dd($nop);
        $view = '( SELECT 
            nop,
            npwpd,
            kecamatan,
            kelurahan,
            no_telp,
            email,
            nama_subjek_pajak,
            alamat_subjek_pajak,
            nama_objek_pajak,
            nama_rekening,
            alamat_objek_pajak
        FROM data.objek_pajak_ta
        GROUP BY 
            nop,
            npwpd,
            kecamatan,
            kelurahan,
            no_telp,
            email,
            nama_subjek_pajak,
            alamat_subjek_pajak,
            nama_objek_pajak,
            nama_rekening,
            alamat_objek_pajak
        ORDER BY MAX(nop) DESC) AS a';

        // Execute the query
        $query = DB::connection("pgsql_pbb")->table(DB::connection("pgsql_pbb")->raw($view))
            ->selectRaw("
                    a.nop,
                    a.npwpd,
                    a.kecamatan,
                    a.kelurahan,
                    a.no_telp,
                    a.email,
                    a.nama_subjek_pajak,
                    a.alamat_subjek_pajak,
                    a.nama_objek_pajak,
                    a.nama_rekening,
                    a.alamat_objek_pajak
                ")
            ->where('a.nop', $nop)
            ->get();


        // dd($query);
        $arr = array();
        // dd($query);
        if ($query->count() > 0) {
            foreach ($query as $d) {
                $arr[] = [
                    'nop' => $d->nop,
                    'npwpd' => $d->npwpd,
                    'kecamatan' => $d->kecamatan,
                    'kelurahan' => $d->kelurahan,
                    'no_telp' => $d->no_telp,
                    'email' => $d->email,
                    'nama_subjek_pajak' => $d->nama_subjek_pajak,
                    'alamat_subjek_pajak' => $d->alamat_subjek_pajak,
                    'nama_objek_pajak' => $d->nama_objek_pajak,
                    'alamat_objek_pajak' => $d->alamat_objek_pajak,
                    'nama_rekening' => $d->nama_rekening,
                ];
            }
        }
        // dd($arr);

        return Datatables::of($arr)
            ->make(true);
    }

    public function detail_tunggakan_nop($tahun, $wilayah, $nama_wilayah)
    {
        // dd($tahun, $wilayah, $nama_wilayah);
        $tahun = $tahun;
        $wilayah = $wilayah;
        $nama_wilayah = $nama_wilayah;
        return view("admin.pbb.detail_tunggakan_nop")->with(compact('tahun', 'wilayah', 'nama_wilayah'));
    }

    public function datatable_detail_tunggakan_nop(Request $request)
    {
        $tahun = $request->tahun;
        $wilayah = strtolower($request->wilayah);
        if ($wilayah == 'kelurahan') {
            $parts = Str::of($request->nama_wilayah)->explode(' - ');
            $nama_wilayah = $parts[1];
            // dd($parts);
        } else {
            $nama_wilayah = $request->nama_wilayah;
        }
        // dd($tahun, $wilayah,$nama_wilayah);

        if ($wilayah == 'kelurahan') {
            $query_where = " WHERE NM_KELURAHAN = '" . $nama_wilayah . "'";
        } elseif ($wilayah == 'kecamatan') {
            $query_where = " WHERE NM_KECAMATAN = '" . $nama_wilayah . "'";
        } else {
            $query_where = "";
        }

        // dd($query_where);
        $query_sismiop = "
                select 
                nop,
                tahun_sppt,
                nama_subjek_pajak,
                alamat_subjek_pajak,
                alamat_objek_pajak,
                NM_KECAMATAN as kecamatan,
                NM_KELURAHAN as kelurahan,
                nominal,
                'SISMIOP' as sumber_data
                from (
                SELECT 
                        KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
                                KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop,
                        THN_PAJAK_SPPT as tahun_sppt,
                        NM_WP_SPPT as nama_subjek_pajak,
                        JLN_WP_SPPT as alamat_subjek_pajak,
                        STATUS_PEMBAYARAN_SPPT,
                        KD_KECAMATAN,
                        KD_KELURAHAN,
                        PBB_YG_HARUS_DIBAYAR_SPPT as nominal
                FROM SPPT

                where STATUS_PEMBAYARAN_SPPT=0 and THN_PAJAK_SPPT=" . $tahun . "
                ) x
                left join
                (SELECT KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
                                KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop2, JALAN_OP as alamat_objek_pajak
                FROM DAT_OBJEK_PAJAK
                ) y
                on x.nop = y.nop2
                left join REF_KECAMATAN kec on x.KD_KECAMATAN = kec.KD_KECAMATAN
                left join REF_KELURAHAN kel on  x.KD_KECAMATAN = kel.KD_KECAMATAN and x.KD_KELURAHAN = kel.KD_KELURAHAN
                " . $query_where;

        $sismiop = DB::connection("oracle")->select($query_sismiop);



        $arr = array();
        // if($sismiop->count() > 0){
        foreach ($sismiop as $key => $d) {
            $arr[] =
                array(
                    "nop" => $d->nop,
                    "tahun_sppt" => $d->tahun_sppt,
                    "nama_subjek_pajak" => $d->nama_subjek_pajak,
                    "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                    "alamat_objek_pajak" => $d->alamat_objek_pajak,
                    "kecamatan" => $d->kecamatan,
                    "kelurahan" => $d->kelurahan,
                    "nominal" => number_format($d->nominal)
                );
        }
        // }
        return Datatables::of($arr)->make(true);
    }

    public function detail_tunggakan_buku($buku, $tahun, $kecamatan, $kelurahan)
    {
        // dd($tahun, $kecamatan, $kelurahan);
        $buku = $buku;
        $tahun = $tahun;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pbb.detail_tunggakan_buku")->with(compact('buku', 'tahun', 'kecamatan', 'kelurahan'));
    }

    public function datatable_detail_tunggakan_buku(Request $request)
    {
        $buku = $request->buku;
        $tahun = $request->tahun;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;

        $query_sismiop = "
            select * from (
                    SELECT 
                            CASE 
                                    WHEN PBB_YG_HARUS_DIBAYAR_SPPT <=100000 THEN 'buku 1'
                                    when PBB_YG_HARUS_DIBAYAR_SPPT <=500000 THEN 'buku 2'
                                    when PBB_YG_HARUS_DIBAYAR_SPPT <=2000000 THEN 'buku 3' 
                                    when PBB_YG_HARUS_DIBAYAR_SPPT <=5000000 THEN 'buku 4'
                                    else 'buku 5'
                            end as buku,
                            nop,
                            tahun_pajak,
                            PBB_YG_HARUS_DIBAYAR_SPPT AS nominal,
                            nama_subjek_pajak, 
                            alamat_subjek_pajak, 
                            alamat_objek_pajak,
                            NM_KECAMATAN as kecamatan,
                            NM_KELURAHAN as kelurahan
                    FROM
                    (        SELECT KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
                                            KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop, THN_PAJAK_SPPT, PBB_YG_HARUS_DIBAYAR_SPPT,
                                    NM_WP_SPPT as nama_subjek_pajak, JLN_WP_SPPT as alamat_subjek_pajak, THN_PAJAK_SPPT as tahun_pajak,
                                    KD_KECAMATAN,KD_KELURAHAN
                            FROM SPPT
                            WHERE STATUS_PEMBAYARAN_SPPT='0' 
                            AND THN_PAJAK_SPPT > 2002
                            -- FILTER KECAMATAN & KELURAHAN & TAHUN PAJAK DISINI
                            AND THN_PAJAK_SPPT='" . $tahun . "'
                    ) x
                    left join
                    (SELECT KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
                            KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop2, JALAN_OP as alamat_objek_pajak
                            FROM DAT_OBJEK_PAJAK
                    ) y
                    on x.nop = y.nop2
                    left join REF_KECAMATAN kec on x.KD_KECAMATAN = kec.KD_KECAMATAN
                    left join REF_KELURAHAN kel on  x.KD_KECAMATAN = kel.KD_KECAMATAN and x.KD_KELURAHAN = kel.KD_KELURAHAN
            ) zz
            -- FILTER BUKU
            where buku = '" . $buku . "' AND kecamatan = '" . $kecamatan . "' AND kelurahan = '" . $kelurahan . "'
        ";

        $sismiop = DB::connection("oracle")->select($query_sismiop);
        // dd($sismiop);

        $arr = array();
        // if($sismiop->count() > 0){
        foreach ($sismiop as $key => $d) {
            $arr[] =
                array(
                    "buku" => $d->buku,
                    "nop" => $d->nop,
                    "tahun_sppt" => $d->tahun_pajak,
                    "nama_subjek_pajak" => $d->nama_subjek_pajak,
                    "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                    "alamat_objek_pajak" => $d->alamat_objek_pajak,
                    "kecamatan" => $d->kecamatan,
                    "kelurahan" => $d->kelurahan,
                    "nominal" => number_format($d->nominal)
                );
        }
        // }
        return Datatables::of($arr)->make(true);
    }

    public function detail_tunggakan_level($level, $wilayah, $nama_wilayah = null)
    {
        $level = $level;
        $wilayah = $wilayah;
        $nama_wilayah = $nama_wilayah;
        // dd($wilayah, $nama_wilayah);
        return view("admin.pbb.detail_tunggakan_level")->with(compact('level', 'wilayah', 'nama_wilayah'));
    }

    public function datatable_detail_tunggakan_level(Request $request)
    {
        $level = strtoupper($request->level);
        $wilayah = strtolower($request->wilayah);
        $nama_wilayah = $request->nama_wilayah;

        // Menampilkan isi dari request

        $sismiop = DB::connection("pgsql_pbb")
            ->table("data.v_detail_tunggakan_level_ta")
            ->where('level', $level);

        if ($wilayah == 'kecamatan') {
            $sismiop->where('kecamatan', $nama_wilayah);
        } elseif ($wilayah == 'kelurahan') {
            $parts = Str::of($request->nama_wilayah)->explode(' - ');
            $nama_wilayah = $parts[1];
            // dd($nama_wilayah);
            $sismiop->where('kelurahan', $nama_wilayah);
        }

        $result = $sismiop->get();
        // dd($sismiop);

        // query asli
        // if ($wilayah == 'kelurahan') {
        //     $parts = Str::of($request->nama_wilayah)->explode(' - ');
        //     $nama_wilayah = $parts[1];
        // } else {
        //     $nama_wilayah = $request->nama_wilayah;
        // }

        // if ($wilayah == 'kelurahan') {
        //     $query_where = " WHERE NM_KELURAHAN = '" . $nama_wilayah . "'";
        // } elseif ($wilayah == 'kecamatan') {
        //     $query_where = " WHERE NM_KECAMATAN = '" . $nama_wilayah . "'";
        // } else {
        //     $query_where = "";
        // }
        // $query_sismiop = "
        //         select * from (
        //             SELECT 
        //                     CASE WHEN COUNT(*)=1 THEN 'RINGAN' 
        //                             WHEN COUNT(*)>1 AND COUNT(*) < 5 THEN 'SEDANG'
        //                             WHEN COUNT(*)>=5 THEN 'BERAT' END AS lvl, 
        //                     nop,
        //                     COUNT(*) as jumlah_tahun, 
        //                     SUM(PBB_YG_HARUS_DIBAYAR_SPPT) AS nominal,
        //                     NM_KECAMATAN as kecamatan,
        //         NM_KELURAHAN as kelurahan
        //     FROM
        //             (        SELECT KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
        //                                     KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop, THN_PAJAK_SPPT, PBB_YG_HARUS_DIBAYAR_SPPT,
        //                             --NM_WP_SPPT as nama_subjek_pajak, JLN_WP_SPPT as alamat_subjek_pajak,
        //                             KD_KECAMATAN,KD_KELURAHAN
        //                     FROM SPPT
        //                     WHERE STATUS_PEMBAYARAN_SPPT='0' 
        //                     --AND THN_PAJAK_SPPT > 2002
        //                     -- FILTER KECAMATAN & KELURAHAN DISINI

        //             ) x
        //             left join
        //             (SELECT KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
        //                     KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop2, JALAN_OP as alamat_objek_pajak
        //                     FROM DAT_OBJEK_PAJAK
        //             ) y
        //             on x.nop = y.nop2
        //             left join REF_KECAMATAN kec on x.KD_KECAMATAN = kec.KD_KECAMATAN
        //             left join REF_KELURAHAN kel on  x.KD_KECAMATAN = kel.KD_KECAMATAN and x.KD_KELURAHAN = kel.KD_KELURAHAN
        //                     " . $query_where . "
        //             GROUP BY nop, --, nama_subjek_pajak, alamat_subjek_pajak,alamat_objek_pajak, 
        //                     NM_KECAMATAN, NM_KELURAHAN
        //     ) zz
        //     -- FILTER LEVEL DISINI
        //     where lvl ='" . $level . "'
        //         ";

        // $sismiop = DB::connection("oracle")->select($query_sismiop);
        $arr = array();
        foreach ($result as $key => $d) {


            $route = url('pbb/tunggakan/detail_tunggakan_level_nop') . "/" . $d->nop;
            $detail = "<a target='_BLANK' href='" . $route . "' ><u>" . $d->nop . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";

            $arr[] =
                array(
                    "lvl" => $d->level,
                    "nop" => $detail,
                    "jumlah" => $d->jumlah_tunggakan,
                    "nominal" => number_format($d->nominal_tunggakan),
                    "kecamatan" => $d->kecamatan,
                    "kelurahan" => $d->kelurahan
                );
        }
        // }

        return Datatables::of($arr)
            ->rawColumns(['nop'])
            ->make(true);;
    }

    public function detail_tunggakan_level_nop($nop)
    {
        $nop = $nop;
        // dd($wilayah, $nama_wilayah);
        return view("admin.pbb.detail_tunggakan_level_nop")->with(compact('nop'));
    }

    public function datatable_detail_tunggakan_level_nop(Request $request)
    {
        $nop = strtoupper($request->nop);

        // $query_sismiop = "
        //         SELECT 
        //         THN_PAJAK_SPPT as tahun_pajak,
        //         PBB_YG_HARUS_DIBAYAR_SPPT AS nominal,
        //         nama_subjek_pajak, 
        //         alamat_subjek_pajak, 
        //         alamat_objek_pajak,
        //         NM_KECAMATAN as kecamatan,
        //         NM_KELURAHAN as kelurahan
        // FROM
        // (        SELECT KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
        //                         KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop, THN_PAJAK_SPPT, PBB_YG_HARUS_DIBAYAR_SPPT,
        //                 NM_WP_SPPT as nama_subjek_pajak, JLN_WP_SPPT as alamat_subjek_pajak,
        //                 KD_KECAMATAN,KD_KELURAHAN
        //         FROM SPPT
        //         WHERE STATUS_PEMBAYARAN_SPPT='0' 
        //         AND THN_PAJAK_SPPT > 2002
        //         -- FILTER NOP DISINI
        //         AND KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
        //                         KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP = '" . $nop . "'
        // ) x
        // left join
        // (SELECT KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || 
        //                                                         KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP as nop2, JALAN_OP as alamat_objek_pajak
        //         FROM DAT_OBJEK_PAJAK
        // ) y
        // on x.nop = y.nop2
        // left join REF_KECAMATAN kec on x.KD_KECAMATAN = kec.KD_KECAMATAN
        // left join REF_KELURAHAN kel on  x.KD_KECAMATAN = kel.KD_KECAMATAN and x.KD_KELURAHAN = kel.KD_KELURAHAN
        // order by THN_PAJAK_SPPT desc
        // ";

        // $sismiop = DB::connection("oracle")->select($query_sismiop);
        // dd($sismiop);

        $sismiop = DB::connection("pgsql_pbb")
            ->table("data.detail_tunggakan_ta")
            ->leftJoin("data.objek_pajak_ta", "data.detail_tunggakan_ta.nop", "=", "data.objek_pajak_ta.nop")
            ->where('data.detail_tunggakan_ta.nop', $nop)
            ->get();
        // dd($sismiop);
        $arr = array();
        // if($sismiop->count() > 0){
        foreach ($sismiop as $key => $d) {
            $arr[] =
                array(
                    "tahun_pajak" => $d->tahun,
                    "nominal" => number_format($d->nominal_ketetapan),
                    "nama_subjek_pajak" => $d->nama_subjek_pajak,
                    "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                    "nama_objek_pajak" => $d->nama_objek_pajak,
                    "kecamatan" => $d->kecamatan,
                    "kelurahan" => $d->kelurahan
                );
        }
        // }

        return Datatables::of($arr)
            ->rawColumns(['nop'])
            ->make(true);;
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
}
