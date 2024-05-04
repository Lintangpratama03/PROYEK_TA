<?php

namespace App\Http\Controllers\Retribusi;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RetribusiOPDController extends Controller
{
    public function index()
    {
        return view("admin_retribusi.opd.retribusi_opd");
    }

    public function realisasi_index()
    {
        return view("admin_retribusi.opd.target_realisasi");
    }


    public function datatable_target_realisasi_opd(){

        $session =  Session::get("user_app");
        $id = decrypt($session['user_id']);
        $qr = "
        SELECT dp.id,
        dp.target_murni,
        dp.target_perubahan,
        dp.tahun,
        rt.nama_retribusi as id_retribusi_opd,
                    dp.id_opd,
        COALESCE(dr.jumlah, 0) AS jumlah,
        COALESCE(dr.total_realisasi, 0) AS total_realisasi 
    FROM data.target_opd AS dp
    LEFT JOIN (
        SELECT target_opd_id,
                COUNT(CASE WHEN realisasi IS NOT NULL AND realisasi != 0 THEN realisasi END) AS jumlah,
                SUM(CASE WHEN realisasi IS NOT NULL AND realisasi != 0 THEN realisasi ELSE 0 END) AS total_realisasi
        FROM data.detail_realisasi
        GROUP BY target_opd_id
    ) AS dr ON dr.target_opd_id = dp.id
            LEFT join data.retribusi_opd as ro on ro.id = dp.id_retribusi_opd
            LEFT join data.retribusi as rt on rt.id = ro.id_retribusi
            where dp.id_opd = $id;
    
        ";
        $query = DB::select($qr);
        // $result = $query->get();
        
        // dd($query);
        $arr = array();
        if ($query) {
            $no = 0;
            foreach ($query as $key => $data) {
                $action = '
                    <a href="' . url("retribusi/realisasi/detail_target_realisasi/$data->id") . '" class="btn btn-primary">
                        <i class="fa fa-eye me-2" aria-hidden="true"></i>
                        Detail
                    </a>
                ';
                // $action = 'kosong';
                $no += 1;
                $arr[] = array(
                    "no" => $no,
                    "tahun" => $data->tahun,
                    "id_retribusi_opd" => $data->id_retribusi_opd,
                    "jumlah" => $data->jumlah,
                    "action" => $action,
                    "total_realisasi" => rupiahFormat($data->total_realisasi),
                    "target" => rupiahFormat($data->target_perubahan ? $data->target_perubahan : $data->target_murni),
                    "persen" => round(($data->total_realisasi / ($data->target_perubahan ? $data->target_perubahan : $data->target_murni)) * 100,2)
                );
            }
        }

        return Datatables::of($arr)
            ->rawColumns(['action'])
            ->make(true);
    }

    public function detail_target_realisasi($id){
        $id_opd = $id;
        return view('admin_retribusi.opd.detail_target_realisasi',compact('id_opd'));
    }
    public function datatable_detail_target_realisasi(Request $request){

        $id = (int)$request->id;
        // dd($id);
        $bulan = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
        $qr = "
            select * from data.detail_realisasi where target_opd_id = $id order by bulan
        ";
        $query = DB::select($qr);
        $getMonthIndex = function($month) use ($bulan) {
            return array_search(strtolower($month), $bulan);
        };
        usort($query, function($a, $b) use ($getMonthIndex) {
            $bulanA = $getMonthIndex($a->bulan);
            $bulanB = $getMonthIndex($b->bulan);
            return $bulanA - $bulanB;
        });
        // dd($query);
        return response()->json([
            "data" => $query
        ]);
    }

