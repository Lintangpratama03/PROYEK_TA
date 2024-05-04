<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Validation\Validator;
use Session;
use App\Imports\ImportTargetRealisasi;
use App\Imports\ImportPenerimaanBulanan;
use App\Imports\ImportPenerimaanHarian;
use App\Imports\ImportPenerimaanTahunSPPT;
use App\Imports\ImportTunggakan;
use App\Imports\ImportTunggakanDetail;
use App\Imports\ImportTunggakanLevel;
use App\Imports\ImportTunggakanLevelDetail;
use App\Imports\ImportPenerimaanTempatBayar;
use App\Imports\ImportProporsiTempatBayar;
use App\Imports\ImportJamTempatBayar;
use App\Imports\ImportKepatuhanObjek;
use App\Imports\ImportPembayaranObjek;
use App\Imports\ImportObjekPajakWilayah;
use App\Imports\ImportRekapTunggakan;
use App\Imports\ImportDetailTunggakan;
use App\Imports\ImportPelaporan;
use App\Imports\ImportDetailPelaporan;
use App\Imports\ImportKontribusi;
use App\Imports\ImportDetailObjekPajak;
use App\Imports\ImportPenerimaanNotaris;
use App\Imports\ImportKeberatanBPHTB;
use App\Imports\ImportRekapKetetapan;
use App\Imports\ImportDetailKetetapan;
use App\Imports\ImportObjekPajak;
use App\Imports\ImportPelaporanPPATBPHTB;
use App\Imports\ImportTunggakanBuku;
use App\Imports\ImportRekapKetetapanBPHTBNihilBayar;
use App\Imports\ImportRekapKetetapanBPHTBValidasi;
use App\Imports\ImportRekapKetetapanPeruntukanBPHTB;
use App\Imports\ImportRekapKetetapanPerolehanBPHTB;
use App\Imports\ImportTargetPajak;
use App\Imports\TargetPajakImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function import_target_realisasi(Request $request)
    {
        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportTargetRealisasi, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'TARGET_REALISASI')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport! ' . $th);
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }
    public function import_penerimaan_bulanan(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportPenerimaanBulanan, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'PENERIMAAN_BULANAN')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }
    public function import_penerimaan_harian(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // dd($nama_file);
            // import data
            Excel::import(new ImportPenerimaanHarian, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'PENERIMAAN_HARIAN')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_penerimaan_tahun_sppt(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportPenerimaanTahunSPPT, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'PENERIMAAN_TAHUN_SPPT')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_tunggakan(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportTunggakan, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'TUNGGAKAN')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport! ' . $th);
            throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_tunggakan_detail(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportTunggakanDetail, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'TUNGGAKAN_DETAIL')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_tunggakan_level(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportTunggakanLevel, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'TUNGGAKAN_LEVEL')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_tunggakan_level_detail(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportTunggakanLevelDetail, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'TUNGGAKAN_LEVEL_DETAIL')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_penerimaan_tempat_bayar(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportPenerimaanTempatBayar, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'PENERIMAAN_TEMPAT_BAYAR')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_proporsi_tempat_bayar(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportProporsiTempatBayar, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'PROPORSI_TEMPAT_BAYAR')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_kepatuhan_objek(Request $request)
    {


        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportKepatuhanObjek, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'KEPATUHAN_OBJEK')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }

        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_pembayaran_objek(Request $request)
    {


        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportPembayaranObjek, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'PEMBAYARAN_OBJEK')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }

        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_jam_tempat_bayar(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportJamTempatBayar, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'JAM_TEMPAT_BAYAR')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_objek_pajak_wilayah(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportObjekPajakWilayah, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'OBJEK_PAJAK_WILAYAH')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_rekap_tunggakan(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportRekapTunggakan, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'REKAP_TUNGGAKAN')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }


    public function import_detail_tunggakan(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportDetailTunggakan, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'DETAIL_TUNGGAKAN')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_pelaporan(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportPelaporan, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'PELAPORAN')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_detail_pelaporan(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportDetailPelaporan, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'DETAIL_PELAPORAN')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }
    public function import_kontribusi(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportKontribusi, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'KONTRIBUSI')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }
    public function import_detail_objek_pajak(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportDetailObjekPajak, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'DETAIL_OBJEK_PAJAK')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_penerimaan_notaris(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportPenerimaanNotaris, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'PENERIMAAN_NOTARIS')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_keberatan_bphtb(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportKeberatanBPHTB, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'KEBERATAN_BPHTB')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_rekap_ketetapan(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportRekapKetetapan, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'REKAP_KETETAPAN')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport! ' . $th);
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_detail_ketetapan(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportDetailKetetapan, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'DETAIL_KETETAPAN')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_objek_pajak(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportObjekPajak, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'OBJEK_PAJAK')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!');
            //throw $th;
        }


        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_tunggakan_buku(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportTunggakanBuku, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'TUNGGAKAN_BUKU')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }
        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_pelaporan_ppat_bphtb(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportPelaporanPPATBPHTB, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'PELAPORAN_PPAT_BPHTB')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }
        // alihkan halaman kembali
        return redirect("data/");
    }
    public function import_rekap_ketetapan_bphtb_validasi(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportRekapKetetapanBPHTBValidasi, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'REKAP_KETETAPAN_BPHTB_VALIDASI')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }
        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_rekap_ketetapan_bphtb_nihil_bayar(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportRekapKetetapanBPHTBNihilBayar, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'REKAP_KETETAPAN_BPHTB_NIHIL_BAYAR')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }
        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_rekap_ketetapan_perolehan_bpthb(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportRekapKetetapanPerolehanBPHTB, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'REKAP_KETETAPAN_PEROLEHAN_BPTHB')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }
        // alihkan halaman kembali
        return redirect("data/");
    }

    public function import_rekap_ketetapan_peruntukan_bphtb(Request $request)
    {

        $now = date("Y-m-d H:i:s");
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportRekapKetetapanPeruntukanBPHTB, public_path('/file_import/' . $nama_file));
            $arrData = [
                'updated_at' => $now
            ];
            DB::table("data.daftar_data")->where('table', 'REKAP_KETETAPAN_PERUNTUKAN_BPHTB')->update($arrData);
            Session::flash('sukses', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }
        // alihkan halaman kembali
        return redirect("data/");
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

    public function import_target_pajak(Request $request)
    {
        try {
            // validasi
            $this->validate($request, [
                'file' => 'required|mimes:csv,xls,xlsx'
            ]);
            // menangkap file excel
            $file = $request->file('file');
            // membuat nama file unik
            $nama_file = rand() . '-' . $file->getClientOriginalName();
            // upload ke folder file_siswa di dalam folder public
            $file->move('file_import', $nama_file);
            // import data
            Excel::import(new ImportTargetPajak, public_path('/file_import/' . $nama_file));
            Session::flash('success', 'Data Berhasil Diimport!');
        } catch (\Throwable $th) {
            Session::flash('error', 'Data Gagal Diimport!' . $th);
            //throw $th;
        }
        // alihkan halaman kembali
        return redirect("target_pajak/");
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
