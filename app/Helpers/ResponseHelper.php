<?php

// use Session;
use Illuminate\Support\Facades\Session;

function ArrayOfResponse(){
    $error_status = "error";
    $success_status = "success";

    $response = [
        // Success Response
        "101" => 
            [
                "status" => $success_status,
                "message" => "Data Berhasil Ditambahkan"
            ],
        "102" => 
            [
                "status" => $success_status,
                "message" => "Data Berhasil Diubah"
            ],
        "103" => 
            [
                "status" => $success_status,
                "message" => "Data Berhasil Dihapus"
            ],
        "121" => 
            [
                "status" => $success_status,
                "message" => "Objek Pajak Berhasil Ditutup"
            ],
        // status chat
        "111" => 
            [
                "status" => $success_status,
                "message" => "Pesan Terkirim"
            ],
        // status AKTIF AKUN LOGIN
        "130" => 
        [
            "status" => $success_status,
            "message" => "Akun Berhasil Di NonAktifkan"
        ],
        "131" => 
        [
            "status" => $success_status,
            "message" => "Akun Berhasil Di Aktifkan"
        ],

        // Error Response
        "000" => 
            [
                "status" => $error_status,
                "message" => "Terjadi Kesalahan Pada Server"
            ],
        "001" => 
            [
                "status" => $error_status,
                "message" => "Data Gagal Ditambahkan"
            ],
        "002" => 
            [
                "status" => $error_status,
                "message" => "Data Gagal Diubah"
            ], 
        "003" => 
            [
                "status" => $error_status,
                "message" => "Data Gagal Dihapus"
            ],
        "004" => 
            [
                "status" => $error_status,
                "message" => "Data Tidak Ditemukan"
            ],
        "021" => 
            [
                "status" => $error_status,
                "message" => "Objek Pajak Gagal Ditutup"
            ], 
        // status chat
        "011" => 
            [
                "status" => $error_status,
                "message" => "Pesan Gagal Dikirim"
            ],

        //// NOTIF PEMBERITAHUAN
        "099" => 
        [
            "status" => $error_status,
            "message" => "Maaf, Ketetapan Pajak Sudah Pernah Dibuat"
        ],

        "098" => 
        [
            "status" => $error_status,
            "message" => "Gagal, File Lampiran Tidak Sesuai Format !"
        ],  
    ];

    return $response;
}

function CustomResponse($status,$message){
    $response = [
        "status" => $status,
        "message" => $message
    ];

    return $response;
}

function ReturnResponse($kode){

    $response = ArrayOfResponse();

    return $response[$kode];
}