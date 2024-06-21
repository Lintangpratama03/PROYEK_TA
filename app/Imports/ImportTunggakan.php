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
                $insert['nop']     = $value[1];
                $insert['tahun_sppt'] = $value[2];
                $insert['nama_rekening'] = $value[3];
                $insert['pbb_terutang'] = $value[4];
                $insert['nominal_denda'] = $value[5];
                $insert['nominal_tunggakan'] = $value[6];
                $insert['tahun_pajak'] = $value[7];
                $insert['kecamatan'] = $value[8];
                $insert['kelurahan'] = $value[9];
                $insert['sumber_data'] = $value[10];
                $insert['tanggal_update'] =  $this->getDate($value[11]);

                $cek_data = DB::connection("pgsql_pbb")->table("data.detail_tunggakan_pbb")->where('tahun_sppt', $value[2])->where('nop', $value[1])->count();
                if ($cek_data > 0) {
                    DB::connection("pgsql_pbb")->table("data.detail_tunggakan_pbb")->where('tahun_sppt', $value[2])->where('nop', $value[1])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::connection("pgsql_pbb")->table("data.detail_tunggakan_pbb")->insert($insert);
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
