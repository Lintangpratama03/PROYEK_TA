<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;


class ImportPenerimaanHarian implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $value) {
            if ($key!=0) {
                // dd($value);
                $tanggal = $this->getDate($value[1]);
                // dd($tanggal);
                $insert['tanggal']     = $tanggal;
                $insert['nama_rekening'] = $value[2];
                $insert['kode_rekening'] = $value[3];
                $insert['penerimaan'] = $value[4];
                $insert['jumlah_transaksi'] = $value[5];
                $insert['tempat_bayar'] = $value[6];
                $insert['sumber_data'] = $value[7];
                $insert['tanggal_update'] =  $this->getDate($value[8]);

                $cek_data = DB::table('data.penerimaan_harian')->where('tanggal', $tanggal)->where('nama_rekening', $value[2])->where('kode_rekening', $value[3])->count();
                if ($cek_data > 0) {
                    DB::table('data.penerimaan_harian')->where('tanggal', $tanggal)->where('nama_rekening', $value[2])->where('kode_rekening', $value[3])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.penerimaan_harian')->insert($insert);
                }
                
            }
        }

        return "sukses";
    }
    
    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
