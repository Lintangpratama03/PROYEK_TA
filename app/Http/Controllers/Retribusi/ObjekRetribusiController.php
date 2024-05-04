<?php

namespace App\Http\Controllers\Retribusi;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

class ObjekRetribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        return view("admin.retribusi.op");
    }

    public function pelaporan_pdl(Request $request)
    {

        $data['sudahbayar'] = $this->get_pelaporan_pdl('2023')['sudahbayar'];
        $data['sudahlapor'] = $this->get_pelaporan_pdl('2023')['sudahlapor'];
        $data['belumlapor'] = $this->get_pelaporan_pdl('2023')['belumlapor'];

        // dd($data);
        return $data;
    }

    public function get_pelaporan_pdl($thn)
    {
        $kode_rekening = "Pajak Hotel";
        $query = DB::table("data.pelaporan")->where('nama_rekening',$kode_rekening)->where('tahun',$thn)->orderBy('bulan','ASC')->get();
        // dd($query);
        foreach ($query as $key => $value) {
            $data['sudahbayar'][] = $value->lapor;
            $data['sudahlapor'][] = $value->belum_bayar;
            $data['belumlapor'][] = $value->belum_lapor;
        }
 
        return $data;
    }

    function datatable_kontribusi_op(){
        $thn = '2023';
        $d_data = DB::table("data.kontribusi_op_retribusi")->where('tahun',$thn)->orderBy('kontribusi','DESC')->get();
        // dd($d_data);
        $arr = array();
        if($d_data->count() > 0){
            foreach ($d_data as $key => $d) {
                # code...
                $arr[] = 
                    array(
                        "tahun"=>$d->tahun,
                        "nama_rekening"=>$d->nama_rekening,
                        "nama_objek_pajak"=>$d->nama_objek_pajak,
                        "kontribusi"=> rupiahFormat($d->kontribusi)
                    );
            }
            
        }

        return Datatables::of($arr)
        // ->rawColumns(['aksi','menu','background'])
        ->make(true);
    }

    public function penerimaan_harian(){
        $rekening = 'Pajak Hotel';
        $query = DB::table("data.penerimaan_harian")->where('nama_rekening',$rekening)->orderBy('tanggal','ASC')->get();
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
