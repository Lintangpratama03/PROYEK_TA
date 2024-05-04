<?php

namespace App\Http\Controllers\AdminRetribusi;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class RetribusiController extends Controller
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
            // dd("masuk");
            return view("admin_retribusi.retribusi.index");
        }
    }
    public function get_data()
    {
        // dd("masuk");
        $d_data = DB::table("data.retribusi")->orderby("nama_retribusi", "asc");

        $arr = array();
        foreach ($d_data->get() as $d) {
            $arr[] = [
                "id" => $d->id,
                "nama" => $d->nama_retribusi,
                "keterangan" => $d->keterangan,
                "kode_rekening" => $d->kode_rekening,
                "aksi" => "<div class='btn-group' role='group'></a> <button class='btn btn-icon btn-warning' type='button' data-id='" . $d->id . "' onclick='edit($(this))'><i class='fa fa-pencil-square-o'></i></button> <button class='btn btn-icon btn-danger' type='button' data-id='" . $d->id . "' onclick='hapus($(this))'><i class='fa fa-trash-o'></i></button></div>" .
                    '<input type="hidden" id="table_id' . $d->id . '" value="' . $d->id . '">' .
                    '<input type="hidden" id="table_keterangan' . $d->id . '" value="' . $d->keterangan . '">' .
                    '<input type="hidden" id="table_kode_rekening' . $d->id . '" value="' . $d->kode_rekening . '">' .
                    '<input type="hidden" id="table_nama' . $d->id . '" value="' . $d->nama_retribusi . '">'
            ];
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
        $insert['nama_retribusi'] = $request->get("popup_nama");
        $insert['keterangan'] = $request->get("popup_keterangan");
        $insert['kode_rekening'] = $request->get("popup_kode_rekening");

        if ($id == '') {
            DB::table('data.retribusi')->insert($insert);
        } else {
            DB::table("data.retribusi")->where("id", $id)->update($insert);
        }

        echo json_encode(["status" => '1']);
    }

    public function hapus(Request $request)
    {
        $id = $request->get("id");

        DB::table('data.retribusi')->where("id", $id)->delete();
        $d_data = DB::table('data.retribusi')->where("id", $id)->get()->count();

        if ($d_data == 0) {
            $arr = ['status' => 1, "keterangan" => "Data berhasil dihapus"];
        } else {
            $arr = ['status' => 0, "keterangan" => "Data gagal dihapus"];
        }

        echo json_encode($arr);
    }
}
