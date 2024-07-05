<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class GetDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getdata_target_realisasi(Request $request)
    {
        $year = date('Y');
        $tahun = $request->tahun;
        // $bulan = $request->bulan;
        try {
            // $query_pbb = "
            // SELECT EXTRACT(YEAR FROM SYSDATE) AS TAHUN, '410115' AS KODE_REKENING,'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)' AS NAMA_REKENING,
            // SUM(JML_SPPT_YG_DIBAYAR-DENDA_SPPT) AS REALISASI, 'SISMIOP' AS SUMBER_DATA, SYSDATE AS TANGGAL_UPDATE 
            // FROM PEMBAYARAN_SPPT
            // WHERE EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT) = $tahun
            // ";
            $query_pbb = "SELECT $tahun AS tahun, 
            'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)' AS nama_rekening,
            null as kode_rekening, SUM(JML_SPPT_YG_DIBAYAR-DENDA_SPPT) AS realisasi, 'SISMIOP' AS sumber_data, SYSDATE AS tanggal_update 
             FROM PEMBAYARAN_SPPT
             WHERE EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT) = $tahun";
            $pbb = DB::connection("oracle")->select($query_pbb);

            foreach ($pbb as $key => $pbb) {

                $data_pbb = [
                    'tahun' => $pbb->tahun,
                    'nama_rekening' => $pbb->nama_rekening,
                    'kode_rekening' => $pbb->kode_rekening,
                    'level_rekening' => '4',
                    'realisasi' => $pbb->realisasi,
                    'sumber_data' => $pbb->sumber_data,
                    'tanggal_update' => tgl_full($pbb->tanggal_update, 2),
                ];

                $cek_data_pbb = DB::table('data.target_realisasi')->where('tahun', $pbb->tahun)->where('nama_rekening', $pbb->nama_rekening)->count();
                // dd($cek_data);
                if ($cek_data_pbb > 0) {
                    DB::table('data.target_realisasi')->where('tahun', $pbb->tahun)->where('nama_rekening', $pbb->nama_rekening)->update($data_pbb);
                } else {
                    DB::table('data.target_realisasi')->insert($data_pbb);
                }
            }

            // $query_bphtb = "select tahun, 'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)' as nama_rekening, sum(bayar-denda) as realisasi,'410116' as kode_rekening,'BPHTB' as sumber_data, now() as tanggal_update
            // from bphtb_bank
            // where tahun = extract(year from now())
            // group by tahun";

            $query_bphtb = "SELECT 
                    EXTRACT(YEAR FROM \"TANGGALBAYAR\") AS tahun,
                    'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)' AS nama_rekening,
                    '' AS kode_rekening,
                    4 AS level_rekening,
                    NULL AS target,
                    SUM(\"JUMLAHHARUSDIBAYAR\") AS realisasi,
                    'BPHTB' AS sumber_data,
                    NOW() AS tanggal_update
                FROM 
                    tb_sspd_bphtb
                WHERE 
                    \"DELETED_AT\" IS NULL AND 
                    \"TANGGALBAYAR\" IS NOT NULL AND 
                    \"JUMLAHHARUSDIBAYAR\" > 0 AND 
                    EXTRACT(YEAR FROM \"TANGGALBAYAR\") = $tahun
                GROUP BY 
                    EXTRACT(YEAR FROM \"TANGGALBAYAR\")";

            $bphtb = DB::connection("pgsql_bphtb")->select($query_bphtb);
            // dd($bphtb);
            foreach ($bphtb as $key => $bphtb) {
                $data_bphtb = [
                    'tahun' => $bphtb->tahun,
                    'nama_rekening' => $bphtb->nama_rekening,
                    'kode_rekening' => $bphtb->kode_rekening,
                    'level_rekening' => '4',
                    'realisasi' => $bphtb->realisasi,
                    'sumber_data' => $bphtb->sumber_data,
                    'tanggal_update' => tgl_full($bphtb->tanggal_update, 2),
                ];
                $cek_data_bphtb = DB::table('data.target_realisasi')->where('tahun', $bphtb->tahun)->where('nama_rekening', $bphtb->nama_rekening)->count();
                // dd($cek_data_bphtb);
                if ($cek_data_bphtb > 0) {
                    DB::table('data.target_realisasi')->where('tahun', $bphtb->tahun)->where('nama_rekening', $bphtb->nama_rekening)->update($data_bphtb);
                } else {
                    DB::table('data.target_realisasi')->insert($data_bphtb);
                }
            }
            $query_pdl = "
            SELECT 
                tahun_realisasi AS tahun, 
                nama_pajak AS nama_rekening, 
                q1.kode_rekening, 
                4 AS level_rekening, 
                target, 
                COALESCE(penerimaan, 0) AS realisasi, 
                'SIMPADAMA' AS sumber_data, 
                NOW() AS tanggal_update
            FROM (
                SELECT 
                    nama_pajak, 
                    kode_rekening, 
                    tahun_realisasi, 
                    SUM(COALESCE(target_setelah_papbd, target_awal_tahun)) AS target 
                FROM 
                    data.tb_target_realisasi a 
                LEFT JOIN 
                    master.tb_jenis_pajak b ON a.id_jenis_pajak = b.id 
                WHERE 
                    a.deleted_at IS NULL 
                GROUP BY 
                    nama_pajak, kode_rekening, tahun_realisasi 
            ) q1
            LEFT JOIN (
                SELECT 
                    EXTRACT(YEAR FROM a.tanggal_diterima) AS tahun,
                    b.nama_pajak AS nama_rekening,
                    b.kode_rekening,
                    SUM(A.jumlah_pembayaran::INT) AS penerimaan
                FROM 
                    DATA.tb_penerimaan A 
                LEFT JOIN 
                    master.tb_jenis_pajak b ON A.kode_akun_pajak::INT = b.id 
                LEFT JOIN 
                    DATA.tb_op C ON A.nop = C.nop
                WHERE 
                    ntpp IS NOT NULL 
                    AND A.deleted_at IS NULL 
                GROUP BY 
                    EXTRACT(YEAR FROM tanggal_diterima), 
                    b.nama_pajak, 
                    b.kode_rekening 
            ) q2 ON q1.kode_rekening = q2.kode_rekening AND q1.tahun_realisasi = q2.tahun
            WHERE 
                tahun_realisasi = $tahun";

            $pdl = DB::connection("pgsql_pdl")->select($query_pdl);

            $get_tb_pajak_pln = 0;
            foreach ($pdl as $key => $pdl) {

                if ($pdl->nama_rekening == "PBJT Tenaga Listrik") {
                    $query_pajak_pln = "
                        SELECT tahun, SUM(nominal) AS total_nominal 
                        FROM data.tb_pajak_pln 
                        WHERE tahun = '" . $tahun . "'
                        GROUP BY tahun;                    
                    ";

                    $hasil_db = DB::connection("pgsql_pdl")->select($query_pajak_pln);

                    if (!empty($hasil_db)) {
                        $get_tb_pajak_pln = $hasil_db[0]->total_nominal;
                    }
                }
                $data_pdl = [
                    'tahun' => $pdl->tahun,
                    'nama_rekening' => $pdl->nama_rekening,
                    'kode_rekening' => $pdl->kode_rekening,
                    'level_rekening' => '4',
                    'realisasi' => $pdl->realisasi + $get_tb_pajak_pln,
                    'sumber_data' => $pdl->sumber_data,
                    'tanggal_update' => tgl_full($pdl->tanggal_update, 2),
                ];

                $cek_data_pdl = DB::table('data.target_realisasi')->where('tahun', $pdl->tahun)->where('nama_rekening', $pdl->nama_rekening)->count();
                // dd($cek_data_pdl);
                if ($cek_data_pdl > 0) {
                    DB::table('data.target_realisasi')->where('tahun', $pdl->tahun)->where('nama_rekening', $pdl->nama_rekening)->update($data_pdl);
                } else {
                    DB::table('data.target_realisasi')->insert($data_pdl);
                }
            }

            $now = date("Y-m-d H:i:s");
            $arrData = ['updated_at' => $now];
            DB::table("data.daftar_data")->where('table', 'TARGET_REALISASI')->update($arrData);

            // $total_getdata = $cek_data_pbb + $cek_data_pdl + $cek_data_bphtb;
            // dd($total_getdata);
            return response()->json([
                'status' => '1',
                // 'total_data' => $total_getdata
            ]);
        } catch (\Throwable $th) {
            // return $th;
            return response()->json([
                'status' => '0 ' . $th
            ]);
        }
    }

    public function getdata_penerimaan_notaris(Request $request)
    {
        // dd($request->all());
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        // dd($tahun,$bulan);
        try {
            $query = "SELECT
            EXTRACT(YEAR FROM \"TANGGALBAYAR\") AS tahun,
            EXTRACT(MONTH FROM \"TANGGALBAYAR\") AS bulan,
            \"NAMAPPAT\" AS notaris,
            SUM(\"JUMLAHHARUSDIBAYAR\") AS nominal,
            COUNT(\"id\") AS jumlah_transaksi,
            'BPHTB' AS sumber_data,
            NOW() AS tanggal_update
          FROM tb_sspd_bphtb
          WHERE
            \"DELETED_AT\" IS NULL AND \"TANGGALBAYAR\" IS NOT NULL AND \"JUMLAHHARUSDIBAYAR\" > 0
            AND EXTRACT(YEAR FROM \"TANGGALBAYAR\") = $tahun
            AND EXTRACT(MONTH FROM \"TANGGALBAYAR\") = $bulan
          GROUP BY
            EXTRACT(YEAR FROM \"TANGGALBAYAR\"),
            EXTRACT(MONTH FROM \"TANGGALBAYAR\"),
            \"NAMAPPAT\"";


            // dd($query);
            $bphtb = DB::connection("pgsql_bphtb")->select($query);
            foreach ($bphtb as $key => $bphtb) {
                $data_bphtb = [
                    'tahun' => $bphtb->tahun,
                    'bulan' => $bphtb->bulan,
                    'notaris' => $bphtb->notaris,
                    'nominal' => $bphtb->nominal,
                    'jumlah_transaksi' => $bphtb->jumlah_transaksi,
                    'sumber_data' => $bphtb->sumber_data,
                    'tanggal_update' => tgl_full($bphtb->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.penerimaan_notaris')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('notaris', $bphtb->notaris)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.penerimaan_notaris')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('notaris', $bphtb->notaris)->delete();
                }
                DB::table('data.penerimaan_notaris')->insert($data_bphtb);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'PENERIMAAN_NOTARIS')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_rekap_ketetapan(Request $request)
    {

        $tahun = $request->tahun;
        $bulan = $request->bulan;

        try {

            $query = " select abc.tahun, abc.bulan, t.rekeningnm as nama_rekening, abc.rek as kode_rekening, sum(abc.ketetapan) as nominal_ketetapan, sum(abc.jumlah) as jumlah_transaksi,'PDL' as sumber_data, now() as tanggal_update
            from 
            (select ab.tahun, ab.bulan, substring(t.rekeningkd,1,6) as rek, ab.ketetapan, ab.jumlah
            from 
            (select extract (year from create_date) as tahun, extract (month from create_date) as bulan, rekening_id, count(*) as jumlah, sum(pajak_terhutang) as ketetapan
            from pad_spt 
            where extract (year from create_date)=$tahun and extract (month from create_date)=$bulan
            group by extract (year from create_date), extract (month from create_date), rekening_id) ab
            left join tblrekening t on ab.rekening_id=t.id) abc
            left join tblrekening t on abc.rek=t.rekeningkd 
            where t.issummary =1
            group by abc.tahun, abc.bulan, t.rekeningnm, abc.rek
            ";

            $data = DB::connection("pgsql_pdl")->select($query);
            // dd($data);
            foreach ($data as $key => $data) {
                $insert_data = [
                    'tahun' => $data->tahun,
                    'bulan' => $data->bulan,
                    'nama_rekening' => $data->nama_rekening,
                    'kode_rekening' => $data->kode_rekening,
                    'nominal_ketetapan' => $data->nominal_ketetapan,
                    'jumlah_transaksi' => $data->jumlah_transaksi,
                    'sumber_data' => $data->sumber_data,
                    'tanggal_update' => tgl_full($data->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.rekap_ketetapan')->where('tahun', $data->tahun)->where('bulan', $data->bulan)->where('nama_rekening', $data->nama_rekening)->where('kode_rekening', $data->kode_rekening)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.rekap_ketetapan')->where('tahun', $data->tahun)->where('bulan', $data->bulan)->where('nama_rekening', $data->nama_rekening)->where('kode_rekening', $data->kode_rekening)->delete();
                }
                DB::table('data.rekap_ketetapan')->insert($insert_data);
            }


            $query_bphtb = " select extract (year from tgl_transaksi) as tahun, extract(month from tgl_transaksi) as bulan, 'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)' as nama_rekening, '410116' as kode_rekening, 
            sum(bphtb_harus_dibayarkan) as nominal_ketetapan, count(*) as jumlah_transaksi, 'BPHTB' as sumber_data, now() as tanggal_update 
            from bphtb_sspd
            where extract (year from tgl_transaksi)=extract(year from now()) and extract (month from tgl_transaksi)=extract(month from now())
            group by extract (year from tgl_transaksi), extract(month from tgl_transaksi)
            order by extract (year from tgl_transaksi) desc, extract(month from tgl_transaksi) desc
            ";

            $bphtb = DB::connection("pgsql_bphtb")->select($query_bphtb);
            // dd($bphtb);
            foreach ($bphtb as $key => $bphtb) {
                $insert_bphtb = [
                    'tahun' => $bphtb->tahun,
                    'bulan' => $bphtb->bulan,
                    'nama_rekening' => $bphtb->nama_rekening,
                    'kode_rekening' => $bphtb->kode_rekening,
                    'nominal_ketetapan' => $bphtb->nominal_ketetapan,
                    'jumlah_transaksi' => $bphtb->jumlah_transaksi,
                    'sumber_data' => $bphtb->sumber_data,
                    'tanggal_update' => tgl_full($bphtb->tanggal_update, 2),
                ];
                $cek_bphtb = DB::table('data.rekap_ketetapan')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('nama_rekening', $bphtb->nama_rekening)->where('kode_rekening', $bphtb->kode_rekening)->count();
                // dd($cek_bphtb);
                if ($cek_bphtb > 0) {
                    DB::table('data.rekap_ketetapan')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('nama_rekening', $bphtb->nama_rekening)->where('kode_rekening', $bphtb->kode_rekening)->delete();
                }
                DB::table('data.rekap_ketetapan')->insert($insert_bphtb);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'REKAP_KETETAPAN')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            // return $th;
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_penerimaan_bulanan(Request $request)
    {
        $year = date('Y');
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        // dd($tahun);
        try {
            $query = "SELECT
                satu.tahun,
                '$bulan' as bulan,
                'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)' AS nama_rekening,
                null as kode_rekening,
                DUA.NOMINAL AS penerimaan_per_bulan,
                SATU.NOMINAL AS penerimaan_akumulasi,
                DUA.NOP AS jumlah_transaksi_per_bulan,
                SATU.NOP AS jumlah_transaksi_akumulasi,
                'SISMIOP' AS sumber_data,
                SYSDATE AS tanggal_update,
                NM_KECAMATAN AS kecamatan,
                NM_KELURAHAN AS kelurahan,
                satu.kd_kecamatan AS kode_kecamatan,
                satu.kd_kelurahan AS kode_kelurahan
            FROM
            (
                SELECT
                    \"KD_KECAMATAN\",
                    \"KD_KELURAHAN\",
                    '$tahun' AS tahun,
                    SUM(\"JML_SPPT_YG_DIBAYAR\" - \"DENDA_SPPT\") AS NOMINAL,
                    COUNT(*) AS NOP 
                FROM
                    \"PEMBAYARAN_SPPT\" 
                WHERE
                    EXTRACT(YEAR FROM \"TGL_PEMBAYARAN_SPPT\") = $tahun
                    AND EXTRACT(MONTH FROM \"TGL_PEMBAYARAN_SPPT\") <= $bulan
                GROUP BY
                    '$tahun',
                    \"KD_KECAMATAN\",
                    \"KD_KELURAHAN\" 
            ) SATU
            LEFT JOIN (
                SELECT
                    \"KD_KECAMATAN\",
                    \"KD_KELURAHAN\",
                    '$tahun' AS tahun,
                    EXTRACT(MONTH FROM \"TGL_PEMBAYARAN_SPPT\") AS bulan,
                    SUM(\"JML_SPPT_YG_DIBAYAR\" - \"DENDA_SPPT\") AS NOMINAL,
                    COUNT(*) AS NOP 
                FROM
                    \"PEMBAYARAN_SPPT\" 
                WHERE
                    EXTRACT(YEAR FROM \"TGL_PEMBAYARAN_SPPT\") = $tahun 
                    AND EXTRACT(MONTH FROM \"TGL_PEMBAYARAN_SPPT\") = $bulan
                GROUP BY
                    '$tahun',
                    EXTRACT(MONTH FROM \"TGL_PEMBAYARAN_SPPT\"),
                    \"KD_KECAMATAN\",
                    \"KD_KELURAHAN\" 
            ) DUA ON SATU.tahun = DUA.tahun 
            AND SATU.\"KD_KECAMATAN\" = DUA.\"KD_KECAMATAN\" 
            AND SATU.\"KD_KELURAHAN\" = DUA.\"KD_KELURAHAN\"
            LEFT JOIN \"REF_KECAMATAN\" kec ON satu.\"KD_KECAMATAN\" = kec.\"KD_KECAMATAN\"
            LEFT JOIN \"REF_KELURAHAN\" kel ON satu.\"KD_KECAMATAN\" = kel.\"KD_KECAMATAN\" 
            AND satu.\"KD_KELURAHAN\" = kel.\"KD_KELURAHAN\"";

            // dd($query);
            $pbb = DB::connection("oracle")->select($query);
            foreach ($pbb as $key => $pbb) {

                $data_pbb = [
                    'tahun' => $pbb->tahun,
                    'bulan' => $pbb->bulan,
                    'nama_rekening' => $pbb->nama_rekening,
                    'kode_rekening' => $pbb->kode_rekening,
                    'penerimaan_per_bulan' => $pbb->penerimaan_per_bulan,
                    'penerimaan_akumulasi' => $pbb->penerimaan_akumulasi,
                    'jumlah_transaksi_per_bulan' => $pbb->jumlah_transaksi_per_bulan,
                    'jumlah_transaksi_akumulasi' => $pbb->jumlah_transaksi_akumulasi,
                    'sumber_data' => $pbb->sumber_data,
                    'tanggal_update' => tgl_full($pbb->tanggal_update, 2),
                    'kecamatan' => $pbb->kecamatan,
                    'kelurahan' => $pbb->kelurahan,
                ];

                $cek_data_pbb = DB::table('data.penerimaan_bulanan')->where('tahun', $pbb->tahun)->where('bulan', $pbb->bulan)->where('nama_rekening', $pbb->nama_rekening)->count();
                // dd($cek_data_pbb);
                if ($cek_data_pbb > 0) {
                    DB::table('data.penerimaan_bulanan')->where('tahun', $pbb->tahun)->where('bulan', $pbb->bulan)->where('nama_rekening', $pbb->nama_rekening)->delete();
                }
                DB::table('data.penerimaan_bulanan')->insert($data_pbb);
            }
            $query_bphtb = "SELECT * FROM (
                SELECT 
                    tahun, 
                    bulan, 
                    nama_rekening, 
                    kode_rekening, 
                    penerimaan_per_bulan,
                    SUM(abc.penerimaan_per_bulan) OVER (PARTITION BY abc.tahun ORDER BY abc.bulan) AS penerimaan_akumulasi,
                    jumlah_transaksi_per_bulan,
                    SUM(abc.jumlah_transaksi_per_bulan) OVER (PARTITION BY abc.tahun ORDER BY abc.bulan) AS jumlah_transaksi_akumulasi,
                    sumber_data,
                    tanggal_update,
                    kecamatan,
                    kelurahan
                FROM (
                    SELECT 
                        EXTRACT(YEAR FROM \"TANGGALBAYAR\") AS tahun,
                        EXTRACT(MONTH FROM \"TANGGALBAYAR\") AS bulan,
                        'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)' AS nama_rekening,
                        '' AS kode_rekening,
                        SUM(\"JUMLAHHARUSDIBAYAR\") AS penerimaan_per_bulan,
                        COUNT(\"id\") AS jumlah_transaksi_per_bulan,
                        'BPHTB' AS sumber_data,
                        NOW() AS tanggal_update,
                        '' AS kecamatan,
                        '' AS kelurahan
                    FROM 
                        tb_sspd_bphtb
                    WHERE 
                        \"DELETED_AT\" IS NULL AND 
                        \"TANGGALBAYAR\" IS NOT NULL AND 
                        \"JUMLAHHARUSDIBAYAR\" > 0
                    GROUP BY 
                        EXTRACT(YEAR FROM \"TANGGALBAYAR\"),
                        EXTRACT(MONTH FROM \"TANGGALBAYAR\")
                ) AS abc
            ) AS x 
            WHERE tahun = $tahun
            AND bulan = $bulan";
            $bphtb = DB::connection("pgsql_bphtb")->select($query_bphtb);
            // dd($bphtb);
            foreach ($bphtb as $key => $bphtb) {
                $data_bphtb = [
                    'tahun' => $bphtb->tahun,
                    'bulan' => $bphtb->bulan,
                    'nama_rekening' => $bphtb->nama_rekening,
                    'kode_rekening' => $bphtb->kode_rekening,
                    'penerimaan_per_bulan' => $bphtb->penerimaan_per_bulan,
                    'penerimaan_akumulasi' => $bphtb->penerimaan_akumulasi,
                    'jumlah_transaksi_per_bulan' => $bphtb->jumlah_transaksi_per_bulan,
                    'jumlah_transaksi_akumulasi' => $bphtb->jumlah_transaksi_akumulasi,
                    'sumber_data' => $bphtb->sumber_data,
                    'tanggal_update' => tgl_full($bphtb->tanggal_update, 2),
                    'kecamatan' => $bphtb->kecamatan,
                    'kelurahan' => $bphtb->kelurahan,
                ];
                $cek_data_bphtb = DB::table('data.penerimaan_bulanan')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('nama_rekening', $bphtb->nama_rekening)->count();
                // dd($cek_data_bphtb);
                if ($cek_data_bphtb > 0) {
                    DB::table('data.penerimaan_bulanan')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('nama_rekening', $bphtb->nama_rekening)->delete();
                }
                DB::table('data.penerimaan_bulanan')->insert($data_bphtb);
            }

            // $query_pdl_lama = "select * from (
            // SELECT
            //         tahun,
            //         bulan,
            //         nama_rekening,
            //         kode_rekening,
            //         tanggal_update,
            //         penerimaan_per_bulan,
            //         SUM ( penerimaan_per_bulan ) OVER ( PARTITION BY nama_rekening, kecamatan, kelurahan, tahun ORDER BY kode_akun_pajak, kecamatan, kelurahan, tahun DESC, bulan ) AS penerimaan_akumulasi,
            //         jumlah_transaksi_per_bulan,
            //         SUM ( jumlah_transaksi_per_bulan ) OVER ( PARTITION BY nama_rekening, kecamatan, kelurahan, tahun ORDER BY kode_akun_pajak, kecamatan, kelurahan, tahun DESC, bulan ) AS jumlah_transaksi_akumulasi,
            //         'SIMPADAMA' AS sumber_data,
            //         kecamatan,
            //         kelurahan 
            // FROM
            //         (
            //         SELECT EXTRACT
            //                 ( YEAR FROM A.tanggal_diterima ) AS tahun,
            //                 EXTRACT ( MONTH FROM A.tanggal_diterima ) AS bulan,
            //                 nama_kecamatan kecamatan,
            //                 nama_desa kelurahan,
            //                 b.nama_pajak AS nama_rekening,
            //                 A.updated_at as tanggal_update,
            //                 b.kode_rekening,
            //                 A.kode_akun_pajak,
            //                 SUM ( A.jumlah_pembayaran :: INT ) AS penerimaan_per_bulan,
            //                 COUNT ( * ) AS jumlah_transaksi_per_bulan 
            //         FROM
            //                 DATA.tb_penerimaan
            //                 A LEFT JOIN master.tb_jenis_pajak b ON A.kode_akun_pajak :: INT = b.
            //                 ID LEFT JOIN DATA.tb_op C ON A.nop = C.nop
            //                 LEFT JOIN master.kecamatan kec ON C.kode_kecamatan = kec.kode_kecamatan
            //                 LEFT JOIN master.desa des ON C.kode_desa = des.kode_desa 
            //         WHERE
            //                 ntpp IS NOT NULL 
            //                 AND A.deleted_at IS NULL 
            //         GROUP BY
            //                 A.kode_akun_pajak,
            //                 b.nama_pajak,
            //                 b.kode_rekening,
            //                 tanggal_update,
            //                 nama_kecamatan,
            //                 nama_desa,
            //                 EXTRACT ( YEAR FROM A.tanggal_diterima ),
            //                 EXTRACT ( MONTH FROM A.tanggal_diterima ) 
            //         ) x 
            // ORDER BY
            //         nama_rekening,
            //         kecamatan,
            //         kelurahan,
            //         tahun DESC,
            //         bulan DESC
            // ) x
            // where tahun= $tahun and bulan=$bulan";

            $query_pdl = "
            select * from (
                SELECT
                        tahun,
                        bulan,
                        nama_rekening,
                        kode_rekening,
                        CURRENT_DATE as tanggal_update,
                        penerimaan_per_bulan,
                        SUM ( penerimaan_per_bulan ) OVER ( PARTITION BY nama_rekening, kecamatan, kelurahan, tahun ORDER BY kode_akun_pajak, kecamatan, kelurahan, tahun DESC, bulan ) AS penerimaan_akumulasi,
                        jumlah_transaksi_per_bulan,
                        SUM ( jumlah_transaksi_per_bulan ) OVER ( PARTITION BY nama_rekening, kecamatan, kelurahan, tahun ORDER BY kode_akun_pajak, kecamatan, kelurahan, tahun DESC, bulan ) AS jumlah_transaksi_akumulasi,
                        'SIMPADAMA' AS sumber_data,
                        kecamatan,
                        kelurahan 
                FROM
                        (select thn tahun, bln bulan, kecamatan2 kecamatan, kelurahan2 kelurahan,
                                    nm_rek nama_rekening, kd_rek kode_rekening,
                                    kd_akn_pjk kode_akun_pajak, coalesce(penerimaan_per_bulan,0) penerimaan_per_bulan,
                                    coalesce(jumlah_transaksi_per_bulan,0) jumlah_transaksi_per_bulan
                                 from (
                                    select * from (
                                        SELECT
                                        extract (year from ab) as thn, extract (month from ab) bln
                                        FROM
                                        generate_series (
                                                        --selector thn
                                                        to_date(concat($tahun,'0101'),'yyyymmdd'),
                                                        --selector thn
                                                        to_date(concat($tahun,'1231'),'yyyymmdd'),
                                                        '1 month'
                                        ) AS ab
                                        ) x
                                        left join
                                        (
                                        SELECT distinct
                                                                        nama_kecamatan kecamatan2,
                                                                        nama_desa kelurahan2,
                                                                        b2.nama_pajak AS nm_rek,
                                                                        b2.kode_rekening as kd_rek,
                                                                        kode_akun_pajak kd_akn_pjk
                                                        FROM
                                                                        DATA.tb_penerimaan
                                                                        A2 LEFT JOIN master.tb_jenis_pajak b2 ON A2.kode_akun_pajak :: INT = b2.
                                                                        ID LEFT JOIN DATA.tb_op C2 ON A2.nop = C2.nop
                                                                        LEFT JOIN master.kecamatan kec2 ON C2.kode_kecamatan = kec2.kode_kecamatan
                                                                        LEFT JOIN master.desa des2 ON C2.kode_desa = des2.kode_desa 
                                                        WHERE
                                                                        ntpp IS NOT NULL 
                                                                        AND A2.deleted_at IS NULL
                                                                        and EXTRACT ( YEAR FROM A2.tanggal_diterima ) = $tahun
                                        ) z
                                        on 1=1
                                ) x1 
                                left join
                                (
                        SELECT
                                EXTRACT ( YEAR FROM A.tanggal_diterima ) AS tahun,
                                EXTRACT ( MONTH FROM A.tanggal_diterima ) AS bulan,
                                nama_kecamatan kecamatan,
                                nama_desa kelurahan,
                                b.nama_pajak AS nama_rekening,
                                b.kode_rekening,
                                A.kode_akun_pajak,
                                SUM ( A.jumlah_pembayaran :: INT ) AS penerimaan_per_bulan,
                                COUNT ( * ) AS jumlah_transaksi_per_bulan 
                        FROM
                                DATA.tb_penerimaan
                                A LEFT JOIN master.tb_jenis_pajak b ON A.kode_akun_pajak :: INT = b.
                                ID LEFT JOIN DATA.tb_op C ON A.nop = C.nop
                                LEFT JOIN master.kecamatan kec ON C.kode_kecamatan = kec.kode_kecamatan
                                LEFT JOIN master.desa des ON C.kode_desa = des.kode_desa 
                        WHERE
                                ntpp IS NOT NULL 
                                AND A.deleted_at IS NULL 
                                                --selector tahun
                                                and EXTRACT ( YEAR FROM A.tanggal_diterima ) = $tahun
                        GROUP BY
                                A.kode_akun_pajak,
                                b.nama_pajak,
                                b.kode_rekening,
                                nama_kecamatan,
                                nama_desa,
                                EXTRACT ( YEAR FROM A.tanggal_diterima ),
                                EXTRACT ( MONTH FROM A.tanggal_diterima ) 
                        ) x2
                                on x1.thn=x2.tahun and x1.bln=x2.bulan 
                                and coalesce(x1.kecamatan2,'') = coalesce(x2.kecamatan,'') and coalesce(x1.kelurahan2,'') = coalesce(x2.kelurahan,'')
                                and coalesce(x1.nm_rek,'') = coalesce(x2.nama_rekening,'') and coalesce(x1.kd_rek,'') = coalesce(x2.kode_rekening,'')
                                and coalesce(kode_akun_pajak,'') = coalesce(kd_akn_pjk,'') 
                                ) x12
                ORDER BY
                        nama_rekening,
                        kecamatan,
                        kelurahan,
                        tahun DESC,
                        bulan DESC
                ) x
                -- se;ector bulan
                where bulan=$bulan
                order by  kecamatan, kelurahan, nama_rekening,tahun desc, bulan desc";
            // dd($query_pdl);
            $pdl = DB::connection("pgsql_pdl")->select($query_pdl);
            // dd($pdl);
            foreach ($pdl as $key => $pdl) {

                $data_pdl = [
                    'tahun' => $pdl->tahun,
                    'bulan' => $pdl->bulan,
                    'nama_rekening' => $pdl->nama_rekening,
                    'kode_rekening' => $pdl->kode_rekening,
                    'penerimaan_per_bulan' => $pdl->penerimaan_per_bulan,
                    'penerimaan_akumulasi' => $pdl->penerimaan_akumulasi,
                    'jumlah_transaksi_per_bulan' => $pdl->jumlah_transaksi_per_bulan,
                    'jumlah_transaksi_akumulasi' => $pdl->jumlah_transaksi_akumulasi,
                    'sumber_data' => $pdl->sumber_data,
                    'tanggal_update' => tgl_full($pdl->tanggal_update, 2),
                    'kecamatan' => $pdl->kecamatan,
                    'kelurahan' => $pdl->kelurahan,
                ];

                $cek_data_pdl = DB::table('data.penerimaan_bulanan')->where('tahun', $pdl->tahun)->where('bulan', $pdl->bulan)->where('nama_rekening', $pdl->nama_rekening)->count();
                // dd($cek_data_pdl);
                if ($cek_data_pdl > 0) {
                    DB::table('data.penerimaan_bulanan')->where('tahun', $pdl->tahun)->where('bulan', $pdl->bulan)->where('nama_rekening', $pdl->nama_rekening)->delete();
                }
                DB::table('data.penerimaan_bulanan')->insert($data_pdl);
            }

            $now = date("Y-m-d H:i:s");
            $arrData = ['updated_at' => $now];
            DB::table("data.daftar_data")->where('table', 'PENERIMAAN_BULANAN')->update($arrData);

            // $total_getdata = $cek_data_pbb + $cek_data_pdl + $cek_data_bphtb;
            // dd($total_getdata);
            return response()->json([
                'status' => '1',
                // 'total_data' => $total_getdata
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_penerimaan_harian(Request $request)
    {
        // dd($request->all());
        $tgl = $request->tanggal;
        $tanggal = Carbon::parse($tgl)->format('Y-m-d');
        try {
            $query_pbb = "
            SELECT \"TGL_PEMBAYARAN_SPPT\" AS tanggal, 
            'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)' AS nama_rekening, 
            '' AS kode_rekening,
            SUM(\"JML_SPPT_YG_DIBAYAR\" - \"DENDA_SPPT\") AS penerimaan, 
            COUNT(*) AS jumlah_transaksi, 
            '' AS tempat_bayar, 
            'SISMIOP' AS sumber_data, 
            SYSDATE AS tanggal_update, 
            \"NM_KECAMATAN\" AS kecamatan, 
            \"NM_KELURAHAN\" AS kelurahan
            FROM \"PEMBAYARAN_SPPT\" satu
            LEFT JOIN \"REF_KECAMATAN\" kec ON satu.\"KD_KECAMATAN\" = kec.\"KD_KECAMATAN\"
            LEFT JOIN \"REF_KELURAHAN\" kel ON satu.\"KD_KECAMATAN\" = kel.\"KD_KECAMATAN\" 
            AND satu.\"KD_KELURAHAN\" = kel.\"KD_KELURAHAN\"
            WHERE \"TGL_PEMBAYARAN_SPPT\" = '$tanggal'
            GROUP BY \"NM_KECAMATAN\", \"NM_KELURAHAN\", \"TGL_PEMBAYARAN_SPPT\" 
            ORDER BY \"TGL_PEMBAYARAN_SPPT\"";
            $pbb = DB::connection("oracle")->select($query_pbb);

            foreach ($pbb as $key => $pbb) {
                // dd($pbb);
                $data_pbb = [
                    'tanggal' => tgl_full($pbb->tanggal, 2),
                    'nama_rekening' => $pbb->nama_rekening,
                    'kode_rekening' => $pbb->kode_rekening,
                    'penerimaan' => $pbb->penerimaan,
                    'jumlah_transaksi' => $pbb->jumlah_transaksi,
                    'tempat_bayar' => $pbb->tempat_bayar,
                    'sumber_data' => $pbb->sumber_data,
                    'tanggal_update' => tgl_full($pbb->tanggal_update, 2),
                    'kecamatan' => $pbb->kecamatan,
                    'kelurahan' => $pbb->kelurahan,
                ];

                $cek_data_pbb = DB::table('data.penerimaan_harian')->where('tanggal', tgl_full($pbb->tanggal, 2))->where('kode_rekening', $pbb->kode_rekening)->where('nama_rekening', $pbb->nama_rekening)->count();
                // dd($cek_data_pbb);
                if ($cek_data_pbb > 0) {
                    DB::table('data.penerimaan_harian')->where('tanggal', tgl_full($pbb->tanggal, 2))->where('kode_rekening', $pbb->kode_rekening)->where('nama_rekening', $pbb->nama_rekening)->delete();
                }
                DB::table('data.penerimaan_harian')->insert($data_pbb);
            }

            // $query_bphtb = "select tanggal, 'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)' as nama_rekening, '410116' as kode_rekening, 
            // sum(bayar-denda) as penerimaan, count(*) as jumlah_transaksi, '' as tempat_bayar, 'BPHTB' as sumber_data, now() as tanggal_update 
            // from bphtb_bank
            // where extract (year from tanggal)=extract(year from now()) and extract (month from tanggal)=extract(month from now())
            // and extract (day from tanggal)=extract(day from now())
            // group by tanggal";

            // $bphtb = DB::connection("pgsql_bphtb")->select($query_bphtb);
            // // dd($bphtb);
            // foreach ($bphtb as $key => $bphtb) {
            //     $data_bphtb = [
            //         'tanggal' => tgl_full($bphtb->tanggal,2),
            //         'nama_rekening' => $bphtb->nama_rekening,
            //         'kode_rekening' => $bphtb->kode_rekening,
            //         'penerimaan' => $bphtb->penerimaan,
            //         'jumlah_transaksi' => $bphtb->jumlah_transaksi,
            //         'tempat_bayar' => $bphtb->tempat_bayar,
            //         'sumber_data' => $bphtb->sumber_data,
            //         'tanggal_update' => tgl_full($bphtb->tanggal_update,2),
            //     ];
            //     $cek_data_bphtb = DB::table('data.penerimaan_harian')->where('tanggal',tgl_full($bphtb->tanggal,2))->where('kode_rekening',$bphtb->kode_rekening)->where('nama_rekening',$bphtb->nama_rekening)->count();
            //     // dd($cek_data_bphtb);
            //     if ($cek_data_bphtb > 0) {
            //         DB::table('data.penerimaan_harian')->where('tanggal',tgl_full($bphtb->tanggal,2))->where('kode_rekening',$bphtb->kode_rekening)->where('nama_rekening',$bphtb->nama_rekening)->delete();
            //     }
            //     DB::table('data.penerimaan_harian')->insert($data_bphtb);
            // }

            $query_pdl = "select bcd.tgl as tanggal, t.rekeningnm as nama_rekening,t.rekeningkd as kode_rekening ,bcd.penerimaan, bcd.jumlah_transaksi, '' as tempat_bayar, 'PDL' as sumber_data, date(now()) as tanggal_update
            from 
            (select date(abc.tgl) as tgl, abc.rek,
            sum(abc.jml_bayar-abc.denda) as penerimaan, count(*) as jumlah_transaksi
            from 
            (select substring(re.rekeningkd,1,6) as rek , bc.sspdtgl as tgl, bc.jml_bayar, bc.bunga, bc.denda 
            from 
            (select id, rekening_id, pajak_id
            from pad_spt) ab right join
            (select sspdtgl, spt_id, jml_bayar, bunga, denda
            from pad_sspd where extract(year from sspdtgl)=extract(year from now()) and extract(month from sspdtgl)=extract(month from now())
            and extract(day from sspdtgl)=extract(day from now())) bc on ab.id=bc.spt_id
            left join tblrekening re on ab.rekening_id =re.id) abc
            group by date(abc.tgl), abc.rek) bcd left join tblrekening t on bcd.rek=t.rekeningkd 
            where t.issummary =1";
            $query = "SELECT 
            A.tanggal_diterima :: DATE AS tanggal,
            b.nama_pajak AS nama_rekening,
            b.kode_rekening,
            SUM ( A.jumlah_pembayaran :: INT ) AS penerimaan,
            COUNT ( * ) AS jumlah_transaksi,
            NULL AS tempat_bayar,
            'SIMPADAMA' AS sumber_data,
            now() as tanggal_update,
            nama_kecamatan kecamatan,
            nama_desa kelurahan 
            FROM
                    DATA.tb_penerimaan
                    A LEFT JOIN master.tb_jenis_pajak b ON A.kode_akun_pajak :: INT = b.
                    ID LEFT JOIN DATA.tb_op C ON A.nop = C.nop
                    LEFT JOIN master.kecamatan kec ON C.kode_kecamatan = kec.kode_kecamatan
                    LEFT JOIN master.desa des ON C.kode_desa = des.kode_desa
            WHERE
                    ntpp IS NOT NULL 
                    AND A.deleted_at IS NULL 
                    and A.tanggal_diterima::date = '$tanggal'
            GROUP BY
                    nama_kecamatan,
                    nama_desa,
                    A.tanggal_diterima :: DATE,
                    b.nama_pajak,
                    b.kode_rekening 
            ORDER BY
                    b.kode_rekening,
                    nama_kecamatan,
                    nama_desa,
                    A.tanggal_diterima :: DATE DESC";

            $pdl = DB::connection("pgsql_pdl")->select($query);
            // dd($pdl);

            foreach ($pdl as $key => $pdl) {

                $data_pdl = [
                    'tanggal' => tgl_full($pdl->tanggal, 2),
                    'nama_rekening' => $pdl->nama_rekening,
                    'kode_rekening' => $pdl->kode_rekening,
                    'penerimaan' => $pdl->penerimaan,
                    'jumlah_transaksi' => $pdl->jumlah_transaksi,
                    'tempat_bayar' => $pdl->tempat_bayar,
                    'sumber_data' => $pdl->sumber_data,
                    'tanggal_update' => tgl_full($pdl->tanggal_update, 2),
                    'kecamatan' => $pdl->kecamatan,
                    'kelurahan' => $pdl->kelurahan,
                ];

                $cek_data_pdl = DB::table('data.penerimaan_harian')->where('tanggal', tgl_full($pdl->tanggal, 2))->where('kode_rekening', $pdl->kode_rekening)->where('nama_rekening', $pdl->nama_rekening)->count();
                // dd($cek_data_pdl);
                if ($cek_data_pdl > 0) {
                    DB::table('data.penerimaan_harian')->where('tanggal', tgl_full($pdl->tanggal, 2))->where('kode_rekening', $pdl->kode_rekening)->where('nama_rekening', $pdl->nama_rekening)->delete();
                }
                DB::table('data.penerimaan_harian')->insert($data_pdl);
            }

            $query_bphtb = "SELECT
            \"TANGGALBAYAR\" AS tanggal,
            'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)' AS nama_rekening,
            '' AS kode_rekening,
            SUM(\"JUMLAHHARUSDIBAYAR\") AS penerimaan,
            COUNT(\"id\") AS jumlah_transaksi,
            '' AS tempat_bayar,
            'BPHTB' AS sumber_data,
            NOW() AS tanggal_update,
            '' AS kecamatan,
            '' AS kelurahan
          FROM tb_sspd_bphtb
          WHERE
            \"DELETED_AT\" IS NULL AND \"TANGGALBAYAR\" IS NOT NULL AND \"JUMLAHHARUSDIBAYAR\" > 0
            AND \"TANGGALBAYAR\"::DATE = '$tanggal'
          GROUP BY
            \"TANGGALBAYAR\"";


            $bphtb = DB::connection("pgsql_bphtb")->select($query_bphtb);
            // dd($bphtb);

            foreach ($bphtb as $key => $bphtb) {

                $data_bphtb = [
                    'tanggal' => tgl_full($bphtb->tanggal, 2),
                    'nama_rekening' => $bphtb->nama_rekening,
                    'kode_rekening' => $bphtb->kode_rekening,
                    'penerimaan' => $bphtb->penerimaan,
                    'jumlah_transaksi' => $bphtb->jumlah_transaksi,
                    'tempat_bayar' => $bphtb->tempat_bayar,
                    'sumber_data' => $bphtb->sumber_data,
                    'tanggal_update' => tgl_full($bphtb->tanggal_update, 2),
                    'kecamatan' => $bphtb->kecamatan,
                    'kelurahan' => $bphtb->kelurahan,
                ];

                $cek_data_bphtb = DB::table('data.penerimaan_harian')->where('tanggal', tgl_full($bphtb->tanggal, 2))->where('kode_rekening', $bphtb->kode_rekening)->where('nama_rekening', $bphtb->nama_rekening)->count();
                // dd($cek_data_bphtb);
                if ($cek_data_bphtb > 0) {
                    DB::table('data.penerimaan_harian')->where('tanggal', tgl_full($bphtb->tanggal, 2))->where('kode_rekening', $bphtb->kode_rekening)->where('nama_rekening', $bphtb->nama_rekening)->delete();
                }
                DB::table('data.penerimaan_harian')->insert($data_bphtb);
            }


            $now = date("Y-m-d H:i:s");
            $arrData = ['updated_at' => $now];
            DB::table("data.daftar_data")->where('table', 'PENERIMAAN_HARIAN')->update($arrData);

            // $total_getdata = $cek_data_pbb + $cek_data_bphtb + $cek_data_bphtb;
            // dd($total_getdata);
            return response()->json([
                'status' => '1',
                // 'total_data' => $total_getdata
            ]);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_penerimaan_tahun_sppt(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        if (is_null($bulan)) {
            $bulan = date('m');
        }
        // dd($bulan);
        try {
            $query = "SELECT ABC.TAHUN_BAYAR, ABC.BULAN_BAYAR, ABC.TAHUN_SPPT, ABC.NOMINAL_POKOK, ABC.NOMINAL_DENDA, ABC.NOMINAL_TERIMA, ABC.NOP,
            'SISMIOP' AS SUMBER_DATA, SYSDATE AS TANGGAL_UPDATE
            FROM
            (SELECT EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT) AS TAHUN_BAYAR, EXTRACT(MONTH FROM TGL_PEMBAYARAN_SPPT) AS BULAN_BAYAR, THN_PAJAK_SPPT AS TAHUN_SPPT,
            SUM(JML_SPPT_YG_DIBAYAR-DENDA_SPPT) AS NOMINAL_POKOK, SUM(DENDA_SPPT) AS NOMINAL_DENDA, SUM(JML_SPPT_YG_DIBAYAR) AS NOMINAL_TERIMA, COUNT(*) AS NOP
            FROM PEMBAYARAN_SPPT
            GROUP BY EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT), EXTRACT(MONTH FROM TGL_PEMBAYARAN_SPPT), THN_PAJAK_SPPT
            HAVING EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT)=$tahun AND EXTRACT(MONTH FROM TGL_PEMBAYARAN_SPPT)=$bulan
            ORDER BY EXTRACT(YEAR FROM TGL_PEMBAYARAN_SPPT), EXTRACT(MONTH FROM TGL_PEMBAYARAN_SPPT), THN_PAJAK_SPPT) ABC
            ";

            // dd($query);
            $data = DB::connection("oracle")->select($query);
            // dd($data);

            foreach ($data as $key => $data) {
                $data_get = [
                    'tahun_bayar' => $data->tahun_bayar,
                    'bulan_bayar' => $data->bulan_bayar,
                    'tahun_sppt' => $data->tahun_sppt,
                    'nominal_pokok' => $data->nominal_pokok,
                    'nominal_denda' => $data->nominal_denda,
                    'nominal_terima' => $data->nominal_terima,
                    'nop' => $data->nop,
                    'sumber_data' => $data->sumber_data,
                    'tanggal_update' => tgl_full($data->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.penerimaan_tahun_sppt')->where('tahun_bayar', $data->tahun_bayar)->where('bulan_bayar', $data->bulan_bayar)->where('tahun_sppt', $data->tahun_sppt)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.penerimaan_tahun_sppt')->where('tahun_bayar', $data->tahun_bayar)->where('bulan_bayar', $data->bulan_bayar)->where('tahun_sppt', $data->tahun_sppt)->delete();
                }
                DB::table('data.penerimaan_tahun_sppt')->insert($data_get);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'PENERIMAAN_TAHUN_SPPT')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '0' . $th
            ]);
        }
    }

    public function getdata_tunggakan(Request $request)
    {
        //         $query =
        //             " SELECT
        //         ABC.THN_PAJAK_SPPT AS tahun_sppt,
        //         ABC.NOMINAL AS nominal_baku,
        //         DEF.POKOK AS nominal_pokok,
        //         DEF.DENDA AS nominal_denda,
        //         DEF.TERIMA AS nominal_terima,
        //         ABC.NOP AS nop_baku,
        //         DEF.NOPBAYAR AS nop_bayar,
        //         CMT.NM_KECAMATAN as kecamatan,
        //         LRH.NM_KELURAHAN as kelurahan,
        //         'SISMIOP' AS sumber_data,
        //         SYSDATE AS tanggal_update 
        // FROM
        //         (
        //         SELECT
        //                 THN_PAJAK_SPPT,
        //                 KD_KECAMATAN,
        //                 KD_KELURAHAN,
        //                 COUNT( * ) AS NOP,
        //                 SUM( PBB_YG_HARUS_DIBAYAR_SPPT ) AS NOMINAL 
        //         FROM
        //                 (
        //                 SELECT
        //                         KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP AS nopp,
        //                         THN_PAJAK_SPPT,
        //                         KD_KECAMATAN,
        //                         KD_KELURAHAN,
        //                         SUM( PBB_YG_HARUS_DIBAYAR_SPPT ) PBB_YG_HARUS_DIBAYAR_SPPT 
        //                 FROM
        //                         SPPT 
        //                 GROUP BY
        //                         KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP,
        //                         THN_PAJAK_SPPT,
        //                         KD_KECAMATAN,
        //                         KD_KELURAHAN 
        //                 ) q1 
        //         GROUP BY
        //                 THN_PAJAK_SPPT,
        //                 KD_KECAMATAN,
        //                 KD_KELURAHAN 
        //         ) ABC
        //         LEFT JOIN (
        //         SELECT
        //                 THN_PAJAK_SPPT,
        //                 KD_KECAMATAN,
        //                 KD_KELURAHAN,
        //                 COUNT( * ) AS NOPBAYAR,
        //                 SUM( JML_SPPT_YG_DIBAYAR - DENDA_SPPT ) AS POKOK,
        //                 SUM( DENDA_SPPT ) AS DENDA,
        //                 SUM( JML_SPPT_YG_DIBAYAR ) AS TERIMA 
        //         FROM
        //                 (
        //                 SELECT
        //                         KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP AS nopp,
        //                         THN_PAJAK_SPPT,
        //                         KD_KECAMATAN,
        //                         KD_KELURAHAN,
        //                         SUM( JML_SPPT_YG_DIBAYAR ) AS JML_SPPT_YG_DIBAYAR,
        //                         SUM( DENDA_SPPT ) AS DENDA_SPPT 
        //                 FROM
        //                         PEMBAYARAN_SPPT 
        //                 GROUP BY
        //                         KD_PROPINSI || '.' || KD_DATI2 || '.' || KD_KECAMATAN || '.' || KD_KELURAHAN || '.' || KD_BLOK || '.' || NO_URUT || '.' || KD_JNS_OP,
        //                         THN_PAJAK_SPPT,
        //                         KD_KECAMATAN,
        //                         KD_KELURAHAN 
        //                 ) q2 
        //         GROUP BY
        //                 THN_PAJAK_SPPT,
        //                 KD_KECAMATAN,
        //                 KD_KELURAHAN 
        //         ) DEF ON ABC.THN_PAJAK_SPPT = DEF.THN_PAJAK_SPPT 
        //         AND ABC.KD_KECAMATAN = DEF.KD_KECAMATAN 
        //         AND ABC.KD_KELURAHAN = DEF.KD_KELURAHAN
        //         LEFT JOIN REF_KECAMATAN CMT ON ABC.KD_KECAMATAN = CMT.KD_KECAMATAN
        //         LEFT JOIN REF_KELURAHAN LRH ON ABC.KD_KECAMATAN = LRH.KD_KECAMATAN 
        //         AND ABC.KD_KELURAHAN = LRH.KD_KELURAHAN
        //         ";

        $tahun = $request->tahun;
        // $bulan = $request->bulan;
        // dd($tahun);

        try {
            $data = DB::connection('server_pbb')
                ->table('detail_tunggakan_pbb')
                ->where('tahun_sppt', $tahun)
                ->get();


            // dd($data);
            foreach ($data as $key => $data) {
                $now1 = date("Y-m-d H:i:s");
                $get_data = [
                    'nop' => $data->nop,
                    'tahun_sppt' => $data->tahun_sppt,
                    'nama_rekening' => $data->nama_rekening,
                    'pbb_terutang' => $data->pbb_terutang,
                    'nominal_denda' => $data->nominal_denda,
                    'nominal_tunggakan' => $data->nominal_tunggakan,
                    'tahun_pajak' => $data->tahun_pajak,
                    'sumber_data' => 'server_pbb',
                    'kecamatan' => $data->kecamatan,
                    'kelurahan' => $data->kelurahan,
                    'created_at' => $data->created_at,
                    'tanggal_update' => $now1,
                ];
                // dd($get_data);
                $cek_data = DB::connection('pgsql_pbb')->table('data.detail_tunggakan_pbb')->where('tahun_sppt', $data->tahun_sppt)->where('nop', $data->nop)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::connection('pgsql_pbb')->table('data.detail_tunggakan_pbb')->where('tahun_sppt', $data->tahun_sppt)->where('nop', $data->nop)->delete();
                }
                // dd($cek_data);
                DB::connection('pgsql_pbb')->table('data.detail_tunggakan_pbb')->insert($get_data);

                $now = date("Y-m-d H:i:s");
                // dd($now);
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'TUNGGAKAN')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '0' . $th
            ]);
        }
    }

    public function getdata_tunggakan_level()
    {
        try {
            $query = " SELECT 
            DF.TINGKAT as tingkat,
            DF.NOMINAL as nominal, 
            DF.NOP as nop, 
            CMT.NM_KECAMATAN as kecamatan, 
            LRH.NM_KELURAHAN as kelurahan, 
            'SISMIOP' AS sumber_data, 
            SYSDATE AS tanggal_update
            FROM
            (SELECT CD.TINGKAT,
            SUM(CD.TUNGGAKANNOMINAL) AS NOMINAL, SUM(CD.TUNGGAKANNOP) AS NOP, CD.KD_KECAMATAN, CD.KD_KELURAHAN
            FROM
            (SELECT CD.KD_PROPINSI, CD.KD_DATI2, CD.KD_KECAMATAN, CD.KD_KELURAHAN, CD.KD_BLOK, CD.NO_URUT, CD.KD_JNS_OP, CD.TUNGGAKANNOP, CD.TUNGGAKANNOMINAL,
            CASE WHEN CD.TUNGGAKANNOP=1 THEN 'RINGAN' 
            WHEN CD.TUNGGAKANNOP>1 AND CD.TUNGGAKANNOP < 5 THEN 'SEDANG'
            WHEN CD.TUNGGAKANNOP>=5 THEN 'BERAT' END AS TINGKAT
            FROM
            (SELECT BC.KD_PROPINSI, BC.KD_DATI2, BC.KD_KECAMATAN, BC.KD_KELURAHAN, BC.KD_BLOK, BC.NO_URUT, BC.KD_JNS_OP, 
            COUNT(*) AS TUNGGAKANNOP, SUM(BC.PBB_YG_HARUS_DIBAYAR_SPPT) AS TUNGGAKANNOMINAL
            FROM
            (SELECT KD_PROPINSI, KD_DATI2, KD_KECAMATAN, KD_KELURAHAN, KD_BLOK, NO_URUT, KD_JNS_OP, THN_PAJAK_SPPT, PBB_YG_HARUS_DIBAYAR_SPPT
            FROM SPPT
            WHERE STATUS_PEMBAYARAN_SPPT='0' 
            AND THN_PAJAK_SPPT >=2002 ) BC
            GROUP BY BC.KD_PROPINSI, BC.KD_DATI2, BC.KD_KECAMATAN, BC.KD_KELURAHAN, BC.KD_BLOK, BC.NO_URUT, BC.KD_JNS_OP) CD) CD
            GROUP BY CD.TINGKAT,CD.KD_KECAMATAN, CD.KD_KELURAHAN) DF
            LEFT JOIN REF_KECAMATAN CMT ON DF.KD_KECAMATAN=CMT.KD_KECAMATAN
            LEFT JOIN REF_KELURAHAN LRH ON DF.KD_KECAMATAN=LRH.KD_KECAMATAN AND DF.KD_KELURAHAN=LRH.KD_KELURAHAN
            ORDER BY DF.TINGKAT,CMT.NM_KECAMATAN,LRH.NM_KELURAHAN
            ";

            $data = DB::connection("oracle")->select($query);
            // dd($data);
            foreach ($data as $key => $data) {
                $data_data = [
                    'level' => $data->tingkat,
                    'kecamatan' => $data->kecamatan,
                    'kelurahan' => $data->kelurahan,
                    'nominal' => $data->nominal,
                    'nop' => $data->nop,
                    'sumber_data' => $data->sumber_data,
                    'tanggal_update' => tgl_full($data->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.tunggakan_level')->where('level', $data->tingkat)->where('kecamatan', $data->kecamatan)->where('kelurahan', $data->kelurahan)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.tunggakan_level')->where('level', $data->tingkat)->where('kecamatan', $data->kecamatan)->where('kelurahan', $data->kelurahan)->delete();
                }
                DB::table('data.tunggakan_level')->insert($data_data);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'TUNGGAKAN_LEVEL')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            // return $th;
            return response()->json([
                'status' => '0' . $th
            ]);
        }
    }

    public function getdata_objek_pajak_wilayah(Request $request)
    {
        $tahun = $request->tahun;
        try {
            $query = " select * from (
                SELECT ABC.THN_PAJAK_SPPT AS TAHUN, CMT.NM_KECAMATAN AS KECAMATAN, LRH.NM_KELURAHAN AS KELURAHAN, ABC.NOP, ABC.NOMINAL,
                 'SISMIOP' AS SUMBER_DATA, SYSDATE AS TANGGAL_UPDATE
                 FROM
                 (SELECT THN_PAJAK_SPPT, KD_KECAMATAN, KD_KELURAHAN, COUNT(*) AS NOP, SUM(PBB_YG_HARUS_DIBAYAR_SPPT) AS NOMINAL
                 FROM SPPT
                 GROUP BY THN_PAJAK_SPPT, KD_KECAMATAN, KD_KELURAHAN) ABC 
                 LEFT JOIN REF_KECAMATAN CMT ON ABC.KD_KECAMATAN=CMT.KD_KECAMATAN
                 LEFT JOIN REF_KELURAHAN LRH ON ABC.KD_KECAMATAN=LRH.KD_KECAMATAN AND ABC.KD_KELURAHAN=LRH.KD_KELURAHAN
                 ) x where tahun= $tahun
            ";

            $data = DB::connection("oracle")->select($query);
            // dd($data);
            foreach ($data as $key => $data) {
                $data_data = [
                    'tahun' => $data->tahun,
                    'kecamatan' => $data->kecamatan,
                    'kelurahan' => $data->kelurahan,
                    'nominal' => $data->nominal,
                    'nop' => $data->nop,
                    'sumber_data' => $data->sumber_data,
                    'tanggal_update' => tgl_full($data->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.objek_pajak_wilayah')->where('tahun', $data->tahun)->where('kecamatan', $data->kecamatan)->where('kelurahan', $data->kelurahan)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.objek_pajak_wilayah')->where('tahun', $data->tahun)->where('kecamatan', $data->kecamatan)->where('kelurahan', $data->kelurahan)->delete();
                }
                DB::table('data.objek_pajak_wilayah')->insert($data_data);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'OBJEK_PAJAK_WILAYAH')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_rekap_tunggakan(Request $request)
    {
        // $tahun = $request->tahun;
        // $bulan = $request->bulan;
        try {

            // $query = "select abc.tahun, abc.bulan, t.rekeningnm as nama_rekening, abc.rek as kode_rekening, sum(abc.ketetapan) as nominal_ketetapan, sum(abc.jumlah) as jumlah_transaksi,'PDL' as sumber_data, now() as tanggal_update
            // from 
            // (select ab.tahun, ab.bulan, substring(t.rekeningkd,1,6) as rek, ab.ketetapan, ab.jumlah
            // from 
            // (select extract (year from create_date) as tahun, extract (month from create_date) as bulan, rekening_id, count(*) as jumlah, sum(pajak_terhutang) as ketetapan
            // from pad_spt 
            // where extract (year from create_date)=extract (year from now()) and extract (month from create_date)=extract (month from now()) and status_pembayaran=0
            // group by extract (year from create_date), extract (month from create_date), rekening_id) ab
            // left join tblrekening t on ab.rekening_id=t.id) abc
            // left join tblrekening t on abc.rek=t.rekeningkd 
            // where t.issummary =1
            // group by abc.tahun, abc.bulan, t.rekeningnm, abc.rek
            // ";
            $query = "SELECT
            masa_pajak_tahun :: INT AS tahun,
            masa_pajak_bulan :: INT AS bulan,
            b.nama_pajak AS nama_rekening,
            b.kode_rekening,
            SUM ( A.jumlah_pembayaran :: bigint) AS nominal_ketetapan,
            COUNT ( * ) AS jumlah_ketetapan,
            'SIMPATDAMA' AS sumber_data,
            now( ) AS tanggal_update,
            nama_kecamatan kecamatan,
            nama_desa kelurahan 
        FROM
            DATA.tb_penerimaan
            A LEFT JOIN master.tb_jenis_pajak b ON A.kode_akun_pajak :: INT = b.
            ID LEFT JOIN DATA.tb_op C ON A.nop = C.nop
            LEFT JOIN master.kecamatan kec ON C.kode_kecamatan = kec.kode_kecamatan
            LEFT JOIN master.desa des ON C.kode_desa = des.kode_desa 
        WHERE
            ntpp IS NULL 
            AND A.deleted_at IS NULL 
        GROUP BY
            nama_kecamatan,
            nama_desa,
            masa_pajak_tahun :: INT,
            masa_pajak_bulan :: INT,
            b.nama_pajak,
            b.kode_rekening 
        ORDER BY
            nama_kecamatan,
            nama_desa,
            b.kode_rekening,
            masa_pajak_tahun :: INT DESC,
            masa_pajak_bulan :: INT DESC";

            $data = DB::connection("pgsql_pdl")->select($query);
            // dd($data);
            foreach ($data as $key => $data) {
                $insert_data = [
                    'tahun' => $data->tahun,
                    'bulan' => $data->bulan,
                    'nama_rekening' => $data->nama_rekening,
                    'kode_rekening' => $data->kode_rekening,
                    'nominal_ketetapan' => $data->nominal_ketetapan,
                    'jumlah_ketetapan' => $data->jumlah_ketetapan,
                    'sumber_data' => $data->sumber_data,
                    'tanggal_update' => tgl_full($data->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.rekap_tunggakan')->where('tahun', $data->tahun)->where('bulan', $data->bulan)->where('nama_rekening', $data->nama_rekening)->where('kode_rekening', $data->kode_rekening)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.rekap_tunggakan')->where('tahun', $data->tahun)->where('bulan', $data->bulan)->where('nama_rekening', $data->nama_rekening)->where('kode_rekening', $data->kode_rekening)->delete();
                }
                DB::table('data.rekap_tunggakan')->insert($insert_data);
            }


            // $query_bphtb = "  select extract (year from created) as tahun, extract(month from created) as bulan, 'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)' as nama_rekening, '410116' as kode_rekening, 
            // sum(bphtb_harus_dibayarkan) as nominal_ketetapan, count(*) as jumlah_transaksi, 'BPHTB' as sumber_data, now() as tanggal_update 
            // from bphtb_sspd
            // where extract (year from created)=$tahun and extract (month from created)=$bulan
            // and status_pembayaran=0
            // group by extract (year from created), extract(month from created)
            // order by extract (year from created) desc, extract(month from created) desc
            // ";

            // $bphtb = DB::connection("pgsql_bphtb")->select($query_bphtb);
            // // dd($bphtb);
            // foreach ($bphtb as $key => $bphtb) {
            //     $insert_bphtb = [
            //         'tahun' => $bphtb->tahun,
            //         'bulan' => $bphtb->bulan,
            //         'nama_rekening' => $bphtb->nama_rekening,
            //         'kode_rekening' => $bphtb->kode_rekening,
            //         'nominal_ketetapan' => $bphtb->nominal_ketetapan,
            //         'jumlah_ketetapan' => $bphtb->jumlah_transaksi,
            //         'sumber_data' => $bphtb->sumber_data,
            //         'tanggal_update' => tgl_full($bphtb->tanggal_update, 2),
            //     ];
            //     $cek_bphtb = DB::table('data.rekap_tunggakan')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('nama_rekening', $bphtb->nama_rekening)->where('kode_rekening', $bphtb->kode_rekening)->count();
            //     // dd($cek_bphtb);
            //     if ($cek_bphtb > 0) {
            //         DB::table('data.rekap_tunggakan')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('nama_rekening', $bphtb->nama_rekening)->where('kode_rekening', $bphtb->kode_rekening)->delete();
            //     }
            //     DB::table('data.rekap_tunggakan')->insert($insert_bphtb);

            //     $now = date("Y-m-d H:i:s");
            //     $arrData = ['updated_at' => $now];
            //     DB::table("data.daftar_data")->where('table', 'REKAP_TUNGGAKAN')->update($arrData);
            // }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_detail_tunggakan(Request $request)
    {
        try {
            // $query = " select abc.id as nop, abc.npwpd as npwpd, abc.tahun, abc.bulan, t2.rekeningnm as nama_rekening, abc.rek as kode_rekening, abc.nominal_ketetapan,
            // abc.nama_wp, abc.alamat_wp, abc.nama_op, abc.alamat_op, abc.nomor_ketetapan, abc.tanggal_ketetapan, abc.masa_awal, abc.masa_akhir, abc.tanggal_jatuh_tempo, 'PDL' as sumber_data, now() as tanggal_update
            // from 
            // (select op.id, wp.npwpd, ab.tahun, ab.bulan, substring(t.rekeningkd,1,6) as rek, ab.pajak_terhutang as nominal_ketetapan,
            // wp.customernm as nama_wp , wp.alamat || ' ' || lrhwp.kelurahannm || ' ' || cmtwp.kecamatannm || ' ' || wp.kabupaten as alamat_wp, 
            // op.opnm as nama_op, op.opalamat || ' ' || lrhop.kelurahannm || ' ' || cmtop.kecamatannm as alamat_op,
            // ab.sptno as nomor_ketetapan, ab.tanggal_ketetapan, ab.masa_awal, ab.masa_akhir, ab.tanggal_jatuh_tempo
            // from 
            // (select extract (year from create_date) as tahun, extract (month from create_date) as bulan, rekening_id, pajak_terhutang, sptno,
            // create_date as tanggal_ketetapan, masadari as masa_awal, masasd as masa_akhir, jatuhtempotgl as tanggal_jatuh_tempo, customer_id, customer_usaha_id
            // from pad_spt 
            // where status_pembayaran=0) ab 
            // left join pad_customer wp on ab.customer_id=wp.id 
            // left join pad_customer_usaha op on ab.customer_usaha_id=op.id 
            // left join tblrekening t on ab.rekening_id=t.id 
            // left join tblkecamatan cmtop on op.kecamatan_id = cmtop.id 
            // left join tblkelurahan lrhop on op.kelurahan_id =lrhop.id and op.kecamatan_id =lrhop.kecamatan_id 
            // left join tblkecamatan cmtwp on wp.kecamatan_id = cmtwp.id 
            // left join tblkelurahan lrhwp on wp.kelurahan_id =lrhwp.id and wp.kecamatan_id =lrhwp.kecamatan_id ) abc
            // left join tblrekening t2 on abc.rek=t2.rekeningkd 
            // where t2.issummary =1
            // ";
            $query = "SELECT A
            .nop,
            A.npwpd,
            masa_pajak_tahun :: INT AS tahun,
            masa_pajak_bulan :: INT AS bulan,
            b.nama_pajak AS nama_rekening,
            b.kode_rekening,
            A.jumlah_pembayaran :: BIGINT AS nominal_ketetapan,
            A.nama_wp AS nama_subjek_pajak,
            A.alamat_wp AS alamat_subjek_pajak,
            C.nama AS nama_objek_pajak,
            C.jalan AS alamat_objek_pajak,
            d.tanggal_pendataan AS tanggal_ketetapan,
            A.masa_awal,
            A.masa_akhir,
            A.jatuh_tempo AS tanggal_jatuh_tempo,
            'SIMPADAMA' AS sumber_data,
            now() as tanggal_update,
            nama_kecamatan kecamatan,
            nama_desa kelurahan 
            FROM
                    DATA.tb_penerimaan
                    A LEFT JOIN master.tb_jenis_pajak b ON A.kode_akun_pajak :: INT = b.
                    ID LEFT JOIN tb_op C ON C.nop = A.nop
                    LEFT JOIN tb_ketetapan d ON d.ID = A.id_ketetapan
                    LEFT JOIN DATA.tb_op e ON A.nop = e.nop
                    LEFT JOIN master.kecamatan kec ON e.kode_kecamatan = kec.kode_kecamatan
                    LEFT JOIN master.desa des ON e.kode_desa = des.kode_desa  
            WHERE
                    A.ntpp IS NULL 
                    AND A.deleted_at IS NULL
                    
            ORDER BY
                    b.kode_rekening,
                    d.tanggal_pendataan DESC";

            $pdl = DB::connection("pgsql_pdl")->select($query);
            // dd($pdl);
            foreach ($pdl as $key => $pdl) {
                $data_pdl = [
                    'tahun' => $pdl->tahun,
                    'bulan' => $pdl->bulan,
                    'nop' => $pdl->nop,
                    'npwpd' => $pdl->npwpd,
                    'nama_rekening' => $pdl->nama_rekening,
                    'kode_rekening' => $pdl->kode_rekening,
                    // 'nomor_ketetapan' => $pdl->nomor_ketetapan,
                    'nominal_ketetapan' => $pdl->nominal_ketetapan,
                    'nama_subjek_pajak' => $pdl->nama_subjek_pajak,
                    'alamat_subjek_pajak' => $pdl->alamat_subjek_pajak,
                    'nama_objek_pajak' => $pdl->nama_objek_pajak,
                    'alamat_objek_pajak' => $pdl->alamat_objek_pajak,
                    'tanggal_ketetapan' => tgl_full($pdl->tanggal_ketetapan, 2),
                    'masa_awal' => tgl_full($pdl->masa_awal, 2),
                    'masa_akhir' => tgl_full($pdl->masa_akhir, 2),
                    'tanggal_jatuh_tempo' => tgl_full($pdl->tanggal_jatuh_tempo, 2),
                    'sumber_data' => $pdl->sumber_data,
                    'tanggal_update' => tgl_full($pdl->tanggal_update, 2),
                    'kecamatan' => $pdl->kecamatan,
                    'kelurahan' => $pdl->kelurahan,
                ];
                $cek_data = DB::table('data.detail_tunggakan')->where('tahun', $pdl->tahun)->where('bulan', $pdl->bulan)->where('nop', $pdl->nop)->where('npwpd', $pdl->npwpd)->where('nama_rekening', $pdl->nama_rekening)->where('kode_rekening', $pdl->kode_rekening)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.detail_tunggakan')->where('tahun', $pdl->tahun)->where('bulan', $pdl->bulan)->where('nop', $pdl->nop)->where('npwpd', $pdl->npwpd)->where('nama_rekening', $pdl->nama_rekening)->where('kode_rekening', $pdl->kode_rekening)->delete();
                }
                DB::table('data.detail_tunggakan')->insert($data_pdl);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'DETAIL_TUNGGAKAN')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_detail_ketetapan(Request $request)
    {
        try {
            $query = " select abc.id as nop, abc.npwpd as npwpd, abc.tahun, abc.bulan, t2.rekeningnm as nama_rekening, abc.rek as kode_rekening, abc.nomor_ketetapan, abc.nominal_ketetapan,
            abc.nama_wp, abc.alamat_wp, abc.nama_op, abc.alamat_op, abc.tanggal_ketetapan, abc.masa_awal, abc.masa_akhir, abc.tanggal_jatuh_tempo, 'PDL' as sumber_data, now() as tanggal_update
            from 
            (select op.id, wp.npwpd, ab.tahun, ab.bulan, substring(t.rekeningkd,1,6) as rek, ab.pajak_terhutang as nominal_ketetapan,
            wp.customernm as nama_wp , wp.alamat || ' ' || lrhwp.kelurahannm || ' ' || cmtwp.kecamatannm || ' ' || wp.kabupaten as alamat_wp, 
            op.opnm as nama_op, op.opalamat || ' ' || lrhop.kelurahannm || ' ' || cmtop.kecamatannm as alamat_op,
            ab.sptno as nomor_ketetapan, ab.tanggal_ketetapan, ab.masa_awal, ab.masa_akhir, ab.tanggal_jatuh_tempo
            from 
            (select extract (year from create_date) as tahun, extract (month from create_date) as bulan, rekening_id, pajak_terhutang, sptno,
            create_date as tanggal_ketetapan, masadari as masa_awal, masasd as masa_akhir, jatuhtempotgl as tanggal_jatuh_tempo, customer_id, customer_usaha_id
            from pad_spt ) ab 
            left join pad_customer wp on ab.customer_id=wp.id 
            left join pad_customer_usaha op on ab.customer_usaha_id=op.id 
            left join tblrekening t on ab.rekening_id=t.id 
            left join tblkecamatan cmtop on op.kecamatan_id = cmtop.id 
            left join tblkelurahan lrhop on op.kelurahan_id =lrhop.id and op.kecamatan_id =lrhop.kecamatan_id 
            left join tblkecamatan cmtwp on wp.kecamatan_id = cmtwp.id 
            left join tblkelurahan lrhwp on wp.kelurahan_id =lrhwp.id and wp.kecamatan_id =lrhwp.kecamatan_id ) abc
            left join tblrekening t2 on abc.rek=t2.rekeningkd 
            where t2.issummary =1
            ";

            $pdl = DB::connection("pgsql_pdl")->select($query);
            // dd($pdl);
            foreach ($pdl as $key => $pdl) {
                $data_pdl = [
                    'tahun' => $pdl->tahun,
                    'bulan' => $pdl->bulan,
                    'nop' => $pdl->nop,
                    'npwpd' => $pdl->npwpd,
                    'nama_rekening' => $pdl->nama_rekening,
                    'kode_rekening' => $pdl->kode_rekening,
                    'nomor_ketetapan' => $pdl->nomor_ketetapan,
                    'nominal_ketetapan' => $pdl->nominal_ketetapan,
                    'nama_subjek_pajak' => $pdl->nama_wp,
                    'alamat_subjek_pajak' => $pdl->alamat_wp,
                    'nama_objek_pajak' => $pdl->nama_op,
                    'alamat_objek_pajak' => $pdl->alamat_op,
                    'tanggal_ketetapan' => tgl_full($pdl->tanggal_ketetapan, 2),
                    'masa_awal' => tgl_full($pdl->masa_awal, 2),
                    'masa_akhir' => tgl_full($pdl->masa_akhir, 2),
                    'tanggal_jatuh_tempo' => tgl_full($pdl->tanggal_jatuh_tempo, 2),
                    'sumber_data' => $pdl->sumber_data,
                    'tanggal_update' => tgl_full($pdl->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.detail_ketetapan')->where('tahun', $pdl->tahun)->where('bulan', $pdl->bulan)->where('nop', $pdl->nop)->where('npwpd', $pdl->npwpd)->where('nama_rekening', $pdl->nama_rekening)->where('kode_rekening', $pdl->kode_rekening)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.detail_ketetapan')->where('tahun', $pdl->tahun)->where('bulan', $pdl->bulan)->where('nop', $pdl->nop)->where('npwpd', $pdl->npwpd)->where('nama_rekening', $pdl->nama_rekening)->where('kode_rekening', $pdl->kode_rekening)->delete();
                }
                DB::table('data.detail_ketetapan')->insert($data_pdl);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'DETAIL_KETETAPAN')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_rekap_ketetapan_perolehan_bphtb(Request $request)
    {
        // dd('a');
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        try {
            // $query = " select abc.tahun, abc.bulan, abc.nama_rekening, abc.kode_rekening, bc.nama as jenis_perolehan, 
            // abc.ketetapan, abc.jumlah_transaksi, abc.sumber_data, abc.tanggal_update
            // from 
            // (select extract (year from tgl_transaksi) as tahun, extract(month from tgl_transaksi) as bulan, 'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)' as nama_rekening, '410116' as kode_rekening, 
            // perolehan_id, sum(bphtb_harus_dibayarkan) as ketetapan, count(*) as jumlah_transaksi, 'BPHTB' as sumber_data, now() as tanggal_update 
            // from bphtb_sspd
            // where extract (year from tgl_transaksi)=extract(year from now()) and extract (month from tgl_transaksi)=extract(month from now())
            // group by extract (year from tgl_transaksi), extract(month from tgl_transaksi), perolehan_id) abc
            // left join bphtb_perolehan bc on abc.perolehan_id=bc.id 
            // order by abc.tahun desc, abc.bulan desc
            // ";
            $query = "SELECT
            EXTRACT(YEAR FROM \"TANGGALBAYAR\") AS tahun,
            EXTRACT(MONTH FROM \"TANGGALBAYAR\") AS bulan,
            'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)' AS nama_rekening,
            '' AS kode_rekening,
            \"JENISPEROLEHAN\" AS jenis_perolehan,
            SUM(\"JUMLAHHARUSDIBAYAR\") AS nominal_ketetapan,
            COUNT(\"id\") AS jumlah_transaksi,
            'BPHTB' AS sumber_data,
            NOW() AS tanggal_update
          FROM tb_sspd_bphtb
          WHERE
            \"DELETED_AT\" IS NULL AND \"JUMLAHHARUSDIBAYAR\" > 0
            AND EXTRACT(YEAR FROM \"TANGGALBAYAR\") = $tahun
            AND EXTRACT(MONTH FROM \"TANGGALBAYAR\") = $bulan
          GROUP BY
            EXTRACT(YEAR FROM \"TANGGALBAYAR\"),
            EXTRACT(MONTH FROM \"TANGGALBAYAR\"),
            \"JENISPEROLEHAN\"";

            $bphtb = DB::connection("pgsql_bphtb")->select($query);
            foreach ($bphtb as $key => $bphtb) {
                $data_bphtb = [
                    'nama_rekening' => $bphtb->nama_rekening,
                    'kode_rekening' => $bphtb->kode_rekening,
                    'tahun' => $bphtb->tahun,
                    'bulan' => $bphtb->bulan,
                    'jenis_perolehan' => $bphtb->jenis_perolehan,
                    'nominal' => $bphtb->nominal_ketetapan,
                    'jumlah_transaksi' => $bphtb->jumlah_transaksi,
                    'sumber_data' => $bphtb->sumber_data,
                    'tanggal_update' => tgl_full($bphtb->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.rekap_ketetapan_perolehan_bpthb')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('jenis_perolehan', $bphtb->jenis_perolehan)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.rekap_ketetapan_perolehan_bpthb')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('jenis_perolehan', $bphtb->jenis_perolehan)->delete();
                }
                DB::table('data.rekap_ketetapan_perolehan_bpthb')->insert($data_bphtb);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'REKAP_KETETAPAN_PEROLEHAN_BPHTB')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_rekap_ketetapan_peruntukan_bphtb(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        try {
            $query = " select extract (year from tgl_transaksi) as tahun, extract(month from tgl_transaksi) as bulan, 'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)' as nama_rekening, '410116' as kode_rekening, 
            peruntukan, sum(bphtb_harus_dibayarkan) as nominal_ketetapan, count(*) as jumlah_transaksi, 'BPHTB' as sumber_data, now() as tanggal_update 
            from bphtb_sspd
            where extract (year from tgl_transaksi)=$tahun and extract (month from tgl_transaksi)=$bulan
            group by extract (year from tgl_transaksi), extract(month from tgl_transaksi), peruntukan
            order by extract (year from tgl_transaksi) desc, extract(month from tgl_transaksi) desc
            ";

            $bphtb = DB::connection("pgsql_bphtb")->select($query);
            // dd($bphtb);
            foreach ($bphtb as $key => $bphtb) {
                if (is_null($bphtb->peruntukan)) {
                    $peruntukan = '-';
                } else {
                    $peruntukan = $bphtb->peruntukan;
                }

                $data_bphtb = [
                    'nama_rekening' => $bphtb->nama_rekening,
                    'kode_rekening' => $bphtb->kode_rekening,
                    'tahun' => $bphtb->tahun,
                    'bulan' => $bphtb->bulan,
                    'peruntukan' => $peruntukan,
                    'nominal_ketetapan' => $bphtb->nominal_ketetapan,
                    'jumlah_transaksi' => $bphtb->jumlah_transaksi,
                    'sumber_data' => $bphtb->sumber_data,
                    'tanggal_update' => tgl_full($bphtb->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.rekap_ketetapan_peruntukan_bphtb')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('peruntukan', $peruntukan)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.rekap_ketetapan_peruntukan_bphtb')->where('tahun', $bphtb->tahun)->where('bulan', $bphtb->bulan)->where('peruntukan', $peruntukan)->delete();
                }
                DB::table('data.rekap_ketetapan_peruntukan_bphtb')->insert($data_bphtb);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'REKAP_KETETAPAN_PERUNTUKAN_BPHTB')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_kepatuhan_objek(Request $request)
    {
        $tahun = $request->tahun;
        try {
            $query = " 
            select * from (
                SELECT
                        x.THN_PAJAK_SPPT,
                        x.kd_kecamatan,
                        x.kd_kelurahan,
                        NM_KECAMATAN AS kecamatan,
                        NM_KELURAHAN AS kelurahan,
                        x.nop_baku,
                        coalesce( y.nop_tepat_waktu, 0 ) nop_tepat_waktu,
                        ROUND( ( coalesce( y.nop_tepat_waktu, 0 ) / x.nop_baku ) * 100, 2 ) AS persen 
                FROM
                        ( SELECT kd_kecamatan, kd_kelurahan, THN_PAJAK_SPPT, count( * ) AS NOP_BAKU FROM SPPT GROUP BY kd_kecamatan, kd_kelurahan, THN_PAJAK_SPPT ) x
                        LEFT JOIN (
                        SELECT
                                THN_PAJAK_SPPT,
                                kd_kecamatan,
                                kd_kelurahan,
                                sum( CASE WHEN to_char( TGL_PEMBAYARAN_SPPT, 'yyyy' ) = THN_PAJAK_SPPT AND extract( month FROM TGL_PEMBAYARAN_SPPT ) <= 10 THEN 1 ELSE 0 END ) AS NOP_TEPAT_WAKTU 
                        FROM
                                pembayaran_sppt 
                        GROUP BY
                                THN_PAJAK_SPPT,
                                kd_kecamatan,
                                kd_kelurahan 
                        ) y ON x.THN_PAJAK_SPPT = y.THN_PAJAK_SPPT 
                        AND x.kd_kecamatan = y.kd_kecamatan 
                        AND x.kd_kelurahan = y.kd_kelurahan
                        LEFT JOIN REF_KECAMATAN kec ON x.KD_KECAMATAN = kec.KD_KECAMATAN
                        LEFT JOIN REF_KELURAHAN kel ON x.KD_KECAMATAN = kel.KD_KECAMATAN 
                        AND x.KD_KELURAHAN = kel.KD_KELURAHAN 
                ORDER BY
                        x.THN_PAJAK_SPPT DESC,
                        x.kd_kecamatan,
                        x.kd_kelurahan
                ) x where thn_pajak_sppt = $tahun
            ";

            $data = DB::connection("oracle")->select($query);
            // dd($data);
            foreach ($data as $key => $data) {
                $data_data = [
                    'tahun' => $data->thn_pajak_sppt,
                    'nop_baku' => $data->nop_baku,
                    'nop_bayar' => $data->nop_tepat_waktu,
                    'persen' => $data->persen
                ];
                $cek_data = DB::table('data.kepatuhan_objek')->where('tahun', $data->thn_pajak_sppt)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.kepatuhan_objek')->where('tahun', $data->thn_pajak_sppt)->delete();
                }
                DB::table('data.kepatuhan_objek')->insert($data_data);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'KEPATUHAN_OBJEK')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status' => '0'
            ]);
        }
    }

    public function getdata_pelaporan(Request $request)
    {
        // // dd($request->all());
        // $tahun = $request->tahun;
        // $bulan = $request->bulan;
        // // dd($tahun,$bulan);
        try {
            $query = "select tahun, bulan, nama_pajak nama_rekening, kode_rekening, sudah_bayar lapor,sudah_lapor_blm_lunas belum_bayar,
            jmlh_aktif-sudah_bayar-sudah_lapor_blm_lunas as belum_lapor,
            'SIMPADAMA' AS sumber_data, now() as tanggal_update
            from (
            select
            extract(year from x.tgl1) as tahun,
            extract(month from x.tgl1) as bulan,
            nama_pajak,
            kode_rekening,
            (select count(*) as jmlh_aktif
            from data.tb_op ab
            where ab.tanggal_daftar::date < x.tgl2 and 
            ab.jenis_objek::int=z.kode_akun_pajak::int and
            (ab.tgl_tutup is null or ab.tgl_tutup::date >= x.tgl2) and
            (ab.deleted_at is null or ab.deleted_at::date >= x.tgl2)
            ),
            (
            select count(*) sudah_bayar from (
            select nop,
            sum(case when pen.ntpp is not null then 0 else 1 end) as kondisi_bayar
            from data.tb_penerimaan pen
            where z.kode_akun_pajak = pen.kode_akun_pajak and
            pen.masa_pajak_tahun::int = extract(year from x.tgl1) and
            pen.masa_pajak_bulan::int = extract(month from x.tgl1) and
            (pen.deleted_at is null or pen.deleted_at::date >= x.tgl2)
            group by nop
            ) sbyr where kondisi_bayar=0
            ),
            (
            select count(*) sudah_lapor_blm_lunas from (
            select nop,
            sum(case when pen.ntpp is not null then 0 else 1 end) as kondisi_bayar
            from data.tb_penerimaan pen
            where z.kode_akun_pajak = pen.kode_akun_pajak and
            pen.masa_pajak_tahun::int = extract(year from x.tgl1) and
            pen.masa_pajak_bulan::int = extract(month from x.tgl1) and
            (pen.deleted_at is null or pen.deleted_at::date >= x.tgl2)
            group by nop
            ) sbyr where kondisi_bayar>0
            )
            from 
            (
            SELECT
            ab as tgl1, ab + interval '1 month' as tgl2
            FROM (
            SELECT
            *
            FROM
            generate_series (
            --filter jumlah tahun kebelakang yang diambil
            to_date(concat(extract(year from now())-1,'0101'),'yyyymmdd'),
            now(),
            '1 month'
            ) AS ab
            ) as aa
            ) x
            left join
            ( select distinct a.kode_akun_pajak, b.nama_pajak, b.kode_rekening 
            from data.tb_penerimaan a
            left join master.tb_jenis_pajak b
            on a.kode_akun_pajak::int = b.id
            ) z
            on 1=1
            where x.tgl1 >= '2023-8-1'
            ) xz
            order by nama_pajak, tahun desc, bulan desc";


            // dd($query);
            $bphtb = DB::connection("pgsql_pdl")->select($query);
            foreach ($bphtb as $key => $result) {
                $data_bphtb = [
                    'tahun' => $result->tahun,
                    'bulan' => $result->bulan,
                    'kode_rekening' => $result->kode_rekening,
                    'nama_rekening' => $result->nama_rekening,
                    'lapor' => $result->lapor,
                    'belum_bayar' => $result->belum_bayar,
                    'belum_lapor' => $result->belum_lapor,
                    'sumber_data' => $result->sumber_data,
                    'tanggal_update' => tgl_full($result->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.pelaporan')->where('tahun', $result->tahun)->where('bulan', $result->bulan)->where('nama_rekening', $result->nama_rekening)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.pelaporan')->where('tahun', $result->tahun)->where('bulan', $result->bulan)->where('nama_rekening', $result->nama_rekening)->delete();
                }
                DB::table('data.pelaporan')->insert($data_bphtb);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'PELAPORAN')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '0'
            ]);
        }
    }
    public function getdata_detail_objek_pajak(Request $request)
    {
        // // dd($request->all());
        // $tahun = $request->tahun;
        // $bulan = $request->bulan;
        // // dd($tahun,$bulan);
        try {
            $query = "select
            nop, wp.npwp npwpd, nama_pajak nama_rekening, a.kode_rekening, wp.nama nama_subjek_pajak, wp.jalan alamat_subjek_pajak,
            a.nama nama_objek_pajak, a.jalan alamat_objek_pajak, tanggal_pendaftaran as tanggal_daftar, tgl_tutup as tanggal_tutup, wp.no_hp as telp_subjek_pajak, 
            'SIMPADAMA' AS sumber_data, now() as tanggal_update
             from data.tb_wp wp left join data.tb_op a
            on wp.npwp=a.npwp
            left join master.tb_jenis_pajak b
            on a.jenis_objek = b.id
            where
            ( a.tgl_tutup is not null or a.deleted_at is not null )
            and a.jenis_objek=2";

            $objek_results = DB::connection("pgsql_pdl")->select($query);
            // dd($objek_results);
            foreach ($objek_results as $key => $result) {
                $data_objek = [
                    'nop' => $result->nop,
                    'npwpd' => $result->npwpd,
                    'nama_rekening' => $result->nama_rekening,
                    'kode_rekening' => $result->kode_rekening,
                    'nama_subjek_pajak' => $result->nama_subjek_pajak,
                    'alamat_subjek_pajak' => $result->alamat_subjek_pajak,
                    'nama_objek_pajak' => $result->nama_objek_pajak,
                    'alamat_objek_pajak' => $result->alamat_objek_pajak,
                    'tanggal_daftar' => $result->tanggal_daftar,
                    'tanggal_tutup' => $result->tanggal_tutup,
                    'telp_subjek_pajak' => $result->telp_subjek_pajak,
                    'sumber_data' => $result->sumber_data,
                    'tanggal_update' => tgl_full($result->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.detail_objek_pajak')->where('nop', $result->nop)->where('npwpd', $result->npwpd)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.detail_objek_pajak')->where('nop', $result->nop)->where('npwpd', $result->npwpd)->delete();
                }
                DB::table('data.detail_objek_pajak')->insert($data_objek);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'DETAIL_OBJEK_PAJAK')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status' => '0' . $th
            ]);
        }
    }

    public function getdata_rekap_ketetapan_bphtb_nihil_bayar(Request $request)
    {
        // // dd($request->all());
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        // // dd($tahun,$bulan);
        try {
            $query = "SELECT
            EXTRACT(YEAR FROM \"TANGGALBAYAR\") AS tahun,
            EXTRACT(MONTH FROM \"TANGGALBAYAR\") AS bulan,
            SUM(\"JUMLAHHARUSDIBAYAR\") AS nominal_berbayar,
            SUM(CASE WHEN \"JUMLAHHARUSDIBAYAR\" > 0 THEN 1 ELSE 0 END) AS jumlah_transaksi,
            SUM(CASE WHEN \"JUMLAHHARUSDIBAYAR\" > 0 AND \"TANGGALBAYAR\" IS NOT NULL THEN 1 ELSE 0 END) AS sudah_bayar,
            SUM(CASE WHEN \"JUMLAHHARUSDIBAYAR\" > 0 AND \"TANGGALBAYAR\" IS NULL THEN 1 ELSE 0 END) AS belum_bayar,
            SUM(CASE WHEN \"JUMLAHHARUSDIBAYAR\" = 0 THEN 1 ELSE 0 END) AS jumlah_transaksi_nihil,
            'BPHTB' AS sumber_data,
            NOW() AS tanggal_update
          FROM tb_sspd_bphtb
          WHERE
            \"DELETED_AT\" IS NULL
            AND EXTRACT(YEAR FROM \"TANGGALBAYAR\") = $tahun
            AND EXTRACT(MONTH FROM \"TANGGALBAYAR\") = $bulan
          GROUP BY
            EXTRACT(YEAR FROM \"TANGGALBAYAR\"),
            EXTRACT(MONTH FROM \"TANGGALBAYAR\")";
            $objek_results = DB::connection("pgsql_bphtb")->select($query);
            // dd($objek_results);
            foreach ($objek_results as $key => $result) {
                $data_objek = [
                    'tahun' => $result->tahun,
                    'bulan' => $result->bulan,
                    'nominal_berbayar' => $result->nominal_berbayar,
                    'jumlah_transaksi' => $result->jumlah_transaksi,
                    'sudah_bayar' => $result->sudah_bayar,
                    'belum_bayar' => $result->belum_bayar,
                    'jumlah_transaksi_nihil' => $result->jumlah_transaksi_nihil,
                    'sumber_data' => $result->sumber_data,
                    'tanggal_update' => tgl_full($result->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.rekap_ketetapan_bphtb_nihil_bayar')->where('tahun', $result->tahun)->where('bulan', $result->bulan)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.rekap_ketetapan_bphtb_nihil_bayar')->where('tahun', $result->tahun)->where('bulan', $result->bulan)->delete();
                }
                DB::table('data.rekap_ketetapan_bphtb_nihil_bayar')->insert($data_objek);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'REKAP_KETETAPAN_BPHTB_NIHIL_BAYAR')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status' => '0'
            ]);
        }
    }
    public function getdata_pelaporan_ppat_bphtb(Request $request)
    {
        // // dd($request->all());
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        // // dd($tahun,$bulan);
        try {
            $query = "SELECT
            EXTRACT(YEAR FROM \"TANGGALBAYAR\") AS tahun,
            EXTRACT(MONTH FROM \"TANGGALBAYAR\") AS bulan,
            \"NAMAPPAT\" AS nama_ppat,
            COUNT(\"id\") AS jumlah_laporan,
            'BPHTB' AS sumber_data,
            NOW() AS tanggal_update
          FROM tb_sspd_bphtb
          WHERE
            \"DELETED_AT\" IS NULL
            AND EXTRACT(YEAR FROM \"TANGGALBAYAR\") = $tahun
            AND EXTRACT(MONTH FROM \"TANGGALBAYAR\") = $bulan
          GROUP BY
            EXTRACT(YEAR FROM \"TANGGALBAYAR\"),
            EXTRACT(MONTH FROM \"TANGGALBAYAR\"),
            \"NAMAPPAT\"";

            $objek_results = DB::connection("pgsql_bphtb")->select($query);
            // dd($objek_results);
            foreach ($objek_results as $key => $result) {
                $data_objek = [
                    'tahun' => $result->tahun,
                    'bulan' => $result->bulan,
                    'nama_ppat' => $result->nama_ppat,
                    'jumlah_laporan' => $result->jumlah_laporan,
                    'sumber_data' => $result->sumber_data,
                    'tanggal_update' => tgl_full($result->tanggal_update, 2),
                ];
                $cek_data = DB::table('data.pelaporan_ppat_bphtb')->where('tahun', $result->tahun)->where('bulan', $result->bulan)->where('nama_ppat', $result->nama_ppat)->count();
                // dd($cek_data);
                if ($cek_data > 0) {
                    DB::table('data.pelaporan_ppat_bphtb')->where('tahun', $result->tahun)->where('bulan', $result->bulan)->where('nama_ppat', $result->nama_ppat)->delete();
                }
                DB::table('data.pelaporan_ppat_bphtb')->insert($data_objek);

                $now = date("Y-m-d H:i:s");
                $arrData = ['updated_at' => $now];
                DB::table("data.daftar_data")->where('table', 'PELAPORAN_PPAT_BPHTB')->update($arrData);
            }

            return response()->json([
                'status' => '1'
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status' => '0'
            ]);
        }
    }
}
