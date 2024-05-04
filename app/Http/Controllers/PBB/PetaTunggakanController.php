<?php

namespace App\Http\Controllers\PBB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetaTunggakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view("admin.pbb.peta.peta");
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
