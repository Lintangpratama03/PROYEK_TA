<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportTunggakan implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    { //dd($row);
        foreach ($rows as $key => $value) {
            if ($key != 0) {
                // dd($value);
                $insert['tahun_sppt']     = $value[1];
                $insert['nominal_baku'] = $value[2];
                $insert['nominal_pokok'] = $value[3];
                $insert['nominal_denda'] = $value[4];
                $insert['nominal_terima'] = $value[5];
                $insert['nop_baku'] = $value[6];
                $insert['nop_bayar'] = $value[7];
                $insert['kecamatan'] = $value[8];
                $insert['kelurahan'] = $value[9];
                $insert['sumber_data'] = $value[10];
                $insert['tanggal_update'] =  $this->getDate($value[11]);

                $cek_data = DB::table('data.tunggakan')->where('tahun_sppt', $value[1])->where('kecamatan', $value[8])->where('kelurahan', $value[9])->count();
                if ($cek_data > 0) {
                    DB::table('data.tunggakan')->where('tahun_sppt', $value[1])->where('kecamatan', $value[8])->where('kelurahan', $value[9])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.tunggakan')->insert($insert);
                }
            }
        }

        return "sukses";
    }

    function getDate($value)
    {
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
