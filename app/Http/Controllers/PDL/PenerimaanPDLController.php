<?php

namespace App\Http\Controllers\PDL;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use PhpParser\Node\Expr\AssignOp\Concat;

class PenerimaanPDLController extends Controller
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
            return view("admin.pdl.penerimaan", ['data' => $data]);
        }
    }

    public function get_wilayah(Request $request)
    {
        $wilayah = $request->wilayah;
        $value = $request->data;
        // dd($request->value);
        $data = DB::table("data.penerimaan_bulanan")
            ->selectRaw("distinct(kelurahan) as kelurahan")
            ->where($wilayah, $value)
            ->get();
        //dd($data);
        return response()->json($data);
    }

    public function penerimaan_perbulan(Request $request)
    {
        //dd($request->all());
        $tahun = $request->input('tahun', []);
        $bulan = $request->input('bulan', []);
        $jenis_pajak = $request->jenis_pajak;
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
        // if (is_null($jenis_pajak)) {
        //     $jenis_pajak = "Pajak Hotel";
        // }

        $data = $this->get_penerimaan_perbulan($jenis_pajak, $tahun, $bulan, $kecamatan, $kelurahan);
        // $data['p2023'] = $this->get_penerimaan_perbulan('2023');
        // dd($data);
        return $data;
    }

    public function get_penerimaan_perbulan($jenis_pajak, $tahun, $bulan, $kecamatan, $kelurahan)
    {
        // dd($kecamatan);
        if (!is_null($kecamatan) && is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->where('nama_rekening', $jenis_pajak)
                ->selectRaw("tahun,
                bulan,
                nama_rekening,
                kode_rekening,
                sum(penerimaan_per_bulan) penerimaan_per_bulan,	
                sum(jumlah_transaksi_per_bulan) jumlah_transaksi_per_bulan")
                ->whereIn('tahun', $tahun)->whereIn('bulan', $bulan)
                ->where('kecamatan', $kecamatan)
                ->groupby('tahun')
                ->groupby('bulan')
                ->groupby('nama_rekening')
                ->groupby('kode_rekening')
                ->orderBy('bulan', 'ASC')->get();
        } else if (!is_null($kecamatan) && !is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->where('nama_rekening', $jenis_pajak)
                ->selectRaw("tahun,
                bulan,
                nama_rekening,
                kode_rekening,
                sum(penerimaan_per_bulan) penerimaan_per_bulan,	
                sum(jumlah_transaksi_per_bulan) jumlah_transaksi_per_bulan")
                ->whereIn('tahun', $tahun)->whereIn('bulan', $bulan)
                ->where('kelurahan', $kelurahan)
                ->groupby('tahun')
                ->groupby('bulan')
                ->groupby('nama_rekening')
                ->groupby('kode_rekening')
                ->orderBy('bulan', 'ASC')->get();
        } else {
            $query = DB::table("data.penerimaan_bulanan")
                ->where('nama_rekening', $jenis_pajak)
                ->whereIn('tahun', $tahun)->whereIn('bulan', $bulan)
                ->selectRaw("tahun,
                bulan,
                nama_rekening,
                kode_rekening,
                sum(penerimaan_per_bulan) penerimaan_per_bulan,	
                sum(jumlah_transaksi_per_bulan) jumlah_transaksi_per_bulan")
                ->groupby('tahun')
                ->groupby('bulan')
                ->groupby('nama_rekening')
                ->groupby('kode_rekening')
                ->orderBy('bulan', 'ASC')
                ->get();
        }
        // dd($query);
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
        // dd($request->all());
        $tahun = $request->input('tahun', []);
        $bulan = $request->input('bulan', []);
        $jenis_pajak = $request->jenis_pajak;

        $yearnow = date('Y');
        $lastyear = $yearnow - 1;
        if (count($tahun) == 0) {
            $tahun = array($yearnow, (string)$lastyear);
        }
        if (count($bulan) == 0) {
            $bulan = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
            // if (is_null($jenis_pajak)) {
            //     $jenis_pajak = "Pajak Hotel";
            // }
        }

        $data = $this->get_penerimaan_akumulasi($jenis_pajak, $tahun, $bulan);
        // dd($data);
        return $data;
    }

    public function get_penerimaan_akumulasi($jenis_pajak, $tahun, $bulan)
    {

        $query = DB::table("data.penerimaan_bulanan")
            ->selectRaw('nama_rekening, tahun, bulan, SUM(penerimaan_akumulasi) as penerimaan_akumulasi')
            ->where('nama_rekening', $jenis_pajak)
            ->whereIn('tahun', $tahun)
            ->whereIn('bulan', $bulan)
            ->groupBy('nama_rekening', 'tahun', 'bulan')
            ->orderBy('tahun', 'ASC')
            ->orderBy('bulan', 'ASC')
            ->get();

        if ($query->isNotEmpty()) {
            foreach ($query as $key => $value) {
                if (!isset($data['penerimaan'][$value->tahun])) {
                    $data['penerimaan'][$value->tahun] = array();
                }
                array_push($data['penerimaan'][$value->tahun], $value->penerimaan_akumulasi);
            }
            $bulanText = array();

            foreach ($bulan as $key => $value) {
                array_push($bulanText, getMonth($value));
            }
            $data['bulan'] = $bulanText;
        } else {
            $data['penerimaan'] = [];
            $data['bulan'] = [];
        }
        // dd($data);
        return $data;
    }

    function datatable_penerimaan_tahun()
    {
        $thn = '2023';
        $d_data = DB::table("data.penerimaan_tahun_sppt")->where('tahun_bayar', $thn)->groupBy('tahun_sppt')->groupBy('tahun_bayar')->orderBy('tahun_sppt', 'DESC')
            ->select(DB::raw('tahun_bayar, SUM(nominal_terima) AS nominal, SUM(nop) AS nop, tahun_sppt'))->get();
        // dd($d_data);
        $arr = array();
        if ($d_data->count() > 0) {
            foreach ($d_data as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "tahun" => $d->tahun_sppt,
                        "nominal" => rupiahFormat($d->nominal),
                        "nop" => number_format($d->nop)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function detail_penerimaan_perbulan($pajak, $tahun, $bulan, $kecamatan = null, $kelurahan = null)
    {
        $pajak = $pajak;
        $tahun = $tahun;
        $bulan = $bulan;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pdl.detail_penerimaan_bulanan")->with(compact('pajak', 'tahun', 'bulan', 'kecamatan', 'kelurahan'));
    }

    public function datatable_detail_penerimaan_perbulan(Request $request)
    {
        // dd($request->all());
        $pajak = $request->pajak;
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
        from data.tb_penerimaan a
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

    public function penerimaan_harian(Request $request)
    {
        //dd($request->all());
        $tanggal = $request->tanggal;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;
        $jenis_pajak = $request->jenis_pajak;
        $rangeDate = explode(" - ", $tanggal);
        $startDate = Carbon::parse($rangeDate[0])->format('Y-m-d');
        $endDate = Carbon::parse($rangeDate[1])->format('Y-m-d');
        if (!is_null($kecamatan) && is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_harian")
                ->selectRaw("tanggal,sum(penerimaan) penerimaan")
                ->where('nama_rekening', $jenis_pajak)
                ->where("sumber_data", "SIMPADAMA")
                ->where('kecamatan', $kecamatan)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->groupby('tanggal')
                ->orderBy('tanggal', 'ASC')
                ->get();
        } else if (!is_null($kecamatan) && !is_null($kelurahan)) {
            $query = DB::table("data.penerimaan_harian")
                ->selectRaw("tanggal,sum(penerimaan) penerimaan")
                ->where('nama_rekening', $jenis_pajak)
                ->where("sumber_data", "SIMPADAMA")
                ->where('kelurahan', $kelurahan)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->groupby('tanggal')
                ->orderBy('tanggal', 'ASC')
                ->get();
        } else {
            $query = DB::table("data.penerimaan_harian")
                ->selectRaw("tanggal,sum(penerimaan) penerimaan")
                ->where('nama_rekening', $jenis_pajak)
                ->where("sumber_data", "SIMPADAMA")
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->groupby('tanggal')
                ->orderBy('tanggal', 'ASC')
                ->get();
        }
        // dd($query);

        if ($query->isNotEmpty()) {
            foreach ($query as $key => $value) {
                $data['tanggal'][] = $value->tanggal;
                $data['penerimaan'][] = $value->penerimaan;
            }
        } else {
            $data['tanggal'][] = 0;
            $data['penerimaan'][] = 0;
        }
        return $data;
    }
    public function detail_penerimaan_harian($tanggal, $pajak, $kecamatan = null, $kelurahan = null)
    {
        $tanggal = $tanggal;
        $pajak = $pajak;
        $kecamatan = $kecamatan;
        $kelurahan = $kelurahan;
        return view("admin.pdl.detail_penerimaan_harian")->with(compact('tanggal', 'pajak', 'kecamatan', 'kelurahan'));
    }

    public function datatable_detail_penerimaan_harian(Request $request)
    {
        $tanggal = $request->tanggal;
        $pajak = $request->pajak;
        $kecamatan = $request->kecamatan;
        $kelurahan = $request->kelurahan;

        $whre_condition = "";
        if (!is_null($kecamatan) && is_null($kelurahan)) {
            $whre_condition = "and nama_kecamatan = '" . $kecamatan . "' and a.tanggal_diterima::date = '$tanggal'";
        } elseif (!is_null($kecamatan) && !is_null($kelurahan)) {
            $whre_condition = "and nama_kecamatan = '$kecamatan' and nama_desa = '$kelurahan' and a.tanggal_diterima::date = '$tanggal'";
        } else {
            $whre_condition = "and a.tanggal_diterima::date = '$tanggal'";
        }
        $select = "select 
        npwpd,  c.nama nama_op, c.jalan alamat_op, nama_wp, alamat_wp, masa_pajak_tahun, masa_pajak_bulan, masa_awal, masa_akhir, a.jumlah_pembayaran::int as nominal
        from data.tb_penerimaan a
        left join master.tb_jenis_pajak b
        on a.kode_akun_pajak::int = b.id
        left join data.tb_op c
        on a.nop = c.nop
        LEFT JOIN master.kecamatan kec ON C.kode_kecamatan = kec.kode_kecamatan
        LEFT JOIN master.desa des ON C.kode_desa = des.kode_desa 
        where ntpp is not null and a.deleted_at is null

        and b.nama_pajak= '$pajak'
        $whre_condition";
        //dd($select);
        $sismiop = DB::connection("pgsql_pdl")->select($select);
        //dd($sismiop);
        $arr = array();
        foreach ($sismiop as $key => $d) {
            $arr[] = array(
                "no" => $key + 1,
                "npwd" => $d->npwpd,
                "nama_op" => $d->nama_op,
                "nama_wp" => $d->nama_wp,
                "alamat_op" => $d->alamat_op,
                "alamat_wp" => $d->alamat_wp,
                "masa_pajak_tahun" => $d->masa_pajak_tahun,
                "masa_pajak_bulan" => $d->masa_pajak_bulan,
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
