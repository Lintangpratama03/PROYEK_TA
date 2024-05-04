<?php

namespace App\Http\Controllers\Master;

use Auth;
use Redirect;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class GroupController extends Controller
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
            return view("master.group.index");
        }
    }

    public function get_data(){
    	$d_data = DB::table("auth.user_group")->orderby("nama_group", "asc");
        
    	$arr = array();
    	foreach ($d_data->get() as $d) {
    		$arr[] = ["id" => $d->id,
    				"nama" => $d->nama_group,
                    "nama_ditampilkan" => $d->nama_ditampilkan,
    				"aksi" => "<div class='btn-group' role='group'><a class='btn btn-icon btn-info' type='button' data-id='".$d->id."' href='".url('master/group/detail').'/'.$d->id."'><i class='fa fa-eye'></i></a> <button class='btn btn-icon btn-warning' type='button' data-id='".$d->id."' onclick='edit($(this))'><i class='fa fa-pencil-square-o'></i></button> <button class='btn btn-icon btn-danger' type='button' data-id='".$d->id."' onclick='hapus($(this))'><i class='fa fa-trash-o'></i></button></div>".
    					'<input type="hidden" id="table_id'.$d->id.'" value="'.$d->id.'">'.
                        '<input type="hidden" id="table_nama_ditampilkan'.$d->id.'" value="'.$d->nama_ditampilkan.'">'.
    					'<input type="hidden" id="table_nama'.$d->id.'" value="'.$d->nama_group.'">'];
    	}
        // dd($arr);
    	return Datatables::of($arr)
        ->rawColumns(['aksi'])
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
        $id = $request->get("popup_id");
    	$insert['nama_group'] = $request->get("popup_grup");
        $insert['nama_ditampilkan'] = $request->get("popup_nama_ditampilkan");

    	if($id == ''){
    		DB::table('auth.user_group')->insert($insert);
    	}else{
    		DB::table("auth.user_group")->where("id", $id)->update($insert);
    	}

    	echo json_encode(["status" => '1']);
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
        $id = $request->get("id");

    	DB::table('auth.user_group')->where("id", $id)->delete();
    	$d_data = DB::table('auth.user_group')->where("id", $id)->get()->count();

    	if($d_data == 0){
    		$arr = ['status' => 1, "keterangan" => "Data berhasil dihapus"];
    	}else{
    		$arr = ['status' => 0, "keterangan" => "Data gagal dihapus"];
    	}

    	echo json_encode($arr);
    }


    //< MAPPING MENU >//

    function detail($id){
        $data['id'] = $id;
        $d_data = DB::table("auth.user_group")->where("id", $id)->get();
        $count = $d_data->count();

        if($count > 0){
            $data['nama_group'] = $d_data->first()->nama_ditampilkan;
        }else{
            $data['nama_grup'] = "";
        }
        
        return view('master.group.detail')->with("data", $data);

    }

    function get_data_detail(Request $request){
        $id = $request->get('id');

        $d_data = DB::table("auth.user_menu AS um")->join("auth.menu AS m1", function($join){
            $join->on("m1.id", "um.child_id")
            ->on("m1.parent", "um.parent_id");
        })->leftjoin("auth.menu AS m2", "m2.id", "m1.parent")
        ->where("id_group", $id)
        ->orderby("m1.id", "asc")
        ->select(DB::raw("um.*, m1.name, m1.icon, m2.name AS parent_menu"));
        
        $arr = array();
        foreach ($d_data->get() as $d) {
            $icon = "";
            if($d->icon != '' || $d->icon != null){
                $icon = "<i class='".$d->icon."'></i> ".$d->icon;
            }
            $d->icon_html = $icon;
            $d->aksi = "<div class='btn-group' role='group'><button class='btn btn-icon btn-danger' type='button' data-id='".$d->id."' onclick='hapus($(this))'><i class='fa fa-trash-o'></i></button></div>";
            $arr[] = $d;
        }

        return Datatables::of($arr)
        ->rawColumns(['aksi', 'icon_html'])
        ->make(true);
    }

    function hapus_detail(Request $request){
        $id = $request->get("id");

        DB::table('auth.user_menu')->where("id", $id)->delete();
        $d_data = DB::table('auth.user_menu')->where("id", $id)->get()->count();

        if($d_data == 0){
            $arr = ['status' => 1, "keterangan" => "Data berhasil dihapus"];
        }else{
            $arr = ['status' => 0, "keterangan" => "Data gagal dihapus"];
        }

        echo json_encode($arr);

    }

    function get_menu(Request $request){
        $id = $request->get('id');
        // dd(_menuselect($id));
        print_r(_menuselect($id));
    }

    function simpan_menu(Request $request){
        $id = $request->get("popup_id");
        $insert['id_group'] = $request->get("popup_idgrup");
        $insert['child_id'] = $request->get("popup_grup");

        $d_menu = DB::table("auth.menu")->where("id", $insert['child_id'])->get();
        $count = $d_menu->count();

        if($count > 0){
            $d_first = $d_menu->first();
            $insert['parent_id'] = $d_first->parent;

            DB::table('auth.user_menu')->insert($insert);
        }

        echo json_encode(["status" => '1']);
    }

    public function select2Group(Request $request)
    {
        $searchs = (isset($request->param)) ? strtolower($request->param) : null;
        $result = DB::table("auth.user_group as ug")
        ->when($searchs, function($sql) use ($searchs){
            return $sql ->where(function($sql) use($searchs){
                return $sql ->whereRaw("LOWER(ug.nama_ditampilkan) like '%" . $searchs ."%'");
            });
        })            
        ->get();
        return response()->json($result);
    }
}
