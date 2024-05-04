<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportPelaporan implements ToCollection
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
                $insert['bulan'] = $value[2];
                $insert['nama_rekening'] = $value[3];
                $insert['kode_rekening'] = $value[4];
                $insert['lapor'] = $value[5];
                $insert['belum_bayar'] = $value[6];
                $insert['belum_lapor'] = $value[7];
                $insert['sumber_data'] = $value[8];
                $insert['tanggal_update'] =  $this->getDate($value[9]);

                $cek_data = DB::table('data.pelaporan')->where('tahun', $value[1])->where('bulan', $value[2])->where('nama_rekening', $value[3])->where('kode_rekening', $value[4])->count();
                if ($cek_data > 0) {
                    DB::table('data.pelaporan')->where('tahun', $value[1])->where('bulan', $value[2])->where('nama_rekening', $value[3])->where('kode_rekening', $value[4])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.pelaporan')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
