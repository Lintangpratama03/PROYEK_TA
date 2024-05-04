<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportTunggakanLevelDetail implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $value) {
            if ($key!=0) {
                // dd($value);
                $insert['level']     = $value[1];
                $insert['nop'] = $value[2];
                $insert['npwpd'] = $value[3];
                $insert['jumlah_tahun'] = $value[4];
                $insert['nominal'] = $value[5];
                $insert['nama_subjek_pajak'] = $value[6];
                $insert['alamat_subjek_pajak'] = $value[7];
                $insert['alamat_objek_pajak'] = $value[8];
                $insert['kecamatan'] = $value[9];
                $insert['kelurahan'] = $value[10];
                $insert['sumber_data'] = $value[11];
                $insert['tanggal_update'] =  $this->getDate($value[12]);
                
                $cek_data = DB::table('data.tunggakan_level_detail')->where('level', $value[1])->where('nop', $value[2])->where('npwpd', $value[3])->count();
                if ($cek_data > 0) {
                    DB::table('data.tunggakan_level_detail')->where('level', $value[1])->where('nop', $value[2])->where('npwpd', $value[3])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.tunggakan_level_detail')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
