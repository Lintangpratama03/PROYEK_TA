<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;


class ImportTunggakanBuku implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $value) {
            if ($key!=0) {
                // dd($value);
                $insert['tahun_sppt']     = $value[1];
                $insert['buku'] = $value[2];
                $insert['nominal_baku'] = $value[3];
                $insert['nominal_pokok'] = $value[4];
                $insert['nominal_denda'] = $value[5];
                $insert['nominal_terima'] = $value[6];
                $insert['nop_baku'] = $value[7];
                $insert['nop_bayar'] = $value[8];
                $insert['kecamatan'] = $value[9];
                $insert['kelurahan'] = $value[10];
                $insert['sumber_data'] = $value[11];
                $insert['tanggal_update'] =  $this->getDate($value[12]);
                
                $cek_data = DB::table('data.tunggakan_buku')->where('tahun_sppt', $value[1])->where('kecamatan', $value[9])->where('kelurahan', $value[10])->where('buku', $value[2])->count();
                if ($cek_data > 0) {
                    DB::table('data.tunggakan_buku')->where('tahun_sppt', $value[1])->where('kecamatan', $value[9])->where('kelurahan', $value[10])->where('buku', $value[2])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.tunggakan_buku')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
