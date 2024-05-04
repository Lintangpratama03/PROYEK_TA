<?php

namespace App\Http\Controllers\BPHTB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KetetapanBPHTBController extends Controller
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
            return view("admin.bphtb.ketetapan");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function ketetapanPerolehan(Request $request)
    {
        $arrResult = array();

        $tahun = ($request->tahun) ? $request->tahun : date("Y");

        $data = DB::table("data.rekap_ketetapan_perolehan_bpthb")
            ->when($tahun, function ($query) use ($tahun) {
                $query->where('tahun', $tahun);
            })
            ->orderBy('tahun', 'ASC')
            ->orderBy('bulan', 'ASC')
            ->get();

        foreach ($data as $key => $value) {
            $jenisPerolehan = $value->jenis_perolehan;

            if (!isset($arrResult[$jenisPerolehan])) {
                $arrResult[$jenisPerolehan] = [];
                $arrResult[$jenisPerolehan]['nominal_ketetapan'] = $value->nominal_ketetapan;
                $arrResult[$jenisPerolehan]['jumlah_transaksi'] = $value->jumlah_transaksi;
                foreach (getMonthList() as $key => $valueBulan) {
                    $arrResult[$jenisPerolehan]['arrMonthNominal'][$valueBulan] = "0";
                    $arrResult[$jenisPerolehan]['arrMonthTransaksi'][$valueBulan] = "0";
                }
            }

            $bulan = getMonth($value->bulan);
            $arrResult[$jenisPerolehan]['arrMonthNominal'][$bulan] = $value->nominal_ketetapan;
            $arrResult[$jenisPerolehan]['arrMonthTransaksi'][$bulan] = $value->jumlah_transaksi;
        }

        return response()->json($arrResult);
    }

    public function ketetapanPeruntukan(Request $request)
    {
        $arrResult = array();

        $tahun = ($request->tahun) ? $request->tahun : date("Y");

        $data = DB::table("data.rekap_ketetapan_peruntukan_bphtb")
            ->when($tahun, function ($query) use ($tahun) {
                $query->where('tahun', $tahun);
            })
            ->orderBy('tahun', 'ASC')
            ->orderBy('bulan', 'ASC')
            ->get();

        foreach ($data as $key => $value) {
            $peruntukan = $value->peruntukan;

            if (!isset($arrResult[$peruntukan])) {
                $arrResult[$peruntukan] = [];
                foreach (getMonthList() as $key => $valueBulan) {
                    $arrResult[$peruntukan]['arrMonthNominal'][$valueBulan] = "0";
                    $arrResult[$peruntukan]['arrMonthTransaksi'][$valueBulan] = "0";
                }
            }

            $bulan = getMonth($value->bulan);
            $arrResult[$peruntukan]['arrMonthNominal'][$bulan] = $value->nominal_ketetapan;
            $arrResult[$peruntukan]['arrMonthTransaksi'][$bulan] = $value->jumlah_transaksi;
        }

        return response()->json($arrResult);
    }

    public function ketetapanValidasi(Request $request)
    {
        $arrResult = array();

        $tahun = ($request->tahun) ? $request->tahun : date("Y");

        $data = DB::table("data.rekap_ketetapan_bphtb_validasi")
            ->when($tahun, function ($query) use ($tahun) {
                $query->where('tahun', $tahun);
            })
            ->orderBy('tahun', 'ASC')
            ->orderBy('bulan', 'ASC')
            ->get();

        foreach ($data as $key => $value) {
            $tahunValidasi = $value->tahun;

            if (!isset($arrResult[$tahunValidasi])) {
                $arrResult[$tahunValidasi] = [];

                foreach (getMonthList() as $key => $valueBulan) {
                    $arrResult[$tahunValidasi]['jumlah_ketetapan'][$valueBulan] = "0";
                    $arrResult[$tahunValidasi]['sudah_divalidasi'][$valueBulan] = "0";
                    $arrResult[$tahunValidasi]['belum_divalidasi'][$valueBulan] = "0";
                }
            }

            $bulan = getMonth($value->bulan);
            $arrResult[$tahunValidasi]['jumlah_ketetapan'][$bulan] = $value->jumlah_ketetapan;
            $arrResult[$tahunValidasi]['sudah_divalidasi'][$bulan] = $value->sudah_divalidasi;
            $arrResult[$tahunValidasi]['belum_divalidasi'][$bulan] = $value->belum_divalidasi;
        }

        return response()->json($arrResult);
    }

    public function ketetapanNihilBayar(Request $request)
    {
        $arrResult = array();

        $tahun = ($request->tahun) ? $request->tahun : date("Y");

        $data = DB::table("data.rekap_ketetapan_bphtb_nihil_bayar")
            ->when($tahun, function ($query) use ($tahun) {
                $query->where('tahun', $tahun);
            })
            ->orderBy('tahun', 'ASC')
            ->orderBy('bulan', 'ASC')
            ->get();

        foreach ($data as $key => $value) {
            $tahunValidasi = $value->tahun;

            if (!isset($arrResult[$tahunValidasi])) {
                $arrResult[$tahunValidasi] = [];

                foreach (getMonthList() as $key => $valueBulan) {
                    $arrResult[$tahunValidasi]['jumlah_transaksi'][$valueBulan] = "0";
                    $arrResult[$tahunValidasi]['sudah_bayar'][$valueBulan] = "0";
                    $arrResult[$tahunValidasi]['belum_bayar'][$valueBulan] = "0";
                    $arrResult[$tahunValidasi]['jumlah_transaksi_nihil'][$valueBulan] = "0";
                }
            }

            $bulan = getMonth($value->bulan);
            $arrResult[$tahunValidasi]['jumlah_transaksi'][$bulan] = $value->jumlah_transaksi;
            $arrResult[$tahunValidasi]['sudah_bayar'][$bulan] = $value->sudah_bayar;
            $arrResult[$tahunValidasi]['belum_bayar'][$bulan] = $value->belum_bayar;
            $arrResult[$tahunValidasi]['jumlah_transaksi_nihil'][$bulan] = $value->jumlah_transaksi_nihil;
        }

        return response()->json($arrResult);
    }
    public function detail_ketetapan_perolehan($tahun, $status, $bulan)
    {
        $tahun = $tahun;
        $status = $status;
        $bulan = $bulan;

        // dd($kelurahan);
        return view("admin.bphtb.detail_ketetapan_perolehan")->with(compact('tahun', 'status', 'bulan'));
    }

    public function datatable_detail_ketetapan_perolehan(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $status = $request->status;

        $tgl = '"TANGGALBAYAR"';
        $npop = '"NPOP"';
        $namawp = '"NAMAWP"';
        $alamatwp = '"ALAMATWP"';
        $jml = '"JUMLAHHARUSDIBAYAR"';
        $delet = '"DELETED_AT"';
        $perol = '"JENISPEROLEHAN"';


        $select = "SELECT
        $tgl,
        $npop,
        $namawp,
        $alamatwp,
        $jml AS KETETAPAN
    FROM
        tb_sspd_bphtb
    WHERE
        $delet IS NULL
        AND $jml > 0
        AND EXTRACT(YEAR FROM $tgl) = '$tahun'
        AND EXTRACT(MONTH FROM $tgl) = '$bulan'
        AND $perol = '$status'";

        // dd($select);
        $sismiop = DB::connection("pgsql_bphtb")->select($select);
        // dd($sismiop);
        $arr = array();
        foreach ($sismiop as $key => $d) {
            $arr[] = array(
                "no" => $key + 1,
                "TANGGALBAYAR" => $d->TANGGALBAYAR,
                "NPOP" => $d->NPOP,
                "NAMAWP" => $d->NAMAWP,
                "ALAMATWP" => $d->ALAMATWP,
                "ketetapan" => $d->ketetapan,
            );
        }

        return Datatables::of($arr)->make(true);
    }
    public function detail_ketetapan_peruntukan($tahun, $status, $bulan)
    {
        $tahun = $tahun;
        $status = $status;
        $bulan = $bulan;
        return view("admin.bphtb.detail_ketetapan_peruntukan")->with(compact('tahun', 'status', 'bulan'));
    }

    public function datatable_detail_ketetapan_peruntukan(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $status = $request->status;

        $select = "SELECT
        tgl_transaksi,concat(kd_propinsi,kd_dati2,kd_kecamatan,kd_kelurahan,kd_blok,no_urut,kd_jns_op) nop,
        wp_nama,wp_alamat, bphtb_harus_dibayarkan as ketetapan
        FROM bphtb_sspd abc
        where extract (year from tgl_transaksi)=$tahun and extract (month from tgl_transaksi)=$bulan";

        if ($status === '-') {
            $select .= " and (peruntukan IS NULL OR peruntukan = '-')";
        } else {
            $select .= " and peruntukan='$status'";
        }

        $select .= " order by tgl_transaksi desc";

        $sismiop = DB::connection("pgsql_bphtb")->select($select);
        $arr = array();
        foreach ($sismiop as $key => $d) {
            $arr[] = array(
                "no" => $key + 1,
                "tgl_transaksi" => $d->tgl_transaksi,
                "nop" => $d->nop,
                "nama_wajib_pajak" => $d->wp_nama,
                "alamat_wajib_pajak" => $d->wp_alamat,
                "ketetapan" => rupiahFormat($d->ketetapan),
            );
        }

        return Datatables::of($arr)->make(true);
    }


    public function detail_ketetapan_validasi($tahun, $status, $bulan)
    {
        $tahun = $tahun;
        $status = $status;
        $bulan = $bulan;
        return view("admin.bphtb.detail_ketetapan_validasi")->with(compact('tahun', 'status', 'bulan'));
    }

    public function datatable_detail_ketetapan_validasi(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $status = $request->status;
        $filterCondition = "";
        if ($status === "Sudah Divalidasi") {
            $filterCondition = "verifikasi_date is not null";
        } elseif ($status === "Belum Divalidasi") {
            $filterCondition = "verifikasi_date is null";
        }
        $select = "SELECT
        tgl_transaksi, concat(kd_propinsi, kd_dati2, kd_kecamatan, kd_kelurahan, kd_blok, no_urut, kd_jns_op) AS nop,
        wp_nama, wp_alamat, bphtb_harus_dibayarkan AS ketetapan,
        CASE
            WHEN tgl_transaksi IS NULL THEN 'Belum di Validasi'
            ELSE 'Sudah Divalidasi'
        END AS status
        FROM bphtb_sspd abc
        LEFT JOIN bphtb_perolehan bc ON abc.perolehan_id = bc.id 
        WHERE EXTRACT(YEAR FROM tgl_transaksi) = $tahun AND EXTRACT(MONTH FROM tgl_transaksi) = $bulan";

        if (!empty($filterCondition)) {
            $select .= " and $filterCondition";
        }

        $select .= " order by tgl_transaksi desc";
        //dd($select);
        $sismiop = DB::connection("pgsql_bphtb")->select($select);
        //dd($sismiop);
        $arr = array();
        foreach ($sismiop as $key => $d) {
            $arr[] = array(
                "no" => $key + 1,
                "tgl_transaksi" => $d->tgl_transaksi,
                "nop" => $d->nop,
                "nama_wajib_pajak" => $d->wp_nama,
                "alamat_wajib_pajak" => $d->wp_alamat,
                "ketetapan" => rupiahFormat($d->ketetapan),
            );
        }


        return Datatables::of($arr)->make(true);
    }

    public function detail_ketetapan_nihil_bayar($tahun, $status, $bulan)
    {
        $tahun = $tahun;
        $status = $status;
        $bulan = $bulan;
        return view("admin.bphtb.detail_ketetapan_nihil_bayar")->with(compact('tahun', 'status', 'bulan'));
    }

    public function datatable_detail_ketetapan_nihil_bayar(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $status = strtoupper(str_replace(' ', '_', $request->status));

        $tgl = '"TANGGALBAYAR"';
        $npop = '"NPOP"';
        $namawp = '"NAMAWP"';
        $alamatwp = '"ALAMATWP"';
        $jml = '"JUMLAHHARUSDIBAYAR"';
        $delet = '"DELETED_AT"';

        $select = "select
        $tgl, $npop, $namawp, $alamatwp, $jml as ketetapan,
        case when $jml>0 then
            case when $tgl is not null then 'Sudah Bayar' else 'Belum Bayar' end
        else 'Transaksi Nihil' end as jenis
        from tb_sspd_bphtb
        where
        $delet is null
        and extract(year from $tgl)=$tahun
        and extract(month from $tgl)=$bulan
        and case when $jml>0 then
        case when $tgl is not null then 'Sudah Bayar' else 'Belum Bayar' end
        else 'Transaksi Nihil' end in ('Sudah Bayar','Belum Bayar')";
        // dd($select);
        $sismiop = DB::connection("pgsql_bphtb")->select($select);
        //dd($sismiop);
        $arr = array();
        foreach ($sismiop as $key => $d) {
            $arr[] = array(
                "no" => $key + 1,
                "TANGGALBAYAR" => $d->TANGGALBAYAR,
                "NPOP" => $d->NPOP,
                "NAMAWP" => $d->NAMAWP,
                "ALAMATWP" => $d->ALAMATWP,
                "ketetapan" => $d->ketetapan,
            );
        }

        return Datatables::of($arr)->make(true);
    }



    public function datatablePelaporanPpat(Request $request)
    {
        $data = array();

        $tahun = ($request->tahun) ? $request->tahun : [date("Y")];
        $bulan = ($request->bulan) ? $request->bulan : [date("m")];

        $result = DB::table("data.pelaporan_ppat_bphtb")
            ->whereIn("tahun", $tahun)
            ->whereIn("bulan", $bulan)
            ->orderBy('tahun', 'ASC')
            ->orderBy('bulan', 'ASC')
            ->get();
        // dd($result);
        foreach ($result as $keyPertemuan => $value) {

            $row = array(
                "no" => $keyPertemuan + 1,
                "nama_ppat" => $value->nama_ppat,
                "tahun" => $value->tahun,
                "bulan" => getMonth($value->bulan),
                "jumlah_laporan" => $value->jumlah_laporan,
            );

            array_push($data, $row);
        }

        return DataTables::of($data)
            // ->rawColumns(['action','peserta','jenis_pertemuan','jumlah_peserta_hadir','status'])
            ->make(true);
    }

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
