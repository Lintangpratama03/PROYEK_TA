<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

class MenuController extends Controller
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
            $data['menu'] = get_menu()->orderby("menu.urutan", "asc")->orderby("menu.parent", "asc")->orderby("menu.id", "asc")->get();
    
            return view("master.menu.index")->with('data',$data);
        }
    }

    function get_data(){

        $d_data = get_menu()->orderby("menu.urutan","asc")->orderby("menu.parent","asc")->orderby("menu.id","asc");
        // dd($d_data->get());
        $arr = array();
        if($d_data->count() > 0){
            foreach ($d_data->get() as $key => $d) {
                # code...
                $arr[] = array("id"=>$d->id,
                               "menu"=>"<i data-feather='".$d->icon."'></i> ".$d->name,
                               "link"=>$d->url,
                               "icon"=>$d->icon,
                               "parent"=>$d->parent_menu,
                            //    "tipe"=>$d->tipe_site,
                               "urutan"=>$d->urutan,
                               "background"=> "<button class='btn btn-icon btn-info' type='button' data-image='".$d->background."' onclick='show_image_background(this)'><i class='fa fa-eye'></i> " .$d->background." </button> ",
                               "aksi"=>"<div class='btn-group' role='group'><button class='btn btn-icon btn-warning' type='button' data-id='".$d->id."' onclick='edit(".$d->id.")'><i class='fa fa-pencil-square-o'></i></button> <button class='btn btn-icon btn-danger' type='button' data-id='".$d->id."' onclick='hapus(".$d->id.")'><i class='fa fa-trash-o'></i></button></div>
                               <input type='hidden' value='".$d->id."' name='table_id[]' id='table_id".$d->id."'>
                               <input type='hidden' value='".$d->name."' name='table_nama[]' id='table_nama".$d->id."'>
                               <input type='hidden' value='".$d->url."' name='table_url[]' id='table_url".$d->id."'>
                               <input type='hidden' value='".$d->icon."' name='table_icon[]' id='table_icon".$d->id."'>
                               <input type='hidden' value='".$d->parent."' name='table_parent[]' id='table_parent".$d->id."'>
                               <input type='hidden' value='".$d->urutan."' name='table_urutan[]' id='table_urutan".$d->id."'>
                               ");
            }
            
        }

        return Datatables::of($arr)
        ->rawColumns(['aksi','menu','background'])
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
        $status = ["status" => '1'];
        $id = $request->get('popup_id');

        $d_menu = DB::table("auth.menu")->where("id", $id)->get();
        $count = $d_menu->count();
        $file_sebelum = '';
        if($count > 0){
            $file_sebelum = $d_menu->first()->background;
        }
        // dd($request->foto_background);
        $count_file = ($request->foto_background != '') ? "1":"";
        $nama_file = '';
        if($count_file > 0){
            $path = "images_background_menu/";
            if (!is_dir($path )) {
                mkdir($path, 0777, true);
            }
            // $data_foto = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->get('foto_background')));
            // $nama_file = date("YmdHis").".png";
            $file = $request->file('foto_background');
            $nama_file = date("YmdHis").".".$file->getClientOriginalExtension();
            
            if($file_sebelum != ''){
                if(file_exists($path.$file_sebelum)){
                    unlink($path.$file_sebelum);
                }
            }
            // file_put_contents($path.$nama_file, $data_foto);
            $file->move($path,$nama_file);
            $data['background'] = $nama_file;
        }

        // $data['tipe_site'] = $request->get('popup_aktif');
        $data['name']   = $request->get('popup_name');
        $data['url']    = $request->get('popup_url');
        $data['icon']   = $request->get('popup_icon');
        $data['parent'] = $request->get('popup_parent');
        $data['urutan'] = $request->get('popup_urutan');

        DB::beginTransaction();
        try {
            if($id == ''){
                DB::table('auth.menu')->insert($data);
                $status = "1";
            }else{
                DB::table('auth.menu')->where(array('id' => $id))->update($data);
                $status = "2";
            }
            DB::commit();   
            // echo json_encode($status);
            return response()->json([
                'status' => $status
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            // echo json_encode(["status" => '0']);
            return response()->json([
                'status' => '0'
            ]);
        }
        // return $data;
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
    public function hapus(Request $request)
    {
        $id = $request->get('id');
        $query = DB::table('auth.menu')->where(['id'=>$id])->delete();

        $d_data = DB::table('auth.menu')->where("id", $id)->get()->count();
        
    	if($d_data == 0){
    		$arr = ['status' => 1, "keterangan" => "Data berhasil dihapus"];
    	}else{
    		$arr = ['status' => 0, "keterangan" => "Data gagal dihapus"];
    	}

    	echo json_encode($arr);
    }
}
