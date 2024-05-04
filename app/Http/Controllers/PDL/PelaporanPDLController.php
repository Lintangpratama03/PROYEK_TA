<?php

namespace App\Http\Controllers\PDL;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PelaporanPDLController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $akses = get_url_akses();
        if($akses){
           return redirect()->route("pad.index");
        }else{
            return view("admin.pdl.pelaporan");
        }
    }

    public function pelaporan_pdl(Request $request)
    {
        // dd($request->all());
        // $jenis_pajak = str_replace(".","",$request->jenis_pajak);
        $tahun = $request->tahun ;
        $kode_rekening = $request->jenis_pajak;
        // dd($tahun);
        $data['sudahbayar'] = $this->get_pelaporan_pdl($tahun,$kode_rekening)['sudahbayar'];
        $data['sudahlapor'] = $this->get_pelaporan_pdl($tahun,$kode_rekening)['sudahlapor'];
        $data['belumlapor'] = $this->get_pelaporan_pdl($tahun,$kode_rekening)['belumlapor'];
        $data['bulan'] = $this->get_pelaporan_pdl($tahun,$kode_rekening)['bulan'];

        // dd($data);
        // dd($data);
        return $data;
    }

    public function get_pelaporan_pdl($thn,$kode_rekening)
    {
        // $kode_rekening = "Pajak Hotel";
   
        $query = DB::table("data.pelaporan")
        ->where('tahun',$thn)
        ->where('kode_rekening', $kode_rekening)
        ->orderBy('bulan','ASC')->get();
       
        if($query->count() > 0){
            foreach ($query as $key => $value) {
                $data['sudahbayar'][] = $value->lapor;
                $data['sudahlapor'][] = $value->belum_bayar;
                $data['belumlapor'][] = $value->belum_lapor;
                $data['bulan'][] = getMonth($value->bulan);
            }
        }else{
                $data['sudahbayar'][] = 0;
                $data['sudahlapor'][] = 0;
                $data['belumlapor'][] = 0;
                $data['bulan'][] = null;
        }

        // dd($data);
 
        return $data;
    }

    function datatable_op_belumlapor(Request $request){
        $jenis_pajak =(int)$request->jenis_pajak;
        $tanggal = $request->tahun;
        // $tanggal = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        // dd($currentDate);
        $rawQuery = "
        select 
        xz.nop,xz.nama_op,xz.alamat_op,xz.nama_wp,xz.alamat_wp,xz.jenis_objek ,xz.nama_pajak,
        sum(belum_lapor) as belum_lapor
        from (
        select
        extract(year from x.tgl1) as tahun,
        extract(month from x.tgl1) as bulan,
        z.nop,z.nama_op,z.alamat_op,z.nama_wp,z.alamat_wp,z.jenis_objek,z.nama_pajak,
        (select count(*) as belum_lapor
        from data.tb_op ab
        where ab.tanggal_daftar::date < x.tgl2 and 
        ab.jenis_objek::int=z.jenis_objek::int and
        ab.nop = z.nop and 
        ab.nama = z.nama_op and
        (ab.tgl_tutup is null or ab.tgl_tutup::date >= x.tgl2) and
        (ab.deleted_at is null or ab.deleted_at::date >= x.tgl2) and
        nop not in (
        select nop from data.tb_penerimaan pen
        where pen.kode_akun_pajak::int = z.jenis_objek and
        pen.masa_pajak_tahun::int = extract(year from tgl1) and
        pen.masa_pajak_bulan::int = extract(month from tgl1) and
        (pen.deleted_at is null or pen.deleted_at::date >= x.tgl2)
        )
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
        (
        select distinct 
        a.nop,a.nama nama_op,a.jalan alamat_op, 
        c.nama nama_wp, c.jalan alamat_wp,
        a.jenis_objek,b.nama_pajak from data.tb_op a
        left join master.tb_jenis_pajak b
        on a.jenis_objek::int = b.id
        left join data.tb_wp c 
        on a.npwp=c.npwp
        ) z
        on 1=1
        where extract(year from x.tgl1) = '".$tanggal."'
        ) xz
        where xz.jenis_objek=".$jenis_pajak." and belum_lapor>0
        group by
        xz.nop,xz.nama_op,xz.alamat_op,xz.nama_wp,xz.alamat_wp,xz.jenis_objek,xz.nama_pajak
        order by nama_pajak
        ";
        // dd($rawQuery);
        $d_data = DB::connection("pgsql_pdl")->select($rawQuery);

        $arr = array();
            foreach ($d_data as $key => $d) {
                $route = url('pdl/pelaporan/detail_belumbayar') . "/".$d->nop;
                $detail = " <a href='" . $route . "' ><u>" . $d->nama_op . "</u> <i class='fa fa-arrow-circle-o-right'></i></a>";
                $arr[] = 
                    array(
                        "nama_rekening"=>$d->nama_pajak,
                        "nama_objek_pajak"=>$detail,
                        "tidak_lapor"=> $d->belum_lapor
                    );
            }
        // }

        return Datatables::of($arr)
        ->rawColumns(['nama_objek_pajak'])
        ->make(true);
    }
    public function detail_belumbayar($nop)
    {
        $nop = $nop;
        return view("admin.pdl.detail_belumbayar")->with(compact('nop'));
    }

    public function datatable_detail_belumbayar(Request $request)
    {
        // dd($request->all());
        $nop = $request->nop;
        // $query = DB::table("data.detail_pelaporan")->where('nop',$nop)->where('status_lapor','Belum Lapor')->get();
        $rawQuery = "
        select tahun,bulan,
        xz.nop,
        xz.nama_op as nama_objek_pajak,
        xz.alamat_op as alamat_objek_pajak,
        xz.nama_wp as nama_subjek_pajak,
        xz.alamat_wp as alamat_subjek_pajak,
        xz.jenis_objek ,
        xz.nama_pajak as nama_rekening,
        xz.npwp as npwpd
        from (
        select
        extract(year from x.tgl1) as tahun,
        extract(month from x.tgl1) as bulan,
        z.nop,z.nama_op,z.alamat_op,z.nama_wp,z.alamat_wp,z.jenis_objek,z.nama_pajak,z.npwp,
        (select count(*) as belum_lapor
        from data.tb_op ab
        where ab.tanggal_daftar::date < x.tgl2 and 
        ab.jenis_objek::int=z.jenis_objek::int and
        ab.nop = z.nop and 
        ab.nama = z.nama_op and
        (ab.tgl_tutup is null or ab.tgl_tutup::date >= x.tgl2) and
        (ab.deleted_at is null or ab.deleted_at::date >= x.tgl2) and
        nop not in (
        select nop from data.tb_penerimaan pen
        where pen.kode_akun_pajak::int = z.jenis_objek and
        pen.masa_pajak_tahun::int = extract(year from tgl1) and
        pen.masa_pajak_bulan::int = extract(month from tgl1) and
        (pen.deleted_at is null or pen.deleted_at::date >= x.tgl2) 
        )
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
        (
        select distinct 
        a.nop,
        a.nama nama_op,
        a.jalan alamat_op, 
        c.nama nama_wp, 
        c.jalan alamat_wp,
        a.npwp,
        a.jenis_objek,
        b.nama_pajak 
        from data.tb_op a
        left join master.tb_jenis_pajak b
        on a.jenis_objek::int = b.id
        left join data.tb_wp c 
        on a.npwp=c.npwp
        ) z
        on 1=1
        where x.tgl1 >= '2023-8-1'
        ) xz
        where xz.nop='".$nop."' and belum_lapor>0
        order by tahun desc,bulan desc
        ";
        $query = DB::connection("pgsql_pdl")->select($rawQuery);
        // dd($query);
        $arr = array();
        // if($query->count() > 0){
            foreach ($query as $key => $d) {
                # code...
                $arr[] = 
                    array(
                        "nop"=>$d->nop,
                        "npwpd"=>$d->npwpd,
                        "nama_objek_pajak"=>$d->nama_objek_pajak,
                        "alamat_objek_pajak"=>$d->alamat_objek_pajak,
                        "nama_subjek_pajak"=>$d->nama_subjek_pajak,
                        "alamat_subjek_pajak"=>$d->alamat_subjek_pajak,
                        "nama_rekening"=>$d->nama_rekening,
                        "tahun"=>$d->tahun,
                        "bulan"=>getMonth($d->bulan),
                    );
            }
        // }
        return Datatables::of($arr)
        // ->rawColumns(['aksi','menu','background'])
        ->make(true);
    }


    public function detail_pelaporan($jenispajak,$tahun,$bulan,$status)
    {
        $jenispajak = $jenispajak;
        $tahun = $tahun;
        $bulan = $bulan;
        $status = $status;
        return view("admin.pdl.detail_pelaporan")->with(compact('jenispajak','tahun','bulan','status'));
    }

    public function datatable_detail_pelaporan(Request $request)
    {
        //   dd($request->all());
        $jenispajak = $request->jenispajak;
        $tahun = $request->tahun;
        $bulan = (int)$request->bulan + 1;
        $status = $request->status;
        $data = 1;
        if(!is_null($jenispajak)){
            $jenispajak =$request->jenispajak . "0";
            $query = "select id from master.tb_jenis_pajak where kode_rekening = '".$jenispajak."'";
            $data = DB::connection("pgsql_pdl")->select($query)[0]->id;
        }

        // dd($data);

        if ($status==0) {
            // dd($bulan);
            $status_lapor = "Sudah Bayar";
            $rawQuery = "
            select 
            a.nop,
            a.npwpd,
            a.masa_pajak_tahun tahun,
            a.masa_pajak_bulan bulan,
            b.nama_pajak nama_rekening,
            b.kode_rekening,
            d.pokok_pajak nominal_ketetapan,
            a.nama_wp,
            a.alamat_wp,
            c.nama nama_op,
            c.jalan alamat_op,
            d.tanggal_pendataan tanggal_ketetapan,
            a.masa_awal,
            a.masa_akhir,
            a.jatuh_tempo tanggal_jatuh_tempo,
            'sudah bayar' as status_lapor,
            'SIMPADAMA' as sumber_data
            from data.tb_penerimaan a
            left join master.tb_jenis_pajak b
            on a.kode_akun_pajak::int = b.id
            left join data.tb_op c
            on c.nop=a.nop
            left join data.tb_ketetapan d
            on d.id=a.id_ketetapan
            where a.ntpp is not null
            -- kondisi cari
            and b.id=".$data." and a.masa_pajak_tahun='".$tahun."' and a.masa_pajak_bulan='".$bulan."' and
            (a.deleted_at is null or a.deleted_at::date >= 
            TO_DATE(concat('2023',right(concat('0','12'),2),'01'),'YYYYMMDD')+interval '1 month' )            
            ";
        }elseif ($status==1) {
            $status_lapor = "Belum Bayar";
            $rawQuery = "
            select 
            a.nop,
            a.npwpd,
            a.masa_pajak_tahun tahun,
            a.masa_pajak_bulan bulan,
            b.nama_pajak nama_rekening,
            b.kode_rekening,
            d.pokok_pajak nominal_ketetapan,
            a.nama_wp,
            a.alamat_wp,
            c.nama nama_op,
            c.jalan alamat_op,
            d.tanggal_pendataan tanggal_ketetapan,
            a.masa_awal,
            a.masa_akhir,
            a.jatuh_tempo tanggal_jatuh_tempo,
            'sudah lapor, belum bayar' as status_lapor,
            'SIMPADAMA' as sumber_data
            from data.tb_penerimaan a
            left join master.tb_jenis_pajak b
            on a.kode_akun_pajak::int = b.id
            left join data.tb_op c
            on c.nop=a.nop
            left join data.tb_ketetapan d
            on d.id=a.id_ketetapan
            where a.ntpp is null
            -- kondisi cari
            and b.id=".$data." and a.masa_pajak_tahun='".$tahun."' and a.masa_pajak_bulan='".$bulan."' and
            (a.deleted_at is null or a.deleted_at::date >= 
            TO_DATE(concat('2023',right(concat('00','12'),2),'01'),'YYYYMMDD')+interval '1 month' )
            ";

        }elseif ($status==2) {
            $status_lapor = "Belum Lapor";
            $rawQuery = "
            select
            ab.nop,
            ab.npwp as npwpd,
            --thn bln berdasarkan search
            '".$tahun."' as tahun,
            '".$bulan."' as bulan,
            b.nama_pajak nama_rekening,
            b.kode_rekening,
            null as nominal_ketetapan,
            cd.nama as nama_wp,
            cd.jalan as alamat_wp,
            ab.nama as nama_op,
            ab.jalan as alamat_op
            from data.tb_op ab
            left join master.tb_jenis_pajak b
            on ab.jenis_objek::int = b.id
            left join data.tb_wp cd
            on cd.npwp=ab.npwp
            --filter thn bln berdasarkan search
            where ab.tanggal_daftar::date < TO_DATE(concat('".$tahun."',right(concat('00','".$bulan."'),2),'01'),'YYYYMMDD')+interval '1 month' and 
            --filter thn bln berdasarkan search +filter pjk
            (ab.tgl_tutup is null or ab.tgl_tutup::date >= TO_DATE(concat('".$tahun."',right(concat('00','".$bulan."'),2),'01'),'YYYYMMDD')+interval '1 month') and
            (ab.deleted_at is null or ab.deleted_at::date >= TO_DATE(concat('".$tahun."',right(concat('00','".$bulan."'),2),'01'),'YYYYMMDD')+interval '1 month') and
            ab.jenis_objek::int=".$data." and 
            ab.nop not in (
            select distinct nop from data.tb_penerimaan
            --filter thn bln pjk
            where masa_pajak_tahun='".$tahun."' 
            and masa_pajak_bulan='".$bulan."'
            and kode_akun_pajak='".$data."'
            and (deleted_at is null or deleted_at::date >= TO_DATE(concat('".$tahun."',right(concat('00','".$bulan."'),2),'01'),'YYYYMMDD')+interval '1 month' )
            )   
            ";
        }
        // $query = DB::table("data.detail_pelaporan")->where('kode_rekening',$jenispajak)->where('tahun',$tahun)->where('bulan',$bulan+1)->where('status_lapor',$status_lapor)->get();
        $query = DB::connection("pgsql_pdl")->select($rawQuery);
        // dd($query);
        $arr = array();
        // if($query->count() > 0){
            foreach ($query as $key => $d) {
                $arr[] = 
                    array(
                        "nop"=>$d->nop,
                        "npwpd"=>$d->npwpd,
                        "nama_objek_pajak"=>(isset($d->nama_op))?$d->nama_op:$d->nama_objek_pajak,
                        "alamat_objek_pajak"=>(isset($d->alamat_op))?$d->alamat_op:$d->alamat_objek_pajak,
                        "nama_subjek_pajak"=>(isset($d->nama_wp))?$d->nama_wp:$d->nama_subjek_pajak,
                        "alamat_subjek_pajak"=>(isset($d->alamat_wp))?$d->alamat_wp:$d->alamat_subjek_pajak,
                        "nama_rekening"=>$d->nama_rekening,
                        "tahun"=>$d->tahun,
                        "bulan"=>getMonth($d->bulan),
                        "status_lapor"=>(isset($d->status_lapor))?$d->status_lapor:$status_lapor
                    );
            }
        // }
        return Datatables::of($arr)
        // ->rawColumns(['aksi','menu','background'])
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
