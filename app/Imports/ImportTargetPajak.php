<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportTargetPajak implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    { //dd($row);
        foreach ($rows as $key => $value) {
            if ($key != 0) {
                $insert['tahun'] = $value[1];
                $insert['jenis_pajak'] = $value[2];
                $insert['target'] = $value[3];
                $insert['target_tw1'] = $value[4];
                $insert['target_tw2'] = $value[5];
                $insert['target_tw3'] = $value[6];
                $insert['target_tw4'] = $value[7];

                $cek_data = DB::table('master.target_tw')->where('tahun', $value[1])->where('jenis_pajak', $value[2])->count();
                if ($cek_data > 0) {
                    DB::table('master.target_tw')->where('tahun', $value[1])->where('jenis_pajak', $value[2])->delete();
                }
                DB::table('master.target_tw')->insert($insert);
            }
        }

        return "sukses";
    }

    function getDate($value)
    {
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
