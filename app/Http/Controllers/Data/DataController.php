<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;


class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //  public function __construct()
    //  {
    //      // $role = get_role();

    //  }


    public function index()
    {
        $akses = get_url_akses();
        if ($akses) {
            return redirect()->route("pad.index");
        } else {
            return view("admin.data.index");
        }
    }

    public function datatables()
    {
        $query = DB::table("data.daftar_data")->whereNull('deleted_at')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                $view = getDaftarData($d->id);
                $route = url('data') . '/' . $view;
                $route_import = strtolower('import_' . $d->table);
                $route_getdata = strtolower('getdata_' . $d->table);
                $file_import =  strtolower($d->table . '.xlsx');

                $aksi = "<div class='btn-group btn-group-pill'>";
                $aksi .= "<a type='button' class='btn btn-info btn-xs btn-data' data-route='" . $route_getdata . "' onclick='get_data($(this))'> <i class='fa fa-download'></i> Ambil Data </a>";
                $aksi .= "<a type='button' class='btn btn-success btn-xs btn-data' data-route='" . $route_import . "' data-file='" . $file_import . "' onclick='import_excel($(this))'><i class='fa fa-file-excel-o'></i> Upload Data</a>";
                $aksi .= "<a class='btn btn-danger btn-xs btn-data' href='" . $route . "'><i class='fa fa-eye'></i> Lihat Data</a>";
                $aksi .= "</div>";

                $arr[] =
                    array(
                        "id" => $d->id,
                        "data" => $d->judul,
                        "table" => $d->table,
                        "view" => $d->view,
                        "menu" => $d->menu,
                        "updated_at" => tgl_full($d->updated_at, 6),
                        "aksi" => $aksi
                    );
            }
        }

        return Datatables::of($arr)
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function target_realisasi_pad()
    {
        return view("admin.data.target_realisasi_pad");
    }

    public function datatable_target_realisasi_pad()
    {

        $query = DB::table("data.target_realisasi_pad")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "level_rekening" => $d->level_rekening,
                        "target" => rupiahFormat($d->target),
                        "realisasi" => rupiahFormat($d->realisasi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function target_realisasi_pajak()
    {
        return view("admin.data.target_realisasi_pajak");
    }

    public function datatable_target_realisasi_pajak()
    {

        $query = DB::table("data.target_realisasi_pajak")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "level_rekening" => $d->level_rekening,
                        "target" => rupiahFormat($d->target),
                        "realisasi" => rupiahFormat($d->realisasi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function target_realisasi_retribusi()
    {
        return view("admin.data.target_realisasi_retribusi");
    }

    public function datatable_target_realisasi_retribusi()
    {

        $query = DB::table("data.target_realisasi_retribusi")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "level_rekening" => $d->level_rekening,
                        "target" => rupiahFormat($d->target),
                        "realisasi" => rupiahFormat($d->realisasi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function komposisi_pad()
    {
        return view("admin.data.komposisi_pad");
    }

    public function datatable_komposisi_pad()
    {

        $query = DB::table("data.komposisi_pad")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "level_rekening" => $d->level_rekening,
                        "target" => rupiahFormat($d->target),
                        "realisasi" => rupiahFormat($d->realisasi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function penerimaan_per_bulan()
    {
        return view("admin.data.penerimaan_per_bulan");
    }

    public function datatable_penerimaan_per_bulan()
    {

        $query = DB::table("data.penerimaan_per_bulan")->orderBy('nama_rekening', 'ASC')->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => (is_null($d->bulan)) ? "-" : getMonth($d->bulan),
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "penerimaan_per_bulan" => rupiahFormat($d->penerimaan_per_bulan),
                        "penerimaan_akumulasi" => rupiahFormat($d->penerimaan_akumulasi),
                        "jumlah_transaksi_per_bulan" => number_format($d->jumlah_transaksi_per_bulan),
                        "jumlah_transaksi_akumulasi" => number_format($d->jumlah_transaksi_akumulasi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => (is_null($d->tanggal_update)) ? "-" : tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function penerimaan_akumulasi()
    {
        return view("admin.data.penerimaan_akumulasi");
    }

    public function datatable_penerimaan_akumulasi()
    {

        $query = DB::table("data.penerimaan_akumulasi")->orderBy('nama_rekening', 'ASC')->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "penerimaan_per_bulan" => rupiahFormat($d->penerimaan_per_bulan),
                        "penerimaan_akumulasi" => rupiahFormat($d->penerimaan_akumulasi),
                        "jumlah_transaksi_per_bulan" => number_format($d->jumlah_transaksi_per_bulan),
                        "jumlah_transaksi_akumulasi" => number_format($d->jumlah_transaksi_akumulasi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function penerimaan_harian()
    {
        return view("admin.data.penerimaan_harian");
    }

    public function datatable_penerimaan_harian()
    {

        $query = DB::table("data.penerimaan_harian")->orderBy('nama_rekening', 'ASC')->orderBy('tanggal', 'DESC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tanggal" => tgl_full($d->tanggal, 0),
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "penerimaan" => rupiahFormat($d->penerimaan),
                        "jumlah_transaksi" => number_format($d->jumlah_transaksi),
                        "tempat_bayar" => $d->tempat_bayar,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function penerimaan_tahun_sppt()
    {
        return view("admin.data.penerimaan_tahun_sppt");
    }

    public function datatable_penerimaan_tahun_sppt()
    {

        $query = DB::table("data.penerimaan_tahun_sppt")->orderBy('tahun_sppt', 'DESC')->orderBy('tahun_bayar', 'DESC')->orderBy('bulan_bayar', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun_bayar" => $d->tahun_bayar,
                        "bulan_bayar" => getMonth($d->bulan_bayar),
                        "tahun_sppt" => $d->tahun_sppt,
                        "nop" => $d->nop,
                        "nominal_pokok" => rupiahFormat($d->nominal_pokok),
                        "nominal_denda" => rupiahFormat($d->nominal_denda),
                        "nominal_terima" => rupiahFormat($d->nominal_terima),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function tunggakan_nop()
    {
        return view("admin.data.tunggakan_nop");
    }

    public function datatable_tunggakan_nop()
    {

        $query = DB::table("data.tunggakan")->orderBy('tahun_sppt', 'DESC')->orderBy('kecamatan', 'ASC')->orderBy('kelurahan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun_sppt" => $d->tahun_sppt,
                        "nop_baku" => number_format($d->nop_baku),
                        "nop_bayar" => number_format($d->nop_bayar),
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function tunggakan_rp()
    {
        return view("admin.data.tunggakan_rp");
    }

    public function datatable_tunggakan_rp()
    {

        $query = DB::table("data.tunggakan")->orderBy('tahun_sppt', 'DESC')->orderBy('kecamatan', 'ASC')->orderBy('kelurahan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun_sppt" => $d->tahun_sppt,
                        "nominal_baku" => rupiahFormat($d->nominal_baku),
                        "nominal_pokok" => rupiahFormat($d->nominal_pokok),
                        "nominal_denda" => rupiahFormat($d->nominal_denda),
                        "nominal_terima" => rupiahFormat($d->nominal_terima),
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function tunggakan_detail()
    {
        return view("admin.data.tunggakan_detail");
    }

    public function datatable_tunggakan_detail()
    {

        $query = DB::table("data.tunggakan_detail")->orderBy('tahun_sppt', 'DESC')->orderBy('kecamatan', 'ASC')->orderBy('kelurahan', 'ASC')->orderBy('nama_subjek_pajak', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun_sppt" => $d->tahun_sppt,
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan,
                        "nominal" => rupiahFormat($d->nominal),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function tunggakan_level()
    {
        return view("admin.data.tunggakan_level");
    }

    public function datatable_tunggakan_level()
    {

        $query = DB::table("data.tunggakan_level")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "level" => $d->level,
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan,
                        "nop" => number_format($d->nop),
                        "nominal" => rupiahFormat($d->nominal),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function tunggakan_level_detail()
    {
        return view("admin.data.tunggakan_level_detail");
    }

    public function datatable_tunggakan_level_detail()
    {

        $query = DB::table("data.tunggakan_level_detail")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "level" => $d->level,
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "jumlah_tahun" => $d->jumlah_tahun,
                        "nominal" => rupiahFormat($d->nominal),
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function pembayaran_tunggakan()
    {
        return view("admin.data.pembayaran_tunggakan");
    }

    public function datatable_pembayaran_tunggakan()
    {

        $query = DB::table("data.pembayaran_tunggakan")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "level_rekening" => $d->level_rekening,
                        "target" => rupiahFormat($d->target),
                        "realisasi" => rupiahFormat($d->realisasi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function penerimaan_tempat_bayar()
    {
        return view("admin.data.penerimaan_tempat_bayar");
    }

    public function datatable_penerimaan_tempat_bayar()
    {

        $query = DB::table("data.penerimaan_tempat_bayar")->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "tempat_bayar" => $d->tempat_bayar,
                        "penerimaan" => rupiahFormat($d->penerimaan),
                        "jumlah_transaksi" => number_format($d->jumlah_transaksi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function proporsi_tempat_bayar()
    {
        return view("admin.data.proporsi_tempat_bayar");
    }

    public function datatable_proporsi_tempat_bayar()
    {

        $query = DB::table("data.proporsi_tempat_bayar")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "tempat_bayar" => $d->tempat_bayar,
                        "persen_nominal" => ($d->persen_nominal) . " %",
                        "persen_jumlah" => ($d->persen_jumlah) . " %",
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function jam_tempat_bayar()
    {
        return view("admin.data.jam_tempat_bayar");
    }

    public function datatable_jam_tempat_bayar()
    {

        $query = DB::table("data.jam_tempat_bayar")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => $d->bulan,
                        "jam" => $d->jam,
                        "jumlah_transaksi" => number_format($d->jumlah_transaksi),
                        "tempat_bayar" => $d->tempat_bayar,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function perbandingan_tempat_bayar()
    {
        return view("admin.data.perbandingan_tempat_bayar");
    }

    public function datatable_perbandingan_tempat_bayar()
    {

        $query = DB::table("data.perbandingan_tempat_bayar")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "level_rekening" => $d->level_rekening,
                        "target" => rupiahFormat($d->target),
                        "realisasi" => rupiahFormat($d->realisasi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function kepatuhan_objek()
    {
        return view("admin.data.kepatuhan_objek");
    }

    public function datatable_kepatuhan_objek()
    {

        $query = DB::table("data.kepatuhan_objek")->orderBy('tahun', 'DESC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "persen" => $d->persen . " %",
                        "nop_baku" => number_format($d->nop_baku),
                        "nop_bayar" => number_format($d->nop_bayar),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function pembayaran_awal()
    {
        return view("admin.data.pembayaran_awal");
    }

    public function datatable_pembayaran_awal()
    {

        $query = DB::table("data.pembayaran_awal")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "tanggal_bayar" => tgl_full($d->tanggal_bayar, 0),
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan,
                        "nominal" => rupiahFormat($d->nominal),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function pembayaran_tinggi()
    {
        return view("admin.data.pembayaran_tinggi");
    }

    public function datatable_pembayaran_tinggi()
    {

        $query = DB::table("data.pembayaran_tinggi")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "tanggal_bayar" => tgl_full($d->tanggal_bayar, 0),
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan,
                        "nominal" => rupiahFormat($d->nominal),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function objek_pajak_wilayah()
    {
        return view("admin.data.objek_pajak_wilayah");
    }

    public function datatable_objek_pajak_wilayah()
    {

        $query = DB::table("data.objek_pajak_wilayah")->orderBy('tahun', 'DESC')->orderBy('kecamatan', 'ASC')->orderBy('kelurahan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan,
                        "nop" => number_format($d->nop),
                        "nominal" => rupiahFormat($d->nominal),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function rekap_tunggakan()
    {
        return view("admin.data.rekap_tunggakan");
    }

    public function datatable_rekap_tunggakan()
    {

        $query = DB::table("data.rekap_tunggakan")->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan),
                        "jumlah_ketetapan" => number_format($d->jumlah_ketetapan),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function detail_tunggakan()
    {
        return view("admin.data.detail_tunggakan");
    }

    public function datatable_detail_tunggakan()
    {

        $query = DB::table("data.detail_tunggakan")->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan),
                        "tanggal_ketetapan" => tgl_full($d->tanggal_ketetapan, 0),
                        "tanggal_jatuh_tempo" => tgl_full($d->tanggal_jatuh_tempo, 0),
                        "masa_awal" => tgl_full($d->masa_awal, 0),
                        "masa_akhir" => tgl_full($d->masa_akhir, 0),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function pelaporan()
    {
        return view("admin.data.pelaporan");
    }

    public function datatable_pelaporan()
    {

        $query = DB::table("data.pelaporan")->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "lapor" => $d->lapor,
                        "belum_bayar" => $d->belum_bayar,
                        "belum_lapor" => $d->belum_lapor,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function detail_pelaporan()
    {
        return view("admin.data.detail_pelaporan");
    }

    public function datatable_detail_pelaporan()
    {

        $query = DB::table("data.detail_pelaporan")->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan),
                        "tanggal_ketetapan" => tgl_full($d->tanggal_ketetapan, 0),
                        "tanggal_jatuh_tempo" => tgl_full($d->tanggal_jatuh_tempo, 0),
                        "masa_awal" => tgl_full($d->masa_awal, 0),
                        "masa_akhir" => tgl_full($d->masa_akhir, 0),
                        "tanggal_bayar" => tgl_full($d->tanggal_bayar, 0),
                        "status_lapor" => $d->status_lapor,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function objek_pajak_belum_lapor()
    {
        return view("admin.data.objek_pajak_belum_lapor");
    }

    public function datatable_objek_pajak_belum_lapor()
    {

        $query = DB::table("data.pelaporan")->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        // "lapor"=> $d->lapor,
                        // "belum_bayar"=> $d->belum_bayar,
                        "belum_lapor" => $d->belum_lapor,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function detail_objek_pajak_belum_lapor()
    {
        return view("admin.data.detail_objek_pajak_belum_lapor");
    }

    public function datatable_detail_objek_pajak_belum_lapor()
    {

        $query = DB::table("data.detail_pelaporan")->where('status_lapor', 'Belum Lapor')->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan),
                        "tanggal_ketetapan" => tgl_full($d->tanggal_ketetapan, 0),
                        "tanggal_jatuh_tempo" => tgl_full($d->tanggal_jatuh_tempo, 0),
                        "masa_awal" => tgl_full($d->masa_awal, 0),
                        "masa_akhir" => tgl_full($d->masa_akhir, 0),
                        "tanggal_bayar" => tgl_full($d->tanggal_bayar, 0),
                        "status_lapor" => $d->status_lapor,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function kontribusi()
    {
        return view("admin.data.kontribusi");
    }

    public function datatable_kontribusi()
    {

        $query = DB::table("data.kontribusi")->orderBy('tahun', 'DESC')->orderBy('nominal', 'DESC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "nominal" => rupiahFormat($d->nominal),
                        "tanggal_bayar" => tgl_full($d->tanggal_bayar, 0),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function jumlah_objek_pajak()
    {
        return view("admin.data.jumlah_objek_pajak");
    }

    public function datatable_jumlah_objek_pajak()
    {

        $query = DB::table("data.jumlah_objek_pajak")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "jumlah" => number_format($d->jumlah),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function jumlah_objek_retribusi()
    {
        return view("admin.data.jumlah_objek_retribusi");
    }

    public function datatable_jumlah_objek_retribusi()
    {

        $query = DB::table("data.jumlah_objek_retribusi")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "jumlah" => number_format($d->jumlah),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function daftar_tutup_objek_pajak()
    {
        return view("admin.data.daftar_tutup_objek_pajak");
    }

    public function datatable_daftar_tutup_objek_pajak()
    {

        $query = DB::table("data.daftar_tutup_objek_pajak")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "tanggal_daftar" => tgl_full($d->tanggal_daftar, 0),
                        "tanggal_tutup" => (is_null($d->tanggal_tutup)) ? '-' : tgl_full($d->tanggal_tutup, 0),
                        // "telp_subjek_pajak"=> $d->telp_subjek_pajak,
                        // "nama_contact_person"=> $d->nama_contact_person,
                        // "telp_contact_person"=> $d->telp_contact_person,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function penerimaan_notaris()
    {
        return view("admin.data.penerimaan_notaris");
    }

    public function datatable_penerimaan_notaris()
    {

        $query = DB::table("data.penerimaan_notaris")->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "notaris" => $d->notaris,
                        "nominal" => rupiahFormat($d->nominal),
                        "jumlah_transaksi" => number_format($d->jumlah_transaksi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function keberatan_bphtb()
    {
        return view("admin.data.keberatan_bphtb");
    }

    public function datatable_keberatan_bphtb()
    {

        $query = DB::table("data.keberatan_bphtb")->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "notaris" => $d->notaris,
                        "nominal_penelitian" => rupiahFormat($d->nominal_penelitian),
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan),
                        "nominal_selisih" => rupiahFormat($d->nominal_selisih),
                        "jumlah_transaksi" => number_format($d->jumlah_transaksi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function rekap_ketetapan()
    {
        return view("admin.data.rekap_ketetapan");
    }

    public function datatable_rekap_ketetapan()
    {

        $query = DB::table("data.rekap_ketetapan")->orderBy('tahun', 'DESC')->orderBy('bulan', 'ASC')->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => getMonth($d->bulan),
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan),
                        "jumlah_transaksi" => number_format($d->jumlah_transaksi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function detail_ketetapan()
    {
        return view("admin.data.detail_ketetapan");
    }

    public function datatable_detail_ketetapan()
    {

        $query = DB::table("data.detail_ketetapan")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nomor_ketetapan" => $d->nomor_ketetapan,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan),
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "tanggal_ketetapan" => tgl_full($d->tanggal_ketetapan, 0),
                        "tanggal_jatuh_tempo" => tgl_full($d->tanggal_jatuh_tempo, 0),
                        "masa_awal" => tgl_full($d->masa_awal, 0),
                        "masa_akhir" => tgl_full($d->masa_akhir, 0),
                        "tanggal_bayar" => tgl_full($d->tanggal_bayar, 0),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function subjek_pajak()
    {
        return view("admin.data.subjek_pajak");
    }

    public function datatable_subjek_pajak()
    {

        $query = DB::table("data.subjek_pajak")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        // "nama_objek_pajak"=> $d->nama_objek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        // "alamat_objek_pajak"=> $d->alamat_objek_pajak,
                        // "tanggal_daftar" => tgl_full($d->tanggal_daftar,0),
                        // "tanggal_tutup" => tgl_full($d->tanggal_tutup,0),
                        "telp_subjek_pajak" => $d->telp_subjek_pajak,
                        "nama_contact_person" => $d->nama_contact_person,
                        "telp_contact_person" => $d->telp_contact_person,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function detail_objek_pajak()
    {
        return view("admin.data.objek_pajak");
    }

    public function datatable_detail_objek_pajak()
    {

        $query = DB::table("data.detail_objek_pajak")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "tanggal_daftar" => tgl_full($d->tanggal_daftar, 0),
                        "tanggal_tutup" => (is_null($d->tanggal_tutup)) ? '-' :  tgl_full($d->tanggal_tutup, 0),
                        "telp_subjek_pajak" => $d->telp_subjek_pajak,
                        "nama_contact_person" => $d->nama_contact_person,
                        "telp_contact_person" => $d->telp_contact_person,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }

    public function objek_pajak_aktif()
    {
        return view("admin.data.objek_pajak_aktif");
    }

    public function datatable_objek_pajak_aktif()
    {

        $query = DB::table("data.objek_pajak_aktif")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "nop" => $d->nop,
                        "npwpd" => $d->npwpd,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "nama_subjek_pajak" => $d->nama_subjek_pajak,
                        "nama_objek_pajak" => $d->nama_objek_pajak,
                        "alamat_subjek_pajak" => $d->alamat_subjek_pajak,
                        "alamat_objek_pajak" => $d->alamat_objek_pajak,
                        "tanggal_daftar" => tgl_full($d->tanggal_daftar, 0),
                        "tanggal_tutup" => (is_null($d->tanggal_tutup)) ? '-' :  tgl_full($d->tanggal_tutup, 0),
                        "telp_subjek_pajak" => $d->telp_subjek_pajak,
                        "nama_contact_person" => $d->nama_contact_person,
                        "telp_contact_person" => $d->telp_contact_person,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)
                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }
    public function tunggakan_buku()
    {
        return view("admin.data.tunggakan_buku");
    }

    public function datatable_tunggakan_buku()
    {

        $query = DB::table("data.tunggakan_buku")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun_sppt,
                        "buku" => $d->buku,
                        "nominal_baku" => rupiahFormat($d->nominal_baku),
                        "nominal_pokok" => rupiahFormat($d->nominal_pokok),
                        "nominal_denda" => rupiahFormat($d->nominal_denda),
                        "nop_baku" => number_format($d->nop_baku),
                        "nop_bayar" => number_format($d->nop_bayar),
                        "kecamatan" => $d->kecamatan,
                        "kelurahan" => $d->kelurahan, 0,
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)

                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }
    public function pelaporan_ppat_bphtb()
    {
        return view("admin.data.pelaporan_ppat_bphtb");
    }

    public function datatable_pelaporan_ppat_bphtb()
    {

        $query = DB::table("data.pelaporan_ppat_bphtb")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => $d->bulan,
                        "nama_ppat" => $d->nama_ppat,
                        "jumlah_laporan" => number_format($d->jumlah_laporan),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)

                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }
    public function rekap_ketetapan_bphtb_nihil_bayar()
    {
        return view("admin.data.rekap_ketetapan_bphtb_nihil_bayar");
    }

    public function datatable_rekap_ketetapan_bphtb_nihil_bayar()
    {

        $query = DB::table("data.rekap_ketetapan_bphtb_nihil_bayar")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => $d->bulan,
                        "nominal_berbayar" => rupiahFormat($d->nominal_berbayar),
                        "jumlah_transaksi" => number_format($d->jumlah_transaksi),
                        "sudah_bayar" => number_format($d->sudah_bayar),
                        "belum_bayar" => number_format($d->belum_bayar),
                        "jumlah_transaksi_nihil" => number_format($d->jumlah_transaksi_nihil),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)

                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }
    public function rekap_ketetapan_bphtb_validasi()
    {
        return view("admin.data.rekap_ketetapan_bphtb_validasi");
    }

    public function datatable_rekap_ketetapan_bphtb_validasi()
    {

        $query = DB::table("data.rekap_ketetapan_bphtb_validasi")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => $d->bulan,
                        "jumlah_ketetapan" => rupiahFormat($d->jumlah_ketetapan),
                        "sudah_divalidasi" => number_format($d->sudah_divalidasi),
                        "belum_divalidasi" => number_format($d->belum_divalidasi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)

                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }
    public function rekap_ketetapan_perolehan_bpthb()
    {
        return view("admin.data.rekap_ketetapan_perolehan_bpthb");
    }

    public function datatable_rekap_ketetapan_perolehan_bpthb()
    {

        $query = DB::table("data.rekap_ketetapan_perolehan_bpthb")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => $d->bulan,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "jenis_perolehan" => $d->jenis_perolehan,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan),
                        "jumlah_transaksi" => number_format($d->jumlah_transaksi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)

                    );
            }
        }

        return Datatables::of($arr)
            // ->rawColumns(['aksi','menu','background'])
            ->make(true);
    }


    public function rekap_ketetapan_peruntukan_bphtb()
    {
        return view("admin.data.rekap_ketetapan_peruntukan_bphtb");
    }

    public function datatable_rekap_ketetapan_peruntukan_bphtb()
    {

        $query = DB::table("data.rekap_ketetapan_peruntukan_bphtb")->get();
        // dd($query);
        $arr = array();
        if ($query->count() > 0) {
            foreach ($query as $key => $d) {
                # code...
                $arr[] =
                    array(
                        "id" => $d->id,
                        "tahun" => $d->tahun,
                        "bulan" => $d->bulan,
                        "nama_rekening" => $d->nama_rekening,
                        "kode_rekening" => $d->kode_rekening,
                        "peruntukan" => $d->peruntukan,
                        "nominal_ketetapan" => rupiahFormat($d->nominal_ketetapan),
                        "jumlah_transaksi" => number_format($d->jumlah_transaksi),
                        "sumber_data" => $d->sumber_data,
                        "tanggal_update" => tgl_full($d->tanggal_update, 0)

                    );
            }
        }

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
