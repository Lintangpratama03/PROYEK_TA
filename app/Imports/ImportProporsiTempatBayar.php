<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;


class ImportProporsiTempatBayar implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //dd($row);
        foreach ($rows as $key => $value) {
            if ($key!=0) {
                // dd($value);
                $insert['tahun']     = $value[1];
                $insert['nama_rekening'] = $value[2];
                $insert['kode_rekening'] = $value[3];
                $insert['persen_nominal'] = $value[4];
                $insert['persen_jumlah'] = $value[5];
                $insert['tempat_bayar'] = $value[6];
                $insert['sumber_data'] = $value[7];
                $insert['tanggal_update'] =  $this->getDate($value[8]);
                
                $cek_data = DB::table('data.proporsi_tempat_bayar')->where('tahun', $value[1])->where('nama_rekening', $value[2])->where('kode_rekening', $value[3])->where('tempat_bayar', $value[6])->count();
                if ($cek_data > 0) {
                    DB::table('data.proporsi_tempat_bayar')->where('tahun', $value[1])->where('nama_rekening', $value[2])->where('kode_rekening', $value[3])->where('tempat_bayar', $value[6])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.proporsi_tempat_bayar')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
