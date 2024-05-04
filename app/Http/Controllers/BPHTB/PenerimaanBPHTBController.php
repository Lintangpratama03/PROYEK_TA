<?php

namespace App\Http\Controllers\BPHTB;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class PenerimaanBPHTBController extends Controller
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
            return view("admin.bphtb.penerimaan");
        }
    }

    public function penerimaan_perbulan(Request $request)
    {
        $tahun = $request->input('tahun', []);
        $bulan = $request->input('bulan', []);

        $yearnow = date('Y');
        $lastyear = $yearnow - 1;
        if (count($tahun) == 0) {
            $tahun = array($yearnow, (string)$lastyear);
        }
        if (count($bulan) == 0) {
            $bulan = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
        }

        $data = $this->get_penerimaan_perbulan($tahun, $bulan);
        // $data['p2023'] = $this->get_penerimaan_perbulan('2023');
        // dd($data);
        return $data;
    }

    public function get_penerimaan_perbulan($tahun, $bulan)
    {
        // $kode_rekening = "Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)";
        // $query = DB::table("data.penerimaan_bulanan")
        // ->whereIn('tahun',$tahun)->whereIn('bulan',$bulan)
        // ->orderBy('bulan','ASC')->get();
        // dd($query);

        if ((count($tahun) == 0) && (count($bulan) == 0)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->selectRaw("
                tahun,
                bulan,
                sum(penerimaan_per_bulan) as penerimaan_per_bulan")
                ->where('sumber_data', 'BPHTB')
                ->groupby('tahun')
                ->groupby('bulan')
                ->orderBy('bulan', 'ASC')->get();
        } elseif ((count($tahun) != 0) && (count($bulan) == 0)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->selectRaw("
                tahun,
                bulan,
                sum(penerimaan_per_bulan) as penerimaan_per_bulan")
                ->where('sumber_data', 'BPHTB')
                ->whereIn('tahun', $tahun)
                ->groupby('tahun')
                ->groupby('bulan')
                ->orderBy('bulan', 'ASC')->get();
        } elseif ((count($tahun) == 0) && (count($bulan) != 0)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->selectRaw("
                tahun,
                bulan,
                sum(penerimaan_per_bulan) as penerimaan_per_bulan")
                ->where('sumber_data', 'BPHTB')
                ->whereIn('bulan', $bulan)
                ->groupby('tahun')
                ->groupby('bulan')
                ->orderBy('bulan', 'ASC')->get();
        } else {
            $query = DB::table("data.penerimaan_bulanan")
                ->selectRaw("
                tahun,
                bulan,
                sum(penerimaan_per_bulan) as penerimaan_per_bulan")
                ->where('sumber_data', 'BPHTB')
                ->whereIn('bulan', $bulan)
                ->whereIn('tahun', $tahun)
                ->groupby('tahun')
                ->groupby('bulan')
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
            $data['bulan'] = 0;
        }
        //dd($data);
        return $data;
    }

    public function penerimaan_akumulasi(Request $request)
    {
        // dd($request->all());
        $tahun = $request->input('tahun', []);
        $bulan = $request->input('bulan', []);

        $yearnow = date('Y');
        $lastyear = $yearnow - 1;
        if (count($tahun) == 0) {
            $tahun = array($yearnow, (string)$lastyear);
        }
        if (count($bulan) == 0) {
            $bulan = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
        }

        $data = $this->get_penerimaan_akumulasi($tahun, $bulan);
        // dd($data);
        return $data;
    }

    public function get_penerimaan_akumulasi($tahun, $bulan)
    {
        // $kode_rekening = "Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)";
        // $query = DB::table("data.penerimaan_bulanan")
        // ->whereIn('tahun',$tahun)->whereIn('bulan',$bulan)
        // ->orderBy('bulan','ASC')->get();

        if ((count($tahun) == 0) && (count($bulan) == 0)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->selectRaw("
                tahun,
                bulan,
                sum(penerimaan_akumulasi) as penerimaan_akumulasi")
                ->where('sumber_data', 'BPHTB')
                ->groupby('tahun')
                ->groupby('bulan')
                ->orderBy('bulan', 'ASC')->get();
        } elseif ((count($tahun) != 0) && (count($bulan) == 0)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->selectRaw("
                tahun,
                bulan,
                sum(penerimaan_akumulasi) as penerimaan_akumulasi")
                ->where('sumber_data', 'BPHTB')
                ->whereIn('tahun', $tahun)
                ->groupby('tahun')
                ->groupby('bulan')
                ->orderBy('bulan', 'ASC')->get();
        } elseif ((count($tahun) == 0) && (count($bulan) != 0)) {
            $query = DB::table("data.penerimaan_bulanan")
                ->selectRaw("
                tahun,
                bulan,
                sum(penerimaan_akumulasi) as penerimaan_akumulasi")
                ->where('sumber_data', 'BPHTB')
                ->whereIn('bulan', $bulan)
                ->groupby('tahun')
                ->groupby('bulan')
                ->orderBy('bulan', 'ASC')->get();
        } else {
            $query = DB::table("data.penerimaan_bulanan")
                ->selectRaw("
                tahun,
                bulan,
                sum(penerimaan_akumulasi) as penerimaan_akumulasi")
                ->where('sumber_data', 'BPHTB')
                ->whereIn('bulan', $bulan)
                ->whereIn('tahun', $tahun)
                ->groupby('tahun')
                ->groupby('bulan')
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
                array_push($bulanText, getMonth($value));
            }
            $data['bulan'] = $bulanText;
        } else {
            $data['penerimaan'] = [];
            $data['bulan'] = [];
        }
        //dd($data);
        return $data;
    }

    function datatable_penerimaan_tahun()
    {
        $thn = date('Y');
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

    public function penerimaan_harian(Request $request)
    {
        $tanggal = $request->tanggal;
        $rangeDate = explode(" - ", $tanggal);
        $startDate = Carbon::parse($rangeDate[0])->format('Y-m-d');
        $endDate = Carbon::parse($rangeDate[1])->format('Y-m-d');

        $rekening = 'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)';
        $query = DB::table("data.penerimaan_harian")
            ->selectRaw('tanggal,sum(penerimaan) as penerimaan')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('sumber_data', 'BPHTB')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
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


    public function datatable_penerimaan_notaris()
    {

        $query = DB::table("data.penerimaan_notaris")->orderBy('notaris', 'ASC')->orderBy('tahun', 'DESC')->orderBy('bulan', 'DESC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "notaris" => $d->notaris,
                        "tahun" => $d->tahun,
                        "bulan" => $d->bulan,
                        "jumlah_transaksi" => number_format($d->jumlah_transaksi),
                        "nominal" => rupiahFormat($d->nominal)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }



    public function detail_penerimaan_perbulan($tahun, $bulan)
    {
        $tahun = $tahun;
        $bulan = $bulan;
        // dd($kelurahan);
        return view("admin.bphtb.detail_penerimaan_perbulan")->with(compact('tahun', 'bulan'));
    }

    public function datatable_detail_penerimaan_perbulan(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $tgl = '"TANGGALBAYAR"';
        $npop = '"NPOP"';
        $namawp = '"NAMAWP"';
        $alamatwp = '"ALAMATWP"';
        $NAMAPPAT = '"NAMAPPAT"';
        $jml = '"JUMLAHHARUSDIBAYAR"';
        $delet = '"DELETED_AT"';

        $select = "SELECT 
        $npop, $namawp, $alamatwp, $NAMAPPAT, $jml AS PENERIMAAN
        FROM tb_sspd_bphtb
        WHERE
        $delet IS NULL AND $tgl IS NOT NULL AND $jml > 0
        AND EXTRACT(YEAR FROM $tgl) = '$tahun'
        AND EXTRACT(MONTH FROM $tgl) = '$bulan'";
        //    dd($getToQl());

        $sismiop = DB::connection("pgsql_bphtb")->select($select);
        $arr = array();
        if (count($sismiop) > 0) {
            foreach ($sismiop as $key => $d) {
                // dd($key);
                foreach ($sismiop as $key => $d) {
                    $arr[] = array(
                        "no" => $key + 1,
                        "NPOP" => $d->NPOP,
                        "NAMAWP" => $d->NAMAWP,
                        "ALAMATWP" => $d->ALAMATWP,
                        "NAMAPPAT" => $d->NAMAPPAT,
                        "PENERIMAAN" => $d->penerimaan,
                    );
                }
            }
        }

        return Datatables::of($arr)
            ->make(true);
    }


    public function detail_penerimaan_harian($tanggal)
    {
        // dd($kelurahan, $tanggal);
        $tanggal = $tanggal;
        // dd($kelurahan);
        return view("admin.bphtb.detail_penerimaan_harian")->with(compact('tanggal'));
    }

    public function datatable_detail_penerimaan_harian(Request $request)
    {
        $tanggal = $request->tanggal;

        $tgl = '"TANGGALBAYAR"';
        $npop = '"NPOP"';
        $namawp = '"NAMAWP"';
        $alamatwp = '"ALAMATWP"';
        $NAMAPPAT = '"NAMAPPAT"';
        $jml = '"JUMLAHHARUSDIBAYAR"';
        $delet = '"DELETED_AT"';

        $select = "SELECT 
        $npop, $namawp, $alamatwp, $NAMAPPAT, $jml AS PENERIMAAN
        FROM tb_sspd_bphtb
        WHERE
        $delet IS NULL AND $tgl IS NOT NULL AND $jml > 0
        AND $tgl::date = '$tanggal'";
        $sismiop = DB::connection("pgsql_bphtb")->select($select);
        $arr = array();
        if (count($sismiop) > 0) {
            foreach ($sismiop as $key => $d) {
                $arr[] = array(
                    "no" => $key + 1,
                    "NPOP" => $d->NPOP,
                    "NAMAWP" => $d->NAMAWP,
                    "ALAMATWP" => $d->ALAMATWP,
                    "NAMAPPAT" => $d->NAMAPPAT,
                    "PENERIMAAN" => $d->penerimaan,
                );
            }
        }

        return Datatables::of($arr)
            ->make(true);
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
