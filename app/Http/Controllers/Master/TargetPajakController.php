<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class TargetPajakController extends Controller
{
    public function index()
    {
        $akses = get_url_akses();
        if($akses){
           return redirect()->route("pad.index");
        }else{
            return view("master.import_target_pajak");
        }
    }
    public function datatable_target_pajak(Request $request)
    {
        $query = DB::table("master.target_tw")
            ->select('tahun', 'jenis_pajak', 'target', 'target_tw1', 'target_tw2', 'target_tw3', 'target_tw4')
            ->orderBy('tahun', 'ASC')
            ->get();

        $arr = [];
        foreach ($query as $value) {
            $arr[] = [
                "tahun" => $value->tahun,
                "jenis_pajak" => $value->jenis_pajak,
                "target_tahun" => number_format($value->target),
                "target_tw1" => number_format($value->target_tw1),
                "target_tw2" => number_format($value->target_tw2),
                "target_tw3" => number_format($value->target_tw3),
                "target_tw4" => number_format($value->target_tw4),
            ];
        }

        return Datatables::of($arr)
            ->addIndexColumn()
            ->make(true);
    }
}
