<?php

namespace App\Http\Controllers\Download;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class UserManualController extends Controller
{
    public function downloadUserManual()
    {
        $session = Session::get("user_app");
        $name = $session['group_name'];

        // Pastikan bahwa variabel $name di-log atau ditangani dengan benar
        // dd($name);

        if ($name == 'superadmin') {
            $filename = 'User_Manual_super_admin_Smart_Dashboard_Madiun.pdf';
        } elseif ($name == 'OPD') {
            $filename = 'User_Manual_admin_OPD_Smart_Dashboard_Madiun.pdf';
        } elseif ($name == 'admin') {
            $filename = 'User_Manual_admin_Bapenda_Smart_Dashboard_Madiun.pdf';
        } else {
            return redirect()->back()->withErrors(['msg' => 'Grup pengguna tidak valid']);
        }

        $file = public_path('user-manual/' . $filename);

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ];

        return response()->download($file, $filename, $headers);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
    public function destroy($id)
    {
        //
    }
}
