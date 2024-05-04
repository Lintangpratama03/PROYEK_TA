<?php

namespace App\Imports;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportRekapKetetapanBPHTBValidasi implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $value) {
            if ($key!=0) {
                // dd($value);
                $insert['tahun']     = $value[1];
                $insert['bulan'] = $value[2];
                $insert['jumlah_ketetapan'] = $value[3];
                $insert['sudah_divalidasi'] = $value[4];
                $insert['belum_divalidasi'] = $value[5];
                $insert['sumber_data'] = $value[6];
                $insert['tanggal_update'] =  $this->getDate($value[7]);

                $cek_data = DB::table('data.rekap_ketetapan_bphtb_validasi')->where('tahun', $value[1])->where('bulan', $value[2])->count();
                if ($cek_data > 0) {
                    DB::table('data.rekap_ketetapan_bphtb_validasi')->where('tahun', $value[1])->where('bulan', $value[2])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.rekap_ketetapan_bphtb_validasi')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
