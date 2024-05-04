<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportTunggakanDetail implements ToCollection
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
                $insert['nop']     = $value[1];
                $insert['npwpd'] = $value[2];
                $insert['tahun_sppt'] = $value[3];
                $insert['nama_subjek_pajak'] = $value[4];
                $insert['alamat_subjek_pajak'] = $value[5];
                $insert['alamat_objek_pajak'] = $value[6];
                $insert['kecamatan'] = $value[7];
                $insert['kelurahan'] = $value[8];
                $insert['nominal'] = $value[9];
                $insert['sumber_data'] = $value[10];
                $insert['tanggal_update'] =  $this->getDate($value[11]);
                
                $cek_data = DB::table('data.tunggakan_detail')->where('nop', $value[1])->where('npwpd', $value[2])->where('tahun_sppt', $value[3])->count();
                if ($cek_data > 0) {
                    DB::table('data.tunggakan_detail')->where('nop', $value[1])->where('npwpd', $value[2])->where('tahun_sppt', $value[3])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.tunggakan_detail')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
