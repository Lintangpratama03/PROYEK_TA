<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportRekapKetetapanPeruntukanBPHTB implements ToCollection
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
                $insert['nama_rekening'] = $value[3];
                $insert['kode_rekening'] = $value[4];
                $insert['peruntukan'] = $value[5];
                $insert['nominal_ketetapan'] = $value[6];
                $insert['jumlah_transaksi'] = $value[7];
                $insert['sumber_data'] = $value[8];
                $insert['tanggal_update'] =  $this->getDate($value[9]);
                
                $cek_data = DB::table('data.rekap_ketetapan_peruntukan_bphtb')->where('tahun', $value[1])->where('bulan', $value[2])->where('peruntukan', $value[5])->count();
                if ($cek_data > 0) {
                    DB::table('data.rekap_ketetapan_peruntukan_bphtb')->where('tahun', $value[1])->where('bulan', $value[2])->where('peruntukan', $value[5])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.rekap_ketetapan_peruntukan_bphtb')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
