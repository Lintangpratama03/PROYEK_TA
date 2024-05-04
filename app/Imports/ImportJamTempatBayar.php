<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportJamTempatBayar implements ToCollection
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
                $insert['jam'] = $this->getDate($value[3]);
                $insert['jumlah_transaksi'] = $value[4];
                $insert['tempat_bayar'] = $value[5];
                $insert['sumber_data'] = $value[6];
                $insert['tanggal_update'] =  $this->getDate($value[7]);

                $cek_data = DB::table('data.jam_tempat_bayar')->where('tahun', $value[1])->where('bulan', $value[2])->where('jam', $this->getDate($value[3]))->where('tempat_bayar', $value[5])->count();
                if ($cek_data > 0) {
                    DB::table('data.jam_tempat_bayar')->where('tahun', $value[1])->where('bulan', $value[2])->where('jam', $this->getDate($value[3]))->where('tempat_bayar', $value[5])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.jam_tempat_bayar')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
