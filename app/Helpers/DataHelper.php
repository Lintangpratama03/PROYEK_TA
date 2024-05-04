<?php

// use Session;

use Dotenv\Result\Result;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Carbon;

function getDaftarData($id)
{
    $data = DB::table("data.daftar_data")
        ->where("id", $id)
        ->first();

    $result = strtolower($data->view);

    return $result;
}

function getGroupId($group_name)
{
    $data_group = DB::table("auth.user_group")
        ->where("nama_group", $group_name)
        ->first();

    $result = $data_group->id;

    return $result;
}

function getMonthList()
{

    $result = array(
        "1" => "Januari",
        "2" => "Februari",
        "3" => "Maret",
        "4" => "April",
        "5" => "Mei",
        "6" => "Juni",
        "7" => "Juli",
        "8" => "Agustus",
        "9" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "Desember"
    );

    return $result;
}

function getMonth($kode)
{
    $arr_month = getMonthList();

    return $arr_month[$kode];
}

function rupiahFormat($angka)
{

    $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
}

function getListTahun()
{
    $arr_tahun = array();
    foreach (range(date('Y'), "2021") as $tahun) {
        // array_push($arr_tahun,$tahun);
        $arr_tahun[$tahun] = $tahun;
    }
    return $arr_tahun;
}

function getRekening()
{
    $data = DB::table("data.master_rekening")
        ->where("tahun", 2023)
        ->where("level", 4)
        ->where("is_aktif", 1)
        ->orderby("id", "asc")
        ->get();

    return $data;
}
function getRekeningPAD()
{
    $data = DB::table("data.master_rekening")
        ->where("tahun", 2023)
        ->where("level", 4)
        ->whereNotIn("nama_rekening", ["Pajak Sarang Burung Walet", "Pajak Mineral Bukan Logam dan Batuan"])
        ->get();

    return $data;
}
function getretribusiPAD()
{
    $data = DB::table("data.retribusi")
        ->get();

    return $data;
}
function getJenisRetribusi()
{
    $data = DB::table("data.jenis_retribusi")
        ->get();

    return $data;
}

function getJenisPajak()
{
    $data = DB::table("data.master_rekening")
        ->where("tahun", 2023)
        ->where("level", 4)
        ->where('kode_rekening', 'like', '4.1.01' . '%%')
        ->where("is_aktif", 1)
        ->orderby("id", "asc")
        ->get();

    return $data;
}
function getJenisPajakPDLPelaporan()
{
    $data = DB::table("data.master_rekening")
        ->where("tahun", 2023)
        ->where("level", 4)
        ->where('kode_rekening', 'like', '4.1.01' . '%')
        ->where("is_aktif", 1)
        ->orderBy("id", "asc")
        ->get();

    $modifiedData = $data->map(function ($item) {
        if (substr($item->kode_rekening, -1) !== '0') {
            $item->kode_rekening .= '0';
        }
        return $item;
    });

    return $modifiedData;
}


function getJenisPajakSimpadamav2()
{
    $data = "
        SELECT * FROM master.tb_jenis_pajak WHERE nama_pajak NOT IN ('BPHTB','PBB')
    ";
    $d_data = DB::connection("pgsql_pdl")->select($data);
    $arr = array();
    foreach ($d_data as $key => $d) {
        $arr[] =
            array(
                "id" => $d->id,
                "nama_pajak" => $d->nama_pajak,
                "kode_rekening" => $d->kode_rekening,
                "kode_perwal" => $d->kode_perwal
            );
    }

    return $arr;
}

function getKecamatan()
{
    $data = DB::table("data.tunggakan_buku")
        ->selectRaw("distinct(kecamatan) as kecamatan")
        ->get();
    return $data;
}

function getOpdRetribusi()
{
    $session =  Session::get("user_app");
    $id = decrypt($session['user_id']);
    // dd($id);
    $query = "select
                ro.id,
                ro.id_opd,
                ro.id_retribusi,
                op.nama_opd,
                rt.nama_retribusi
            from data.retribusi_opd as ro
            join data.opd as op on op.id_opd = ro.id_opd
            join data.retribusi as rt on rt.id = ro.id_retribusi
            where ro.id_opd = $id";
    $data = DB::select($query);
    return $data;
}

function getKelurahan()
{
    $data = DB::table("data.tunggakan_buku")
        ->selectRaw("distinct(kelurahan) as kelurahan")
        ->get();
    return $data;
}

function arrUsahaId()
{
    $arr = [
        "410106" => "1",
        "410107" => "2",
        "410108" => "3",
        "410109" => "4",
        "410111" => "5",
        "410112" => "6",
        "410110" => "7",
        "410114" => "9",
        "410113" => "10",
        "410115" => "11",
        "410116" => "12"
    ];

    return $arr;
}

function getUsahaId($kodeRekening)
{
    $result = arrUsahaId()[$kodeRekening];
    return $result;
}

function getRek()
{
    $data = DB::table("data.rekap_tunggakan_pdl")
        ->selectRaw("distinct(nama_rekening) as nama_rekening")
        ->where('nama_rekening', '!=', 'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)')
        ->get();
    return $data;
}

function get_jenis_pajak()
{
    $arr = [
        "1" => "Pajak Hotel",
        "2" => "Pajak Restoran",
        "3" => "Pajak Hiburan",
        "4" => "Pajak Reklame",
        "5" => "Pajak Parkir",
        "6" => "Pajak Air Tanah",
        "7" => "Pajak Penerangan Jalan",
        "9" => "Pajak Mineral Bukan Logam dan Batuan",
        "10" => "Pajak Sarang Burung Walet",
        "11" => "Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)",
        "12" => "Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)",
        "13" => "DENDA",

    ];
    return $arr;
}
