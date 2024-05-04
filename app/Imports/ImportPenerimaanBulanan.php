<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportPenerimaanBulanan implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //dd($row);
        foreach ($rows as $key => $value) {
            if ($key!=0) {
                // dd($value);
                $insert['tahun']     = $value[1];
                $insert['bulan'] = $value[2];
                $insert['nama_rekening'] = $value[3];
                $insert['kode_rekening'] = $value[4];
                $insert['penerimaan_per_bulan'] = $value[5];
                $insert['penerimaan_akumulasi'] = $value[6];
                $insert['jumlah_transaksi_per_bulan'] = $value[7];
                $insert['jumlah_transaksi_akumulasi'] = $value[8];
                $insert['sumber_data'] = $value[9];
                $insert['tanggal_update'] =  $this->getDate($value[10]);
                
                $cek_data = DB::table('data.penerimaan_bulanan')->where('tahun', $value[1])->where('bulan', $value[2])->where('nama_rekening', $value[3])->where('kode_rekening', $value[4])->count();
                if ($cek_data > 0) {
                    DB::table('data.penerimaan_bulanan')->where('tahun', $value[1])->where('bulan', $value[2])->where('nama_rekening', $value[3])->where('kode_rekening', $value[4])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.penerimaan_bulanan')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
