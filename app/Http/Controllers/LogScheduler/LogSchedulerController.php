<?php

namespace App\Http\Controllers\LogScheduler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

class LogSchedulerController extends Controller
{
    public function index()
    {
        return view("admin.log_scheduler.index");
    }

    function datatable_log_scheduler(){
        $d_data = DB::table("log.log_scheduler")->get();
        $arr = array();
        if($d_data->count() > 0){
            foreach ($d_data as $key => $d) {
                $arr[] = 
                    array(
                        "jenis_data"=>$d->jenis_data,
                        "total_data"=>$d->total_data,
                        "status"=>$d->status,
                        "keterangan"=>$d->keterangan,
                    );
            }
            
        }

        return Datatables::of($arr)
        ->addIndexColumn()
        ->make(true);
    }
}
