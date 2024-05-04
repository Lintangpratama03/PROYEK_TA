<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use DB;
use Session;
use Illuminate\Support\Collection;

class WilayahController extends Controller
{
    public function select2Provinsi(Request $request)
    {
        $session = Session::get('user_app');
        $searchs = (isset($request->param)) ? strtolower($request->param) : null;
        $result = DB::table("master.provinsi as prov")
                    ->when($searchs, function($sql) use ($searchs){
                        return $sql ->whereRaw("LOWER(prov.nama_provinsi) like '%" . $searchs ."%'");
                    })
                    ->get();
        return response()->json($result);
    }

    public function select2Kabupaten(Request $request)
    {
        $session = Session::get('user_app');
        $searchs = (isset($request->param)) ? strtolower($request->param) : null;
        $id_prov = (isset($request->id_prov)) ? $request->id_prov : null ;
        $result = DB::table("master.kabupaten as kab")
                    ->when($id_prov, function($sql) use ($id_prov){
                        return $sql ->where('kab.kode_provinsi',$id_prov);
                    })
                    ->when($searchs, function($sql) use ($searchs){
                        return $sql ->where(function($sql) use($searchs){
                            return $sql ->whereRaw("LOWER(kab.nama_kabupaten) like '%" . $searchs ."%'");
                        });
                    })            
                    ->get();
        return response()->json($result);
    }


    public function select2Kecamatan(Request $request)
    {
        $session = Session::get('user_app');
        $searchs = (isset($request->param)) ? strtolower($request->param) : null;
        $id_kab = (isset($request->id_kab)) ? $request->id_kab : null ;
        $result = DB::table("master.kecamatan as kec")
                    ->when($id_kab, function($sql) use ($id_kab){
                        return $sql ->where('kec.kode_kabupaten',$id_kab);
                    })
                    ->when($searchs, function($sql) use ($searchs){
                        return $sql ->where(function($sql) use($searchs){
                            return $sql ->whereRaw("LOWER(kec.nama_kecamatan) like '%" . $searchs ."%'");
                        });
                    })            
                    ->get();
        return response()->json($result);
    }

    public function select2Desa(Request $request)
    {
        $session = Session::get('user_app');
        $searchs = (isset($request->param)) ? strtolower($request->param) : null;
        $id_kec = (isset($request->id_kec)) ? $request->id_kec : null ;
        $result = DB::table("master.desa as des")
                    ->when($id_kec, function($sql) use ($id_kec){
                        return $sql ->where('des.kode_kecamatan',$id_kec);
                    })
                    ->when($searchs, function($sql) use ($searchs){
                        return $sql ->where(function($sql) use($searchs){
                            return $sql ->whereRaw("LOWER(des.nama_desa) like '%" . $searchs ."%'");
                        });
                    })            
                    ->get();
        return response()->json($result);
    }

}
