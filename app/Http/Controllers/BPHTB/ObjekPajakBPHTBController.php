<?php

namespace App\Http\Controllers\BPHTB;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

class ObjekPajakBPHTBController extends Controller
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
            $currentYear = Carbon::now()->year;
            return view("admin.bphtb.op", compact('currentYear'));
        }
    }

    public function get_total_nop(Request $request)
    {
        $tahun = $request->tahun;
        $result = DB::connection("pgsql_bphtb")
            ->table('bphtb_bank')
            ->selectRaw('count(distinct nop) as jumlah_nop')
            ->whereRaw("extract(year from tanggal) = $tahun")
            ->first();
        return $result->jumlah_nop;
    }

    public function get_total_notaris(Request $request)
    {
        $tahun = $request->tahun;
        $result = DB::connection("pgsql_bphtb")
            ->table('bphtb_bank')
            ->selectRaw('count(distinct notaris) as jumlah_notaris')
            ->whereRaw("extract(year from tanggal) = $tahun")
            ->first();
        return $result->jumlah_notaris;
    }

    function datatable_kontribusi_op(Request $request)
    {
        $tahun = $request->tahun;
        $rawQuery = "
        SELECT extract(year from tanggal) as tahun, notaris, sum(bayar-denda) as penerimaan FROM bphtb_bank
        where extract(year from tanggal)= $tahun
        group by extract(year from tanggal), notaris
        order by sum(bayar-denda) desc
        limit 50
        ";
        $d_data = db::connection("pgsql_bphtb")->select($rawQuery);
        $arr = array();
        foreach ($d_data as $key => $d) {
            $arr[] =
                array(
                    "tahun" => $d->tahun,
                    "notaris" => $d->notaris,
                    "penerimaan" => rupiahFormat($d->penerimaan)
                );
        }

        // }

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
