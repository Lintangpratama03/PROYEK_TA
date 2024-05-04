<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;


class ImportDetailKetetapan implements ToCollection
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
                $insert['nop']     = $value[1];
                $insert['npwpd'] = $value[2];
                $insert['nama_rekening'] = $value[3];
                $insert['kode_rekening'] = $value[4];
                $insert['nomor_ketetapan'] = $value[5];
                $insert['nominal_ketetapan'] = $value[6];
                $insert['nama_subjek_pajak']     = $value[7];
                $insert['alamat_subjek_pajak'] = $value[8];
                $insert['nama_objek_pajak'] = $value[9];
                $insert['alamat_objek_pajak'] = $value[10];
                $insert['tanggal_ketetapan'] =$this->getDate($value[11]);
                $insert['masa_awal'] = $this->getDate($value[12]);
                $insert['masa_akhir'] = $this->getDate($value[13]);
                $insert['tanggal_jatuh_tempo'] = $this->getDate($value[14]);
                $insert['tanggal_bayar'] =$this->getDate($value[15]);
                $insert['sumber_data'] = $value[16];
                $insert['tanggal_update'] =  $this->getDate($value[17]);
                $insert['tahun']     = $value[18];
                $insert['bulan'] = $value[19];
                
                $cek_data = DB::table('data.detail_ketetapan')->where('nop', $value[1])->where('npwpd', $value[2])->where('tahun', $value[18])->where('bulan', $value[19])->where('nama_rekening', $value[3])->where('kode_rekening', $value[4])->count();
                if ($cek_data > 0) {
                    DB::table('data.detail_ketetapan')->where('nop', $value[1])->where('npwpd', $value[2])->where('tahun', $value[18])->where('bulan', $value[19])->where('nama_rekening', $value[3])->where('kode_rekening', $value[4])->delete();
                }

                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.detail_ketetapan')->insert($insert);
                }
    
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
