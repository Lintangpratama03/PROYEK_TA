<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
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
            return view("master.user.index");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataTables(Request $request){
        $data = array();

        $result = DB::table("auth.user_account as ua")
        ->join("auth.user_group as ug","ug.id","ua.id_group")
        ->whereNull("ua.deleted_at")
        ->selectRaw("
            ua.*,
            ug.nama_ditampilkan as nama_group
        ")
        ->get();

        foreach ($result as $key => $value) {

            $is_aktif = ($value->is_aktif==1)?"<span class='badge badge-success'>Aktif</span>":"<span class='badge badge-secondary'>Tidak Aktif</span>";
            $row = array(
                "id" => encrypt($value->id),
                "no" => $key+1,
                "npwp" => $value->npwpd,
                "nama" => $value->nama,
                "username" => $value->username,
                "aktif" => $value->is_aktif,
                "is_aktif" => "<center>$is_aktif</center>",
                "nama_group" => $value->nama_group
            );

            array_push($data,$row);
        }
        // dd($data);
        return DataTables::of($data)
        ->rawColumns(['is_aktif','action'])
        ->addColumn('action', 'master.user.datatables_actions')
        ->make(true);

        return $result;
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
        // dd($request);
        $response = ReturnResponse("000");
        
        try {
            
            DB::beginTransaction();
            $sessions = getSession();
            $user_id = decrypt($sessions['user_id']);

            $data_user = [
                "nama" => $request->nama,
                "username" => $request->username,
                "password" => Hash::make($request->password),
                "is_aktif" => 1,
                "id_group" => $request->group_id,
                "created_by" => $user_id
            ];

            DB::table("auth.user_account")
            ->insert($data_user);
    
            $response = ReturnResponse("101");
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
            // $response = CustomResponse("error","$th");
            $response = ReturnResponse("001");
        }

        DB::commit();

        return response()->json(compact("response"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id_user = decrypt($request->id_user);

        $result = DB::table("auth.user_account as ua")
        ->join("auth.user_group as ug","ug.id","ua.id_group")
        ->where("ua.id",$id_user)
        ->selectRaw("
            ua.*,
            ug.nama_group
        ")
        ->first();
        
        $result->id = encrypt($result->id);

        return response()->json(compact("result"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id_user = decrypt($request->id_user);

        $result = DB::table("auth.user_account as ua")
        ->join("auth.user_group as ug","ug.id","ua.id_group")
        ->where("ua.id",$id_user)
        ->selectRaw("
            ua.*,
            ug.nama_ditampilkan as nama_group
        ")
        ->first();
        // dd($result);
        $result->id = encrypt($result->id);

        return response()->json(compact("result"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $response = ReturnResponse("000");
        // dd($request->all());
        try {
            DB::beginTransaction();

            if ($request->password) { // jika password diubah maka akan disimpan, jika tidak diisi maka password tidak akan diupdate.
                $password = Hash::make($request->password);
                $data['password'] = $password;
            }

            $id_user = decrypt($request->id_user);
            $data['nama'] = $request->nama;
            $data['username'] = $request->username;
            $data['is_aktif'] = 1;
            $data['id_group'] = $request->group_id;
            
            $id_user = DB::table("auth.user_account")
            ->where("id",$id_user)
            ->update($data);

            $response = ReturnResponse("102");

        } catch (\Throwable $th) {
            return $th;
            $response = ReturnResponse("002");
        }

        DB::commit();

        return response()->json(compact("response"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $response = ReturnResponse("000");
        
        try {
            DB::beginTransaction();

            $now = date('Y-m-d H:i:s');
            $id_user = decrypt($request->id_user); 
            $data['deleted_at'] = $now;

            DB::table("auth.user_account")
            ->where("id",$id_user)
            ->update($data);

            $response = ReturnResponse("103");

        } catch (\Throwable $th) {
            DB::rollBack();
            // return $th;
            $response = ReturnResponse("003");
        }

        DB::commit();

        return response()->json(compact("response"));
    }

    public function banned(Request $request)
    {
        $response = ReturnResponse("000");
        try {
            DB::beginTransaction();

            $now = date('Y-m-d H:i:s');
            $id_user = decrypt($request->id_user); 
            $data['updated_at'] = $now;
            $data['is_aktif'] = '0';

            DB::table("auth.user_account")->where("id",$id_user)->update($data);
            $response = ReturnResponse("130");

        } catch (\Throwable $th) {
            DB::rollBack();
            // return $th;
            $response = ReturnResponse("003");
        }
        DB::commit();
        return response()->json(compact("response"));
    }

    public function unbanned(Request $request)
    {
        $response = ReturnResponse("000");
        try {
            DB::beginTransaction();

            $now = date('Y-m-d H:i:s');
            $id_user = decrypt($request->id_user); 
            $data['updated_at'] = $now;
            $data['is_aktif'] = '1';

            DB::table("auth.user_account")->where("id",$id_user)->update($data);
            $response = ReturnResponse("131");

        } catch (\Throwable $th) {
            DB::rollBack();
            // return $th;
            $response = ReturnResponse("003");
        }
        DB::commit();
        return response()->json(compact("response"));
    }
}
