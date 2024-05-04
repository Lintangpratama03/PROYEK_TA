<?php

namespace App\Http\Controllers\AdminRetribusi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class OpdController extends Controller
{
    public function index()
    {
        return view("admin_retribusi.opd.index");
    }

    public function dataTables(Request $request)
    {
        $data = array();

        $result = DB::table("auth.user_account as ua")
            ->join("auth.user_group as ug", "ug.id", "ua.id_group")
            ->whereNull("ua.deleted_at")
            ->where("id_group", 5)
            ->selectRaw("
            ua.*,
            ug.nama_ditampilkan as nama_group
        ")
            ->get();

        foreach ($result as $key => $value) {

            $is_aktif = ($value->is_aktif == 1) ? "<span class='badge badge-success'>Aktif</span>" : "<span class='badge badge-secondary'>Tidak Aktif</span>";
            $row = array(
                "id" => encrypt($value->id),
                "no" => $key + 1,
                "nama" => $value->nama,
                "username" => $value->username,
                "aktif" => $value->is_aktif,
                "is_aktif" => "<center>$is_aktif</center>",
                "nama_group" => $value->nama_group
            );

            array_push($data, $row);
        }
        // dd($data);
        // foreach ($data as $key => $value) {
        //     $data[$key]['id'] = decrypt($value['id']);
        // }
        return DataTables::of($data)
            ->rawColumns(['is_aktif', 'retribusi', 'action'])
            ->addColumn('retribusi', 'admin_retribusi.opd.datatables_retribusi')
            ->addColumn('action', 'admin_retribusi.opd.datatables_actions')
            ->make(true);

        return $result;
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
                "id_group" => 5,
                "created_by" => $user_id
            ];

            $user_id = DB::table("auth.user_account")->insertGetId($data_user);

            $data_opd = [
                "id_opd" => $user_id,
                "nama_opd" => $request->nama,
                "ket_opd" => $request->keterangan,
                "alamat_opd" => $request->alamat
            ];

            DB::table("data.opd")->insert($data_opd);

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

    public function show(Request $request)
    {
        $id_user = decrypt($request->id_user);
        $result = DB::table("auth.user_account as ua")
            ->join("auth.user_group as ug", "ug.id", "ua.id_group")
            ->leftjoin("data.opd as a", "a.id_opd", "ua.id")
            ->where("ua.id", $id_user)
            ->selectRaw("
            ua.*,
            ug.nama_group,
            a.*
        ")
            ->first();

        $result->id = encrypt($result->id);

        return response()->json(compact("result"));
    }

    public function edit(Request $request)
    {
        $id_user = decrypt($request->id_user);

        $result = DB::table("auth.user_account as ua")
            ->join("auth.user_group as ug", "ug.id", "ua.id_group")
            ->leftjoin("data.opd as a", "a.id_opd", "ua.id")
            ->where("ua.id", $id_user)
            ->selectRaw("
            ua.*,
            ug.nama_group,
            a.*
        ")
            ->first();
        // dd($result);
        $result->id = encrypt($result->id);

        return response()->json(compact("result"));
    }

    public function update(Request $request)
    {
        //dd($request->all());
        $response = ReturnResponse("000");
        try {
            DB::beginTransaction();

            if ($request->password) {
                $password = Hash::make($request->password);
                $data['password'] = $password;
            }

            $id_user = decrypt($request->id_user);
            $data['nama'] = $request->nama;
            $data['username'] = $request->username;
            $data['is_aktif'] = 1;

            $id_user = DB::table("auth.user_account")
                ->where("id", $id_user)
                ->update($data);


            $detail['ket_opd'] = $request->keterangan;
            $detail['alamat_opd'] = $request->alamat;

            $id_user = DB::table("data.opd")
                ->where("id_opd", $id_user)
                ->update($detail);

            $response = ReturnResponse("102");
        } catch (\Throwable $th) {
            return $th;
            $response = ReturnResponse("002");
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

            DB::table("auth.user_account")->where("id", $id_user)->update($data);
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

            DB::table("auth.user_account")->where("id", $id_user)->update($data);
            $response = ReturnResponse("131");
        } catch (\Throwable $th) {
            DB::rollBack();
            // return $th;
            $response = ReturnResponse("003");
        }
        DB::commit();
        return response()->json(compact("response"));
    }

    //< MAPPING MENU >//

    function detail($id)
    {
        $data['id'] = $id;
        $d_data = DB::table("data.opd")->where("id_opd", $id)->get();
        $count = $d_data->count();

        if ($count > 0) {
            $data['nama_opd'] = $d_data->first()->nama_opd;
        } else {
            $data['nama_grup'] = "";
        }

        return view('admin_retribusi.opd.detail')->with("data", $data);
    }

    function get_data_detail(Request $request)
    {
        $id = $request->get('id');

        $d_data = DB::table("data.retribusi_opd AS um")
            ->leftjoin("data.opd AS m1", "m1.id_opd", "um.id_opd")
            ->leftjoin("data.retribusi AS m2", "m2.id", "um.id_retribusi")
            ->where("um.id_opd", $id)
            ->select(DB::raw("um.*, m1.nama_opd, m2.nama_retribusi, m2.keterangan"));

        $arr = array();
        foreach ($d_data->get() as $d) {
            $d->nama_retribusi = $d->nama_retribusi;
            $d->keterangan = $d->keterangan;
            $d->aksi = "<div class='btn-group' role='group'><button class='btn btn-icon btn-danger' type='button' data-id='" . $d->id . "' onclick='hapus($(this))'><i class='fa fa-trash-o'></i></button></div>";
            $arr[] = $d;
        }

        return Datatables::of($arr)
            ->rawColumns(['aksi', 'nama_retribusi', 'keterangan'])
            ->make(true);
    }

    function hapus_detail(Request $request)
    {
        $id = $request->get("id");

        DB::table('data.retribusi_opd')->where("id", $id)->delete();
        $d_data = DB::table('data.retribusi_opd')->where("id", $id)->get()->count();

        if ($d_data == 0) {
            $arr = ['status' => 1, "keterangan" => "Data berhasil dihapus"];
        } else {
            $arr = ['status' => 0, "keterangan" => "Data gagal dihapus"];
        }

        echo json_encode($arr);
    }

    function get_retribusi(Request $request)
    {
        $id = $request->get('id');
        //dd(_menuselect($id));
        print_r(_retribusiselect($id));
    }

    function simpan_retribusi(Request $request)
    {
        $id = $request->get("popup_id");
        $insert['id_opd'] = $request->get("popup_idgrup");
        $insert['id_retribusi'] = $request->get("popup_grup");

        $d_menu = DB::table("data.retribusi")->where("id", $insert['id_retribusi'])->get();
        // dd($d_menu);
        $count = $d_menu->count();

        if ($count > 0) {
            $d_first = $d_menu->first();
            $insert['id_retribusi'] = $d_first->id;
            // dd($insert);
            DB::table('data.retribusi_opd')->insert($insert);
        }

        echo json_encode(["status" => '1']);
    }

    public function select2Group(Request $request)
    {
        $searchs = (isset($request->param)) ? strtolower($request->param) : null;
        $result = DB::table("data.retribusi as ug")
            ->when($searchs, function ($sql) use ($searchs) {
                return $sql->where(function ($sql) use ($searchs) {
                    return $sql->whereRaw("LOWER(ug.nama_retribusi) like '%" . $searchs . "%'");
                });
            })
            ->get();
        return response()->json($result);
    }
}
