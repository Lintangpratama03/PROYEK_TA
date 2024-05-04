<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportPelaporanPPATBPHTB implements ToCollection
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
                $insert['nama_ppat'] = $value[3];
                $insert['jumlah_laporan'] = $value[4];
                $insert['sumber_data'] = $value[5];
                $insert['tanggal_update'] =  $this->getDate($value[6]);
                
                $cek_data = DB::table('data.pelaporan_ppat_bphtb')->where('tahun', $value[1])->where('bulan', $value[2])->where('nama_ppat', $value[3])->count();
                if ($cek_data > 0) {
                    DB::table('data.pelaporan_ppat_bphtb')->where('tahun', $value[1])->where('bulan', $value[2])->where('nama_ppat', $value[3])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.pelaporan_ppat_bphtb')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
