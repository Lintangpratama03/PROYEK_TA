<?php

namespace App\Http\Controllers\PBB;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

class TPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return view("admin.pbb.tempatbayar");
    }

    public function penerimaan_perbulan(Request $request)
    {
        $data['p2018'] = $this->get_penerimaan_perbulan('2018');
        $data['p2019'] = $this->get_penerimaan_perbulan('2019');
        $data['p2020'] = $this->get_penerimaan_perbulan('2020');
        $data['p2021'] = $this->get_penerimaan_perbulan('2021');
        $data['p2022'] = $this->get_penerimaan_perbulan('2022');
        $data['p2023'] = $this->get_penerimaan_perbulan('2023');

        // dd($data);
        return $data;
    }

    public function get_penerimaan_perbulan($thn)
    {
        $query = DB::table("data.penerimaan_perbulan_pbb")->where('tahun',$thn)->orderBy('bulan','ASC')->get();
        // dd($query);

        foreach ($query as $key => $value) {
            $data[] = $value->penerimaan_per_bulan;
        }
 
        return $data;
    }

    public function penerimaan_akumulasi(Request $request)
    {
        $data['p2018'] = $this->get_penerimaan_akumulasi('2018');
        $data['p2019'] = $this->get_penerimaan_akumulasi('2019');
        $data['p2020'] = $this->get_penerimaan_akumulasi('2020');
        $data['p2021'] = $this->get_penerimaan_akumulasi('2021');
        $data['p2022'] = $this->get_penerimaan_akumulasi('2022');
        $data['p2023'] = $this->get_penerimaan_akumulasi('2023');

        // dd($data);
        return $data;
    }

    public function get_penerimaan_akumulasi($thn)
    {
        $query = DB::table("data.penerimaan_akumulasi_pbb")->where('tahun',$thn)->orderBy('bulan','ASC')->get();
        // dd($query);

        foreach ($query as $key => $value) {
            $data[] = $value->penerimaan_akumulasi;
        }
 
        return $data;
    }

    function datatable_penerimaan_tahun(){
        $thn = '2023';
        $d_data = DB::table("data.penerimaan_tahun_sppt")->where('tahun_bayar',$thn)->groupBy('tahun_sppt')->groupBy('tahun_bayar')->orderBy('tahun_sppt','DESC')
        ->select(DB::raw('tahun_bayar, SUM(nominal_terima) AS nominal, SUM(nop) AS nop, tahun_sppt'))->get();
        // dd($d_data);
        $arr = array();
        if($d_data->count() > 0){
            foreach ($d_data as $key => $d) {
                # code...
                $arr[] = 
                    array(
                        "tahun"=>$d->tahun_sppt,
                        "nominal"=>rupiahFormat($d->nominal),
                        "nop"=> number_format($d->nop)
                    );
            }
            
        }

        return Datatables::of($arr)
        // ->rawColumns(['aksi','menu','background'])
        ->make(true);
    }

    public function penerimaan_harian(){
        $rekening = '4.';
        $query = DB::table("data.penerimaan_harian")->orderBy('tanggal','ASC')->get();
        // dd($query);
        foreach ($query as $key => $value) {
            $data['tanggal'][] = $value->tanggal;
            $data['penerimaan'][] = $value->penerimaan;
        }
        return $data;
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
