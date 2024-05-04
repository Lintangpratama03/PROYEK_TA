<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportRekapKetetapan implements ToCollection
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
                $insert['nominal_ketetapan'] = $value[5];
                $insert['jumlah_transaksi'] = $value[6];
                $insert['sumber_data'] = $value[7];
                $insert['tanggal_update'] =  $this->getDate($value[8]);
                
                $cek_data = DB::table('data.rekap_ketetapan')->where('tahun', $value[1])->where('bulan', $value[2])->where('nama_rekening', $value[3])->where('kode_rekening', $value[4])->count();
                if ($cek_data > 0) {
                    DB::table('data.rekap_ketetapan')->where('tahun', $value[1])->where('bulan', $value[2])->where('nama_rekening', $value[3])->where('kode_rekening', $value[4])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.rekap_ketetapan')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
