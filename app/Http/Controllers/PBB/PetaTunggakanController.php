<?php

namespace App\Http\Controllers\PBB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetaTunggakanController extends Controller
{
    public function index()
    {
        $koordinat = DB::connection("pgsql_pbb")
            ->table("data.koordinat")->get();
        $tunggakan = DB::connection("pgsql_pbb")
            ->table("data.detail_tunggakan")->get();
        return view('admin.pbb.peta.peta', compact('koordinat', 'tunggakan'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }


    public function edit($id)
    {
    }


    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
