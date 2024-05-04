<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportPenerimaanTahunSPPT implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $value) {
            if ($key!=0) {
                // dd($value);
                $insert['tahun_bayar']     = $value[1];
                $insert['bulan_bayar'] = $value[2];
                $insert['tahun_sppt'] = $value[3];
                $insert['nominal_pokok'] = $value[4];
                $insert['nominal_denda'] = $value[5];
                $insert['nominal_terima'] = $value[6];
                $insert['nop'] = $value[7];
                $insert['sumber_data'] = $value[8];
                $insert['tanggal_update'] =  $this->getDate($value[9]);
                
                $cek_data = DB::table('data.penerimaan_tahun_sppt')->where('tahun_bayar', $value[1])->where('bulan_bayar', $value[2])->where('tahun_sppt', $value[3])->count();
                if ($cek_data > 0) {
                    DB::table('data.penerimaan_tahun_sppt')->where('tahun_bayar', $value[1])->where('bulan_bayar', $value[2])->where('tahun_sppt', $value[3])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.penerimaan_tahun_sppt')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
