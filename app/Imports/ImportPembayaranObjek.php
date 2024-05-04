<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportPembayaranObjek implements ToCollection
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
                $insert['tanggal_bayar'] = $this->getDate($value[2]);
                $insert['nop'] = $value[3];
                $insert['npwpd'] = $value[4];
                $insert['nama_subjek_pajak'] = $value[5];
                $insert['alamat_subjek_pajak'] = $value[6];
                $insert['alamat_objek_pajak'] = $value[7];
                $insert['kecamatan'] = $value[8];
                $insert['kelurahan'] = $value[9];
                $insert['nominal'] = $value[10];
                $insert['sumber_data'] = $value[11];
                $insert['tanggal_update'] =  $this->getDate($value[12]);

                $cek_data = DB::table('data.pembayaran_objek')->where('tahun', $value[1])->where('tanggal_bayar', $this->getDate($value[2]))->where('nop', $value[3])->where('npwpd', $value[4])->count();
                if ($cek_data > 0) {
                    DB::table('data.pembayaran_objek')->where('tahun', $value[1])->where('tanggal_bayar', $this->getDate($value[2]))->where('nop', $value[3])->where('npwpd', $value[4])->delete();
                }
                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.pembayaran_objek')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
