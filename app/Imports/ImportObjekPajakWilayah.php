<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportObjekPajakWilayah implements ToCollection
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
                $insert['kecamatan'] = $value[2];
                $insert['kelurahan'] = $value[3];
                $insert['nop'] = $value[4];
                $insert['nominal'] = $value[5];
                $insert['sumber_data'] = $value[6];
                $insert['tanggal_update'] =  $this->getDate($value[7]);

                $cek_data = DB::table('data.objek_pajak_wilayah')->where('tahun', $value[1])->where('kecamatan', $value[2])->where('kelurahan', $value[3])->count();
                if ($cek_data > 0) {
                    DB::table('data.objek_pajak_wilayah')->where('tahun', $value[1])->where('kecamatan', $value[2])->where('kelurahan', $value[3])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.objek_pajak_wilayah')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