    public function update_target_realisasi(Request $request)
    {
        $data = $request->all();
        foreach ($data as $key => $value) {
            foreach ($value as $key2 => $detail) {
                // dd($detail);
                $id = $detail['id'];
                $bulan = $detail['bulan'];
                $realisasi = $detail['realisasi'];
                $lampiran = $detail['lampiran'] ?? null;

                DB::table('data.detail_realisasi')->where('id', $id)->update([
                    'bulan' => $bulan,
                    'realisasi' => $realisasi,
                ]);
                if ($lampiran != null || $lampiran != "undifined") {
                    $file = $request->file("data")[$key2]["lampiran"] ?? null; 
                    if ($file) {
                        $existingFile = DB::table('data.detail_realisasi')->where('id', $id)->value('lampiran');
                        if (!is_null($existingFile)) {
                            Storage::disk('public')->delete('realisasi/' . $existingFile);
                        }
                        $filename = $file->getClientOriginalName();
                        $path = $file->storeAs('realisasi', $filename, 'public');
                        DB::table('data.detail_realisasi')->where('id', $id)->update([
                            'lampiran' => $filename,
                        ]);
                    }
                }
            }
        }
    
        return response()->json([
            "success" => "Data berhasil diupdate !",
        ]);
    }

    
    public function datatable_kontribusi_op(){

        $session =  Session::get("user_app");
        $id = decrypt($session['user_id']);
        $query = DB::table("data.target_opd as tp")
        ->select(
            'tp.id as id',
            'rt.nama_retribusi as id_retribusi_opd',
            'tp.target_murni as target_murni',
            'tp.target_perubahan as target_perubahan',
            'tp.tahun as tahun',
            'tp.id_opd'
        )
        ->leftJoin("data.retribusi_opd as ro", "ro.id", "=", "tp.id_retribusi_opd")
        ->leftJoin("data.retribusi as rt", "rt.id", "=", "ro.id_retribusi")
        ->where("tp.id_opd", "=", $id);
        $result = $query->get();

        // dd($result);
        
        $arr = array();
        if ($result->count() > 0) {
            $no = 0;
            foreach ($result as $key => $data) {

                // $action = '<a href="' . url("retribusi/opd/form_opd/".$data->id."/edit") . '" class="btn btn-primary">Edit</a>
                // <a href="" class="btn btn-danger">Hapus</a>';
                $action = '
                    <button type="button" data-id="'.$data->id.'" class="btn btn-primary btn-edit-data btn_edit_opd"><i class="fa fa-pencil me-2" aria-hidden="true"></i>Edit</button>
                    <button type="button" data-id="'.$data->id.'" class="btn btn-sm btn-danger btn_hapus_opd btn-hapus-data"><i class="fa fa-trash-o me-2" aria-hidden="true"></i>Hapus</button>
                ';
                $no += 1;
                $arr[] = array(
                    "no" => $no,
                    "id_retribusi_opd" => $data->id_retribusi_opd,
                    "tahun" => $data->tahun,
                    "target_murni" => rupiahFormat($data->target_murni),
                    "target_perubahan" => rupiahFormat($data->target_perubahan),
                    "action" => $action,
                );
            }
        }



        // dd($arr);
        return Datatables::of($arr)
            ->rawColumns(['action'])
            ->make(true);
    }

