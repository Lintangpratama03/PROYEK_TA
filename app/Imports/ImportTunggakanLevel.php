<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportTunggakanLevel implements ToCollection
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
                $insert['level']     = $value[1];
                $insert['nominal'] = $value[2];
                $insert['nop'] = $value[3];
                $insert['kecamatan'] = $value[4];
                $insert['kelurahan'] = $value[5];
                $insert['sumber_data'] = $value[6];
                $insert['tanggal_update'] =  $this->getDate($value[7]);
                
                $cek_data = DB::table('data.tunggakan_level')->where('level', $value[1])->where('kecamatan', $value[4])->where('kelurahan', $value[5])->count();
                if ($cek_data > 0) {
                    DB::table('data.tunggakan_level')->where('level', $value[1])->where('kecamatan', $value[4])->where('kelurahan', $value[5])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.tunggakan_level')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
