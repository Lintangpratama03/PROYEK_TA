<?php

namespace App\Imports;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportRekapKetetapanBPHTBNihilBayar implements ToCollection
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
                $insert['nominal_berbayar'] = $value[3];
                $insert['jumlah_transaksi'] = $value[4];
                $insert['sudah_bayar'] = $value[5];
                $insert['belum_bayar'] = $value[6];
                $insert['jumlah_transaksi_nihil'] = $value[7];
                $insert['sumber_data'] = $value[8];
                $insert['tanggal_update'] =  $this->getDate($value[9]);

                $cek_data = DB::table('data.rekap_ketetapan_bphtb_nihil_bayar')->where('tahun', $value[1])->where('bulan', $value[2])->count();
                if ($cek_data > 0) {
                    DB::table('data.rekap_ketetapan_bphtb_nihil_bayar')->where('tahun', $value[1])->where('bulan', $value[2])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.rekap_ketetapan_bphtb_nihil_bayar')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