    public function form_opd(){
        return view('admin_retribusi.opd.form_opd');
    }
    public function form_opd_form(Request $request){

        $validator = Validator::make($request->all(), [
            'id_retribusi_opd' => 'required',
            'target_murni' => 'required|numeric',
            'tahun' => 'required',
        ], [
            "id_retribusi_opd.required" => "Data retribusi tidak boleh kosong!",
            "target_murni.required" => "Data target murni tidak boleh kosong!",
            "target_murni.numeric" => "Data target murni harus angka!",
            "tahun.required" => "Data tahun tidak boleh kosong!",
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $id_retri = $request->id_retribusi_opd;
        $tahun_retri = $request->tahun;

        $session =  Session::get("user_app");
        $id = decrypt($session['user_id']);
        $cekRetri = DB::table('data.target_opd')
                    ->where('id_retribusi_opd',$id_retri)
                    ->where('tahun',$tahun_retri)
                    ->where('id_opd',$id)->count();
        if($cekRetri != 0){
            return response()->json(['error' => "Data sudah ada ditahun $tahun_retri"]);
        }
        $session =  Session::get("user_app");
        $id = decrypt($session['user_id']);
        $data = [
            "id_opd" => $id,
            "id_retribusi_opd" => $request->id_retribusi_opd,
            "target_murni" =>$request->target_murni,
            "target_perubahan" => $request->target_perubahan,
            "tahun" => $request->tahun
        ];

        // dd($data);
        $dt = DB::table('data.target_opd')->insertGetId($data);
        // dd($dt);
        $bulan = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
        foreach ($bulan as $bln) {
            $data_realisasi = [
                "target_opd_id" => $dt,
                "bulan" => $bln,
                "id_opd" => $id,
                "realisasi" => 0
            ];
            DB::table('data.detail_realisasi')->insert($data_realisasi);
        }
        // return redirect()->route('retribusi.opd.index')->with(['success' => 'Data Berhasil Disimpan!'])
        return response()->json([
            "success" => "Data berhasil disimpan !"
        ]);
    }

    public function form_opd_form_edit($id){
        $data = DB::table('data.target_opd')->where('id',$id)->get();
        $datas = [
            "id" => $data[0]->id,
            "id_retribusi_opd" => $data[0]->id_retribusi_opd,
            "target_murni" => $data[0]->target_murni,
            "target_perubahan" => $data[0]->target_perubahan,
            "tahun" => $data[0]->tahun,
        ];
        // return view('admin_retribusi.opd.form_opd_edit',compact('id','data'));
        return response()->json([
            "result" => $datas
        ]);
    }
    public function form_opd_form_update(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'id_retribusi_opd' => 'required',
            'target_murni' => 'required|numeric',
            'tahun' => 'required',
        ], [
            "id_retribusi_opd.required" => "Data retribusi tidak boleh kosong!",
            "target_murni.required" => "Data target murni tidak boleh kosong!",
            "target_murni.numeric" => "Data target murni harus angka!",
            "tahun.required" => "Data tahun tidak boleh kosong!",
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $id_retri = $request->id_retribusi_opd;
        $tahun_retri = $request->tahun;
        $id = (int)$id;

        $session =  Session::get("user_app");
        $id_op = decrypt($session['user_id']);


        $cekData = DB::table('data.target_opd')
                        ->where('id',$id)
                        ->where('tahun',$tahun_retri)
                        ->where("id_retribusi_opd",$request->id_retribusi_opd)->count();
        // dd($cekData);
        if($cekData == 0){
            $cekRetri = DB::table('data.target_opd')
            ->where('id_retribusi_opd',$id_retri)
            ->where('tahun',$tahun_retri)
            ->where('id_opd',$id_op)->count();
            if($cekRetri != 0){
                return response()->json(['error' => "Data sudah ada ditahun $tahun_retri"]);
            }
        }

        DB::table('data.target_opd')->where('id', $id)->update([
            'id_retribusi_opd' => $request->id_retribusi_opd,
            'tahun' => $request->tahun,
            'target_murni' => $request->target_murni,
            'target_perubahan' => $request->target_perubahan,
        ]);
    
        // return redirect()->route('retribusi.opd.index')->with('success', 'Data OPD berhasil diperbarui.');
        return response()->json([
            "success" => "Data berhasil diupdate !"
        ]);
    }
    public function form_opd_form_delete($id){
        $id = (int)$id; 
        // dd($id);
        $data = DB::table('data.target_opd')->where('id', $id);
        $data->delete();

        DB::table('data.detail_realisasi')->where('target_opd_id', $id)->delete();
        // return redirect()->back();
        return response()->json([
            "success" => "Data berhasil dihapus !"
        ]);
    }
}
