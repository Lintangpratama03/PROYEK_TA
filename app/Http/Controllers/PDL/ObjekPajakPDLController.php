<?php

namespace App\Http\Controllers\PDL;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use DateTime;

class ObjekPajakPDLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $akses = get_url_akses();
        if ($akses) {
            return redirect()->route("pad.index");
        } else {
            return view("admin.pdl.op");
        }
    }
    public function get_total_op()
    {
        $tahun = date('y');
        $bulan = date('m');
        $query = "SELECT 
                    CASE 
                        WHEN usaha_id = 1 THEN 'Pajak Hotel'
                        WHEN usaha_id = 2 THEN 'Pajak Restoran'
                        WHEN usaha_id = 3 THEN 'Pajak Hiburan'
                        WHEN usaha_id = 4 THEN 'Pajak Reklame'
                        WHEN usaha_id = 5 THEN 'Pajak Parkir'
                        WHEN usaha_id = 6 THEN 'Pajak Air Tanah'
                        WHEN usaha_id = 7 THEN 'Pajak Penerangan Jalan'
                        WHEN usaha_id = 9 THEN 'Pajak Mineral Bukan Logam dan Batuan'
                        WHEN usaha_id = 10 THEN 'Pajak Sarang Burung Walet'
                        WHEN usaha_id = 11 THEN 'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)'
                        WHEN usaha_id = 12 THEN 'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)'
                        WHEN usaha_id = 13 THEN 'DENDA'
                        ELSE '?' 
                    END AS pajaknm,
                    COUNT(*) AS jumlah
                FROM pad_customer_usaha
                WHERE customer_status_id != 2 AND usaha_id NOT IN (13,12,11)
                GROUP BY pajaknm";
        $data = DB::connection("pgsql_pdl")->select($query);
        $data2 = DB::table('data.v_nm_rekening_new')->get();
        $nama_lama = [];
        foreach ($data2 as $item) {
            $nama_lama[$item->nama_lama] = $item->nama_rekening;
        }

        $nama_baru = [];
        foreach ($data as $item) {
            if (isset($nama_lama[$item->pajaknm])) {
                $nama_rekening_baru = $nama_lama[$item->pajaknm];
                $nama_baru[$nama_rekening_baru] = $item->jumlah;
            }
        }

        return response()->json([
            "success" => $nama_baru
        ]);
    }



    public function pelaporan_pdl(Request $request)
    {

        $data['sudahbayar'] = $this->get_pelaporan_pdl('2023')['sudahbayar'];
        $data['sudahlapor'] = $this->get_pelaporan_pdl('2023')['sudahlapor'];
        $data['belumlapor'] = $this->get_pelaporan_pdl('2023')['belumlapor'];

        // dd($data);
        return $data;
    }

    public function get_pelaporan_pdl($thn)
    {
        $kode_rekening = "Pajak Hotel";
        $query = DB::table("data.pelaporan")->where('nama_rekening', $kode_rekening)->where('tahun', $thn)->orderBy('bulan', 'ASC')->get();
        // dd($query);
        foreach ($query as $key => $value) {
            $data['sudahbayar'][] = $value->lapor;
            $data['sudahlapor'][] = $value->belum_bayar;
            $data['belumlapor'][] = $value->belum_lapor;
        }

        return $data;
    }

    function datatable_kontribusi_op(Request $request)
    {
        // dd($request);
        // dd($request->jenis_pajak);
        $thn = $request->tahun;
        $kode = $request->jenis_pajak;
        // $kodeRekening = str_replace(".", "", $kode);
        // dd($kodeRekening);
        $rawQuery = "
        select * from (
            select 
            a.masa_pajak_tahun, 
            c.nama_pajak,
            a.nama_wp,
            b.nama as nama_op,
            sum(a.jumlah_pembayaran) as nominal
            from data.tb_penerimaan a
            left join data.tb_op b
            on a.nop=b.nop
            left join master.tb_jenis_pajak c
            on a.kode_akun_pajak::int = c.id
            where 
            a.deleted_at is null and
            a.ntpp is not null and
            a.masa_pajak_tahun = '$thn'
            and a.kode_akun_pajak= '$kode'
            group by
            a.masa_pajak_tahun, 
            c.nama_pajak,
            a.nama_wp,
            b.nama
            ) x
            order by nominal desc
            limit 50
        ";
        //dd($rawQuery);
        // $d_data = DB::table("data.kontribusi_op_pdl")->where('tahun',$thn)->where('kode_rekening',$kode)->orderBy('kontribusi','DESC')->limit('5')->get();
        $d_data = db::connection("pgsql_pdl")->select($rawQuery);
        // dd($d_data);
        $arr = array();
        // if($d_data->count() > 0){
        foreach ($d_data as $key => $d) {
            # code...
            $arr[] =
                // array(
                //     "tahun"=>$d->tahun,
                //     "nama_rekening"=>$d->nama_rekening,
                //     "nama_objek_pajak"=>$d->nama_objek_pajak,
                //     "nama_subjek_pajak"=>$d->nama_subjek_pajak,
                //     "kontribusi"=> rupiahFormat($d->kontribusi)
                // );
                array(
                    "tahun" => $d->masa_pajak_tahun,
                    "nama_rekening" => $d->nama_pajak,
                    "nama_objek_pajak" => $d->nama_op,
                    "nama_subjek_pajak" => $d->nama_wp,
                    "kontribusi" => rupiahFormat($d->nominal)
                );
        }

        // }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    function datatable_op_aktif_tutup(Request $request)
    {

        // dd($request->jenis_pajak);
        $jenis_pajak = $request->jenis_pajak;
        // if (is_null($jenis_pajak)) {
        //     $jenis_pajak = '4.1.01.06.';
        // }

        $qr_id = 0;
        // $qr = DB::table("data.master_rekening")
        //     ->where("tahun", 2023)
        //     ->where("level", 4)
        //     ->where('kode_rekening', 'like', '4.1.01' . '%%')
        //     ->where('kode_rekening', $jenis_pajak)
        //     ->where("is_aktif", 1)
        //     ->first();

        // if (!is_null($qr)) {
        //     $qr_id = $qr->id;
        // }

        // dd($qr_id);


        // dd($qr->id);


        // $jns_pajak = $qr ? strval($qr->id) : '';

        // dd($jns_pajak);

        // $rawQuery = "
        // SELECT
        //     a.id as nop,
        //     b.npwpd,
        //     case usaha_id
        //                     when 1 then 'Pajak Hotel'
        //                     when 2 then 'Pajak Restoran'
        //                     when 3 then 'Pajak Hiburan'
        //                     when 4 then 'Pajak Reklame'
        //                     when 5 then 'Pajak Parkir'
        //                     when 6 then 'Pajak Air Tanah'
        //                     when 7 then 'Pajak Penerangan Jalan'
        //                     when 9 then 'Pajak Mineral Bukan Logam dan Batuan'
        //                     when 10 then 'Pajak Sarang Burung Walet'
        //                     when 11 then 'Pajak Bumi dan Bangunan Perdesaan dan Perkotaan (PBBP2)'
        //                     when 12 then 'Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB)'
        //                     when 13 then 'DENDA'
        //                     else '?' end as nama_rekening,
        //     '' as kode_rekening,
        //     b.customernm as nama_subjek_pajak,
        //     b.alamat as alamat_subjek_pajak,
        //     a.opnm as nama_objek_pajak,
        //     a.opalamat as alamat_objek_pajak,
        //     a.reg_date as tgl_daftar,
        //     a.tgl_tutup,
        //     b.telphone,
        //     '' as namacp,
        //     '' as telpcp,
        //     'PDL' as sumber_data
        //     FROM pad_customer_usaha a
        //     left join pad_customer b
        //     on a.customer_id = b.id
        //     where usaha_id = $jenis_pajak";
        $rawQuery = "
            select 
            a.nop,
            a.npwp,
            b.nama_pajak,
            a.nama nama_op,
            a.jalan alamat_op,
            a.tanggal_daftar,
            a.tgl_tutup,
            a.deleted_at tgl_delete
            from data.tb_op a
            left join master.tb_jenis_pajak b
            on a.jenis_objek = b.id
            left join tb_wp c
            on a.npwp=c.npwp
            where a.jenis_objek=$jenis_pajak
            order by npwp, nop";

        $d_data = DB::connection("pgsql_pdl")->select($rawQuery);
        // dd($d_data);
        $arr = array();
        // if($d_data->count() > 0){
        foreach ($d_data as $key => $d) {
            // if (is_null($d->tanggal_tutup)) {
            // if (is_null($d->tgl_tutup)) {
            //     $status = 'Aktif';
            // }else{
            //     $status = 'Tutup';
            // }
            $arr[] =
                array(
                    "nama_rekening" => $d->nama_pajak,
                    "nama_objek_pajak" => $d->nama_op,
                    "alamat_objek_pajak" => $d->alamat_op,
                    "nop" => $d->nop,
                    "npwpd" => $d->npwp,
                );
        }

        // }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function penerimaan_harian()
    {
        $rekening = 'Pajak Hotel';
        $query = DB::table("data.penerimaan_harian")->where('nama_rekening', $rekening)->orderBy('tanggal', 'ASC')->get();
        // dd($query);
        foreach ($query as $key => $value) {
            $data['tanggal'][] = $value->tanggal;
            $data['penerimaan'][] = $value->penerimaan;
        }
        return $data;
    }

    public function search()
    {
        return view("admin.pdl.search");
    }

    function query_search(Request $request)
    {
        // dd($request);
        $kategori = $request->kategori;
        $search = $request->search;
        $arr = array();

        $query = "  SELECT wp.npwpd, wp.customernm as nama_wp, wp.alamat as alamat_wp,kec.kecamatannm as kecamatan_wp, kel.kelurahannm as kelurahan_wp, 
       op.id as nop, op.opnm as nama_op , op.opalamat as alamat_op, kecop.kecamatannm as kecamatan_op, kelop.kelurahannm as kelurahan_op,
       pjk.pajaknm as jenis_pajak, op.* 
       FROM pad_customer_usaha op
       JOIN pad_customer wp ON wp.id = op.customer_id
       JOIN tblkecamatan kec ON kec.id = wp.kecamatan_id
       JOIN tblkelurahan kel ON kel.id = wp.kelurahan_id
       JOIN tblkecamatan kecop ON kecop.id = op.kecamatan_id
       JOIN tblkelurahan kelop ON kelop.id = op.kelurahan_id
       JOIN pad_pajak pjk ON pjk.id = op.def_pajak_id
       WHERE op.id=" . $search;

        $d_data = DB::connection("pgsql_pdl")->select($query);
        // dd($d_data);
        if (count($d_data) > 0) {
            foreach ($d_data as $key => $d) {
                # code...
                $arr['profil'] =
                    array(
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_wp" => $d->nama_wp,
                        "alamat_wp" => $d->alamat_wp,
                        "kecamatan_wp" => $d->kecamatan_wp,
                        "kelurahan_wp" => $d->kelurahan_wp,
                        "nama_op" => $d->nama_op,
                        "alamat_op" => $d->alamat_op,
                        "kecamatan_op" => $d->kecamatan_op,
                        "kelurahan_op" => $d->kelurahan_op,
                        "jenis_pajak" => $d->jenis_pajak
                    );
            }
        }

        $query_history = " SELECT * FROM pad_spt
        WHERE customer_usaha_id = " . $search  . "ORDER BY masadari DESC";

        $d_data_history = DB::connection("pgsql_pdl")->select($query_history);
        // dd($d_data_history);
        if (count($d_data_history) > 0) {
            foreach ($d_data_history as $key => $d) {
                if ($d->status_pembayaran == 1) {
                    $status_bayar = "LUNAS";
                } else {
                    $status_bayar = "BELUM BAYAR";
                }
                $arr['history'][] =
                    array(
                        "tahun" => $d->tahun,
                        "masa_pajak" => tgl_full($d->masadari, 0) . " sampai " . tgl_full($d->masasd, 0),
                        "pajak_terhutang" => number_format($d->pajak_terhutang),
                        "status_pembayaran" => $status_bayar,
                    );
            }
        }

        return response()->json($arr);
    }

    public function get_chart_op(Request $request)
    {
        // dd('get route');
        if (is_null($request->tahun)) {
            $now = new DateTime();
            $year = $now->format("Y");
            $tahun = $year;
        } else {
            $tahun = $request->tahun;
        }
        // dd($tahun);
        if (!is_null($tahun)) {
            $daftar = DB::table('data.detail_objek_pajak')
                ->where(DB::raw('EXTRACT(YEAR FROM tanggal_daftar)'), $tahun)
                ->groupBy(DB::raw('EXTRACT(MONTH FROM tanggal_daftar)'))
                ->selectRaw('count(*) as pendaftaran, EXTRACT(MONTH FROM tanggal_daftar) as bulan')->get();


            $tutup = DB::table('data.detail_objek_pajak')
                ->where(DB::raw('EXTRACT(YEAR FROM tanggal_tutup)'), $tahun)
                ->whereNotNull('tanggal_tutup')
                ->groupBy(DB::raw('EXTRACT(MONTH FROM tanggal_tutup)'))
                ->selectRaw('count(*) as penutupan, EXTRACT(MONTH FROM tanggal_tutup) as bulan')->get();

            $bulanArray = array();
            if ($daftar->count() > 0) {
                foreach ($daftar as $key => $value) {
                    $arr = $value->pendaftaran;
                    $bulan = $value->bulan;
                    if (!isset($data['pendaftaran'])) {
                        $data['pendaftaran'] = array();
                    }
                    if (!in_array($bulan, $bulanArray)) {
                        $bulanArray[] = $bulan;
                    }
                    array_push($data['pendaftaran'], $arr);
                }
            }
            if ($tutup->count() > 0) {
                foreach ($tutup as $key => $value) {
                    $bulan = $value->bulan;
                    $arr = $value->penutupan;
                    if (!isset($data['penutupan'])) {
                        $data['penutupan'] = array();
                    }
                    if (!in_array($bulan, $bulanArray)) {
                        $bulanArray[] = $bulan;
                    }
                    array_push($data['penutupan'], $arr);
                }
            }

            $bulanText = array();
            foreach ($bulanArray as $key => $value) {
                array_push($bulanText, getMonth($value));
            }
            $data['bulan'] = $bulanText;
        } else {
            $data['pendaftaran'] = [];
            $data['penutupan'] = [];
            $data['bulan'] = [];
        }
        return $data;
    }

    public function detail_daftar_tutup_op($kategori, $bulan, $tahun)
    {
        $kategori = $kategori;
        $bulan = $bulan;
        $tahun = $tahun;
        return view("admin.pdl.detail_daftar_tutup_op")->with(compact('kategori', 'bulan', 'tahun'));
    }

    public function datatable_detail_daftar_tutup_op(Request $request)
    {
        $kategori = $request->kategori;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $arrbul = getMonthList();
        foreach ($arrbul as $key => $value) {
            if ($bulan === $value) {
                $valBulan = $key;
            }
        }

        if ($kategori == 'Pendaftaran') {
            $query = DB::table('data.detail_objek_pajak')
                ->where(DB::raw('EXTRACT(YEAR FROM tanggal_daftar)'), $tahun)
                ->where(DB::raw('EXTRACT(MONTH FROM tanggal_daftar)'), $valBulan)->get();
        } else {
            $query = DB::table('data.detail_objek_pajak')
                ->whereNotNull('tanggal_tutup')
                ->where(DB::raw('EXTRACT(YEAR FROM tanggal_tutup)'), $tahun)
                ->where(DB::raw('EXTRACT(MONTH FROM tanggal_tutup)'), $valBulan)->get();
        }
        $arr = array();
        // dd($query);
        foreach ($query as $key => $d) {
            $arr[] =
                array(
                    "no" => $key + 1,
                    "nama_rekening" => $d->nama_rekening,
                    "nama_objek_pajak" => $d->nama_objek_pajak,
                    "alamat_objek_pajak" => $d->alamat_objek_pajak,
                    "nop" => $d->nop,
                    "npwpd" => $d->npwpd,
                    "nama_subjek_pajak" => $d->nama_subjek_pajak,
                    "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                    "tanggal_daftar" => $d->tanggal_daftar,
                    "tanggal_tutup" => $d->tanggal_tutup,
                    "nama_contact_person" => $d->nama_contact_person,
                    "telp_contact_person" => $d->telp_contact_person
                );
        }
        return Datatables::of($arr)
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
