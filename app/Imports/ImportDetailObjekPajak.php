<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class ImportDetailObjekPajak implements ToCollection
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
                $insert['nama_subjek_pajak'] = $value[5];
                $insert['alamat_subjek_pajak'] = $value[6];
                $insert['nama_objek_pajak'] = $value[7];
                $insert['alamat_objek_pajak'] = $value[8];
                $insert['tanggal_daftar']     = $this->getDate($value[9]);
                $insert['tanggal_tutup'] = $this->getDate($value[10]);
                $insert['telp_subjek_pajak'] = $value[11];
                $insert['nama_contact_person'] = $value[12];
                $insert['telp_contact_person'] = $value[13];
                $insert['sumber_data'] = $value[14];
                $insert['tanggal_update'] =  $this->getDate($value[15]);
                
                $cek_data = DB::table('data.detail_objek_pajak')->where('nop', $value[1])->where('npwpd', $value[2])->where('nama_rekening', $value[3])->where('kode_rekening', $value[4])->count();
                if ($cek_data > 0) {
                    DB::table('data.detail_objek_pajak')->where('nop', $value[1])->where('npwpd', $value[2])->where('nama_rekening', $value[3])->where('kode_rekening', $value[4])->delete();
                }

                if (!is_null($insert['sumber_data'])) {
                    DB::table('data.detail_objek_pajak')->insert($insert);
                }
            }
        }

        return "sukses"; 
    }

    function getDate($value){
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }
}
