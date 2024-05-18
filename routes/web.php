<?php

use Illuminate\Support\Facades\DB;
use App\Http\Middleware\Authsession;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$middleware_auth = Authsession::class;

Route::group(['prefix' => "login"], function () {
    Route::get('/', 'Auth\LoginController@index')->name('login.page');
    Route::post('/doLogin', 'Auth\LoginController@doLogin')->name('login.doLogin');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/reset_password', 'Auth\LoginController@resetPassword')->name('login.reset.password');
    Route::post('/do_reset_password', 'Auth\LoginController@doResetPassword')->name('do.reset.password');
});

Route::group(['prefix' => "register"], function () {
    Route::get('/', 'Auth\RegisterController@index')->name('register.page');
    Route::post('/store', 'Auth\RegisterController@store')->name('register.store');
});

// Route::get('/dashboard', 'Dashboard\DashboardIframeController@index_iframe')->name('dashboardv2');

Route::middleware($middleware_auth)->group(function () {
    Route::group(['prefix' => "login"], function () {
        Route::post('/impersonate', 'Auth\LoginController@doImpersonate')->name('login.impersonate');
        Route::get('/impersonate/stop', 'Auth\LoginController@stopImpersonate')->name('login.impersonate.stop');
    });
    Route::get('/download-user-manual', 'Download\UserManualController@downloadUserManual')->name('download-user-manual');
    Route::get('/user_reset_password', 'Auth\LoginController@userResetPassword')->name('user.reset_password');
    Route::post('/user_do_reset_password', 'Auth\LoginController@userDoResetPassword')->name('user.do_reset_password');

    Route::get('/', function () {
        return view("admin.pad.index");
    });


    Route::group(['prefix' => "home"], function () {
        Route::get('/dashboard', 'Dashboard\DashboardController@index')->name('dashboard');
    });


    Route::group(['prefix' => "wilayah"], function () {
        Route::get('/select2Desa', 'Master\WilayahController@select2Desa')->name('select2Desa');
        Route::get('/select2Kecamatan', 'Master\WilayahController@select2Kecamatan')->name('select2Kecamatan');
        Route::get('/select2Kabupaten', 'Master\WilayahController@select2Kabupaten')->name('select2Kabupaten');
        Route::get('/select2Provinsi', 'Master\WilayahController@select2Provinsi')->name('select2Provinsi');
    });

    Route::group(['prefix' => "master"], function () {

        Route::group(['prefix' => "group"], function () {
            Route::get('/', 'Master\GroupController@index')->name('group');
            Route::get('/get_data', 'Master\GroupController@get_data')->name('group.get_data');
            Route::post('/simpan', 'Master\GroupController@store')->name('group.simpan');
            Route::post('/hapus', 'Master\GroupController@hapus')->name('group.hapus');

            Route::get('detail/{id}', 'Master\GroupController@detail')->name('group.detail');
            Route::post('get_data_detail', 'Master\GroupController@get_data_detail')->name('group.get_data_detail');
            Route::post('detail/hapus', 'Master\GroupController@hapus_detail')->name('group.hapus_detail');
            Route::post('detail/get_menu', 'Master\GroupController@get_menu');
            Route::get('detail/get_menu/{id}', 'Master\GroupController@get_menu');
            Route::post('detail/simpan', 'Master\GroupController@simpan_menu')->name('group.simpan_menu');

            //select2
            Route::get('select2', 'Master\GroupController@select2Group')->name("select2group");
        });

        Route::group(['prefix' => "menu"], function () {
            Route::get('/', 'Master\MenuController@index')->name('menu');
            Route::get('/get_data', 'Master\MenuController@get_data')->name('menu.get_data');
            Route::post('/simpan', 'Master\MenuController@store')->name('menu.simpan');
            Route::post('/hapus', 'Master\MenuController@hapus')->name('menu.hapus');
        });

        Route::group(['prefix' => "user"], function () {
            Route::name('master.user.')->group(function () {
                Route::get('/', 'Master\UserController@index')->name('index');
                Route::post('/datatables', 'Master\UserController@datatables')->name('datatables');
                Route::get('/create', 'Master\UserController@create')->name('create');
                Route::post('/store', 'Master\UserController@store')->name('store');
                Route::post('/show', 'Master\UserController@show')->name('show');
                Route::post('/edit', 'Master\UserController@edit')->name('edit');
                Route::post('/update', 'Master\UserController@update')->name('update');
                Route::post('/destory', 'Master\UserController@destroy')->name('destroy');
                Route::post('/banned', 'Master\UserController@banned')->name('banned');
                Route::post('/unbanned', 'Master\UserController@unbanned')->name('unbanned');
            });
        });
    });


    Route::group(['prefix' => 'pbb'], function () {
        Route::name('pbb.')->group(function () {

            Route::prefix('penerimaan')->name('penerimaan.')->group(function () {
                Route::get('/get_wilayah', 'PBB\PenerimaanController@get_wilayah')->name('get_wilayah');
                Route::get('/', 'PBB\PenerimaanController@index')->name('index');
                Route::get('/datatable_penerimaan_tahun', 'PBB\PenerimaanController@datatable_penerimaan_tahun')->name('datatable_penerimaan_tahun');
                Route::get('/penerimaan_perbulan', 'PBB\PenerimaanController@penerimaan_perbulan')->name('penerimaan_perbulan');
                Route::get('/penerimaan_akumulasi', 'PBB\PenerimaanController@penerimaan_akumulasi')->name('penerimaan_akumulasi');
                Route::get('/penerimaan_harian', 'PBB\PenerimaanController@penerimaan_harian')->name('penerimaan_harian');

                Route::get('detail_penerimaan_tahun_kecamatan/{tahun_sppt}/{tahun_bayar}', 'PBB\PenerimaanController@detail_penerimaan_tahun_kecamatan')->name('detail_penerimaan_tahun_kecamatan');
                Route::get('datatable_penerimaan_tahun_kecamatan', 'PBB\PenerimaanController@datatable_penerimaan_tahun_kecamatan')->name('datatable_penerimaan_tahun_kecamatan');

                Route::get('detail_penerimaan_tahun_wp/{tahun_sppt}/{tahun_bayar}/{kecematan}/{kelurahan}', 'PBB\PenerimaanController@detail_penerimaan_tahun_wp')->name('detail_penerimaan_tahun_wp');
                Route::get('datatable_penerimaan_tahun_wp', 'PBB\PenerimaanController@datatable_penerimaan_tahun_wp')->name('datatable_penerimaan_tahun_wp');


                Route::get('datatable_detail_penerimaan_perbulan', 'PBB\PenerimaanController@datatable_detail_penerimaan_perbulan')->name('datatable_detail_penerimaan_perbulan');
                Route::get('detail_penerimaan_perbulan/{tahun}/{bulan}/{kecamatan?}/{kelurahan?}', 'PBB\PenerimaanController@detail_penerimaan_perbulan')->name('detail_penerimaan_perbulan');

                Route::get('datatable_detail_penerimaan_harian', 'PBB\PenerimaanController@datatable_detail_penerimaan_harian')->name('datatable_detail_penerimaan_harian');
                Route::get('detail_penerimaan_harian/{tanggal}/{kecamatan?}/{kelurahan?}', 'PBB\PenerimaanController@detail_penerimaan_harian')->name('detail_penerimaan_harian');

                Route::get('/datatable_rekap_penerimaan', 'PBB\PenerimaanController@datatable_rekap_penerimaan')->name('datatable_rekap_penerimaan');
                Route::get('/show_qty_penerimaan', 'PBB\PenerimaanController@show_qty_penerimaan')->name('show_qty_penerimaan');
            });

            Route::prefix('tunggakan')->name('tunggakan.')->group(function () {
                Route::get('/', 'PBB\TunggakanController@index')->name('index');
                Route::get('/get_wilayah', 'PBB\TunggakanController@get_wilayah')->name('get_wilayah');
                Route::get('/datatable_tunggakan_nop', 'PBB\TunggakanController@datatable_tunggakan_nop')->name('datatable_tunggakan_nop');
                Route::get('/datatable_tunggakan_nominal', 'PBB\TunggakanController@datatable_tunggakan_nominal')->name('datatable_tunggakan_nominal');
                Route::get('/datatable_tunggakan_level', 'PBB\TunggakanController@datatable_tunggakan_level')->name('datatable_tunggakan_level');
                Route::get('/datatable_pembayaran_tunggakan', 'PBB\TunggakanController@datatable_pembayaran_tunggakan')->name('datatable_pembayaran_tunggakan');
                Route::get('/datatable_tunggakan_buku', 'PBB\TunggakanController@datatable_tunggakan_buku')->name('datatable_tunggakan_buku');

                Route::get('/detail', 'PBB\TunggakanController@detail')->name('detail');
                Route::get('/datatable_detail_tunggakan', 'PBB\TunggakanController@datatable_detail_tunggakan')->name('datatable_detail_tunggakan');

                Route::get('sub_tunggakan_nop/{nop}', 'PBB\TunggakanController@sub_tunggakan_nop')->name('sub_tunggakan_nop');
                Route::get('/datatable_sub_tunggakan_nop', 'PBB\TunggakanController@datatable_sub_tunggakan_nop')->name('datatable_sub_tunggakan_nop');

                Route::get('detail_tunggakan_nop/{tahun}/{wilayah}/{nama_wilayah?}', 'PBB\TunggakanController@detail_tunggakan_nop')->name('detail_tunggakan_nop');
                Route::get('/datatable_detail_tunggakan_nop', 'PBB\TunggakanController@datatable_detail_tunggakan_nop')->name('datatable_detail_tunggakan_nop');

                Route::get('detail_tunggakan_level/{level}/{wilayah}/{nama_wilayah?}', 'PBB\TunggakanController@detail_tunggakan_level')->name('detail_tunggakan_level');
                Route::get('/datatable_detail_tunggakan_level', 'PBB\TunggakanController@datatable_detail_tunggakan_level')->name('datatable_detail_tunggakan_level');
                Route::get('detail_tunggakan_level_nop/{nop}', 'PBB\TunggakanController@detail_tunggakan_level_nop')->name('detail_tunggakan_level_nop');
                Route::get('/datatable_detail_tunggakan_level_nop', 'PBB\TunggakanController@datatable_detail_tunggakan_level_nop')->name('datatable_detail_tunggakan_level_nop');

                Route::get('detail_tunggakan_buku/{buku}/{tahun}/{kecamatan}/{kelurahan}', 'PBB\TunggakanController@detail_tunggakan_buku')->name('detail_tunggakan_buku');
                Route::get('/datatable_detail_tunggakan_buku', 'PBB\TunggakanController@datatable_detail_tunggakan_buku')->name('datatable_detail_tunggakan_buku');

                Route::get('detail_pembayaran_tunggakan/{tahun_sppt}/{tahun_bayar}', 'PBB\TunggakanController@detail_pembayaran_tunggakan')->name('detail_pembayaran_tunggakan');
                Route::get('datatable_detail_pembayaran_tunggakan', 'PBB\TunggakanController@datatable_detail_pembayaran_tunggakan')->name('datatable_detail_pembayaran_tunggakan');

                Route::get('detail_pembayaran_tunggakan_wp/{tahun_sppt}/{tahun_bayar}/{kecematan}/{kelurahan}', 'PBB\TunggakanController@detail_pembayaran_tunggakan_wp')->name('detail_pembayaran_tunggakan_wp');
                Route::get('datatable_pembayaran_tunggakan_wp', 'PBB\TunggakanController@datatable_pembayaran_tunggakan_wp')->name('datatable_pembayaran_tunggakan_wp');
            });
            Route::prefix('peta-tunggakan')->name('peta-tunggakan.')->group(function () {
                Route::get('/', 'PBB\PetaTunggakanController@index')->name('index');
            });
            Route::prefix('tempatbayar')->name('tempatbayar.')->group(function () {
                Route::get('/', 'PBB\TPController@index')->name('index');
                Route::get('/datatable_penerimaan_tahun', 'PBB\TPController@datatable_penerimaan_tahun')->name('datatable_penerimaan_tahun');
                Route::get('/penerimaan_perbulan', 'PBB\TPController@penerimaan_perbulan')->name('penerimaan_perbulan');
            });

            Route::prefix('op')->name('op.')->group(function () {
                Route::get('/', 'PBB\OPController@index')->name('index');
                Route::get('/get_wilayah', 'PBB\OPController@get_wilayah')->name('get_wilayah');
                Route::get('/datatable_kepatuhan_wp', 'PBB\OPController@datatable_kepatuhan_wp')->name('datatable_kepatuhan_wp');
                Route::get('/datatable_pembayaran_awal', 'PBB\OPController@datatable_pembayaran_awal')->name('datatable_pembayaran_awal');
                Route::get('/datatable_jumlah_transaksi_pbb', 'PBB\OPController@datatable_jumlah_transaksi_pbb')->name('datatable_jumlah_transaksi_pbb');
                Route::get('/datatable_pembayaran_tinggi', 'PBB\OPController@datatable_pembayaran_tinggi')->name('datatable_pembayaran_tinggi');
                Route::get('/datatable_op_wilayah', 'PBB\OPController@datatable_op_wilayah')->name('datatable_op_wilayah');
                Route::get('/get_chart_op_wilayah', 'PBB\OPController@get_chart_op_wilayah')->name('get_chart_op_wilayah');
                Route::get('/search', 'PBB\OPController@search')->name('search');
                Route::post('/query_search', 'PBB\OPController@query_search')->name('query_search');

                Route::get('datatable_detail_kepatuhan_wp', 'PBB\OPController@datatable_detail_kepatuhan_wp')->name('datatable_detail_kepatuhan_wp');
                Route::get('detail_kepatuhan_wp/{tahun}/{kecamatan?}/{kelurahan?}', 'PBB\OPController@detail_kepatuhan_wp')->name('detail_kepatuhan_wp');
            });
        });
    });

    Route::group(['prefix' => 'pdl'], function () {
        Route::name('pdl.')->group(function () {

            Route::prefix('penerimaan')->name('penerimaan.')->group(function () {
                Route::get('/', 'PDL\PenerimaanPDLController@index')->name('index');
                Route::get('/get_wilayah', 'PDL\PenerimaanPDLController@get_wilayah')->name('get_wilayah');
                Route::get('/penerimaan_perbulan', 'PDL\PenerimaanPDLController@penerimaan_perbulan')->name('penerimaan_perbulan');
                Route::get('/penerimaan_akumulasi', 'PDL\PenerimaanPDLController@penerimaan_akumulasi')->name('penerimaan_akumulasi');
                Route::get('/penerimaan_harian', 'PDL\PenerimaanPDLController@penerimaan_harian')->name('penerimaan_harian');

                Route::get('datatable_detail_penerimaan_perbulan', 'PDL\PenerimaanPDLController@datatable_detail_penerimaan_perbulan')->name('datatable_detail_penerimaan_perbulan');
                Route::get('detail_penerimaan_perbulan/{pajak}/{tahun}/{bulan}/{kecamatan?}/{kelurahan?}', 'PDL\PenerimaanPDLController@detail_penerimaan_perbulan')->name('detail_penerimaan_perbulan');

                Route::get('datatable_detail_penerimaan_harian', 'PDL\PenerimaanPDLController@datatable_detail_penerimaan_harian')->name('datatable_detail_penerimaan_harian');
                Route::get('detail_penerimaan_harian/{tanggal}/{pajak}/{kecamatan?}/{kelurahan?}', 'PDL\PenerimaanPDLController@detail_penerimaan_harian')->name('detail_penerimaan_harian');
            });

            Route::prefix('tunggakan')->name('tunggakan.')->group(function () {
                Route::get('/', 'PDL\TunggakanPDLController@index')->name('index');
                Route::get('/get_wilayah', 'PDL\TunggakanPDLController@get_wilayah')->name('get_wilayah');
                Route::get('/datatable_tunggakan_pdl', 'PDL\TunggakanPDLController@datatable_tunggakan_pdl')->name('datatable_tunggakan_pdl');
                Route::get('/datatable_tunggakan_wp', 'PDL\TunggakanPDLController@datatable_tunggakan_wp')->name('datatable_tunggakan_wp');

                Route::get('/get_wilayah', 'PDL\TunggakanPDLController@get_wilayah')->name('get_wilayah');
                Route::get('detail/{pajak}/{tahun}/{kecamatan?}/{kelurahan?}', 'PDL\TunggakanPDLController@detail')->name('detail');
                Route::get('/datatable_detail', 'PDL\TunggakanPDLController@datatable_detail')->name('datatable_detail');
                Route::get('detail_op/{nop}/{pajak}/{tahun}/{kecamatan?}/{kelurahan?}', 'PDL\TunggakanPDLController@detail_op')->name('detail_op');
                Route::get('/datatable_detail_op', 'PDL\TunggakanPDLController@datatable_detail_op')->name('datatable_detail_op');
                Route::get('detail_wp/{npwpd}/', 'PDL\TunggakanPDLController@detail_wp')->name('detail_wp');
                Route::get('/datatable_detail_wp', 'PDL\TunggakanPDLController@datatable_detail_wp')->name('datatable_detail_wp');
            });

            Route::prefix('pelaporan')->name('pelaporan.')->group(function () {
                Route::get('/', 'PDL\PelaporanPDLController@index')->name('index');
                Route::get('/datatable_op_belumlapor', 'PDL\PelaporanPDLController@datatable_op_belumlapor')->name('datatable_op_belumlapor');
                Route::get('/pelaporan_pdl', 'PDL\PelaporanPDLController@pelaporan_pdl')->name('pelaporan_pdl');

                Route::get('detail_belumbayar/{nop}', 'PDL\PelaporanPDLController@detail_belumbayar')->name('detail_belumbayar');
                Route::get('/datatable_detail_belumbayar', 'PDL\PelaporanPDLController@datatable_detail_belumbayar')->name('datatable_detail_belumbayar');
                Route::get('detail_pelaporan/{jenispajak}/{tahun}/{bulan}/{status}', 'PDL\PelaporanPDLController@detail_pelaporan')->name('detail_pelaporan');
                Route::get('/datatable_detail_pelaporan', 'PDL\PelaporanPDLController@datatable_detail_pelaporan')->name('datatable_detail_pelaporan');
            });

            Route::prefix('op')->name('op.')->group(function () {
                Route::get('/', 'PDL\ObjekPajakPDLController@index')->name('index');
                Route::get('/datatable_kontribusi_op', 'PDL\ObjekPajakPDLController@datatable_kontribusi_op')->name('datatable_kontribusi_op');
                Route::get('/datatable_op_aktif_tutup', 'PDL\ObjekPajakPDLController@datatable_op_aktif_tutup')->name('datatable_op_aktif_tutup');
                Route::get('/datatable_op_wilayah', 'PDL\ObjekPajakPDLController@datatable_op_wilayah')->name('datatable_op_wilayah');
                Route::get('/get_chart_op', 'PDL\ObjekPajakPDLController@get_chart_op')->name('get_chart_op');
                Route::get('/get_total_op', 'PDL\ObjekPajakPDLController@get_total_op')->name('get_total_op');

                Route::get('/search', 'PDL\ObjekPajakPDLController@search')->name('search');
                Route::post('/query_search', 'PDL\ObjekPajakPDLController@query_search')->name('query_search');
                Route::get('/detail_daftar_tutup_op/{kategori}/{bulan}/{tahun}', 'PDL\ObjekPajakPDLController@detail_daftar_tutup_op')->name('detail_daftar_tutup_op');
                Route::get('/datatable_detail_daftar_tutup_op', 'PDL\ObjekPajakPDLController@datatable_detail_daftar_tutup_op')->name('datatable_detail_daftar_tutup_op');
            });
        });
    });

    Route::group(['prefix' => 'bphtb'], function () {
        Route::name('bphtb.')->group(function () {

            Route::prefix('penerimaan')->name('penerimaan.')->group(function () {
                Route::get('/', 'BPHTB\PenerimaanBPHTBController@index')->name('index');
                Route::get('/penerimaan_perbulan', 'BPHTB\PenerimaanBPHTBController@penerimaan_perbulan')->name('penerimaan_perbulan');
                Route::get('/penerimaan_akumulasi', 'BPHTB\PenerimaanBPHTBController@penerimaan_akumulasi')->name('penerimaan_akumulasi');
                Route::get('/penerimaan_harian', 'BPHTB\PenerimaanBPHTBController@penerimaan_harian')->name('penerimaan_harian');
                Route::get('/datatable_penerimaan_notaris', 'BPHTB\PenerimaanBPHTBController@datatable_penerimaan_notaris')->name('datatable_penerimaan_notaris');


                Route::get('datatable_detail_penerimaan_perbulan', 'BPHTB\PenerimaanBPHTBController@datatable_detail_penerimaan_perbulan')->name('datatable_detail_penerimaan_perbulan');
                Route::get('detail_penerimaan_perbulan/{tahun}/{bulan}', 'BPHTB\PenerimaanBPHTBController@detail_penerimaan_perbulan')->name('detail_penerimaan_perbulan');

                Route::get('datatable_detail_penerimaan_harian', 'BPHTB\PenerimaanBPHTBController@datatable_detail_penerimaan_harian')->name('datatable_detail_penerimaan_harian');
                Route::get('detail_penerimaan_harian/{tanggal}', 'BPHTB\PenerimaanBPHTBController@detail_penerimaan_harian')->name('detail_penerimaan_harian');
            });

            Route::prefix('ketetapan')->name('ketetapan.')->group(function () {
                Route::get('/', 'BPHTB\KetetapanBPHTBController@index')->name('index');
                Route::get('/perolehan', 'BPHTB\KetetapanBPHTBController@ketetapanPerolehan')->name('perolehan');
                Route::get('/peruntukan', 'BPHTB\KetetapanBPHTBController@ketetapanPeruntukan')->name('peruntukan');
                Route::get('/validasi', 'BPHTB\KetetapanBPHTBController@ketetapanValidasi')->name('validasi');
                Route::get('/nihilBayar', 'BPHTB\KetetapanBPHTBController@ketetapanNihilBayar')->name('nihil.bayar');
                Route::get('/datatable/pelaporan/ppat', 'BPHTB\KetetapanBPHTBController@datatablePelaporanPpat')->name('datatable.pelaporan.ppat');


                Route::get('datatable_detail_ketetapan_perolehan', 'BPHTB\KetetapanBPHTBController@datatable_detail_ketetapan_perolehan')->name('datatable_detail_ketetapan_perolehan');
                Route::get('detail_ketetapan_perolehan/{tahun}/{status}/{bulan}', 'BPHTB\KetetapanBPHTBController@detail_ketetapan_perolehan')->name('detail_ketetapan_perolehan');

                Route::get('datatable_detail_ketetapan_peruntukan', 'BPHTB\KetetapanBPHTBController@datatable_detail_ketetapan_peruntukan')->name('datatable_detail_ketetapan_peruntukan');
                Route::get('detail_ketetapan_peruntukan/{tahun}/{status}/{bulan}', 'BPHTB\KetetapanBPHTBController@detail_ketetapan_peruntukan')->name('detail_ketetapan_peruntukan');

                Route::get('datatable_detail_ketetapan_validasi', 'BPHTB\KetetapanBPHTBController@datatable_detail_ketetapan_validasi')->name('datatable_detail_ketetapan_validasi');
                Route::get('detail_ketetapan_validasi/{tahun}/{status}/{bulan}', 'BPHTB\KetetapanBPHTBController@detail_ketetapan_validasi')->name('detail_ketetapan_validasi');

                Route::get('datatable_detail_ketetapan_nihil_bayar', 'BPHTB\KetetapanBPHTBController@datatable_detail_ketetapan_nihil_bayar')->name('datatable_detail_ketetapan_nihil_bayar');
                Route::get('detail_ketetapan_nihil_bayar/{tahun}/{status}/{bulan}', 'BPHTB\KetetapanBPHTBController@detail_ketetapan_nihil_bayar')->name('detail_ketetapan_nihil_bayar');
            });

            Route::prefix('op')->name('op.')->group(function () {
                Route::get('/', 'BPHTB\ObjekPajakBPHTBController@index')->name('index');
                Route::get('/get_total_nop', 'BPHTB\ObjekPajakBPHTBController@get_total_nop')->name('get_total_nop');
                Route::get('/get_total_notaris', 'BPHTB\ObjekPajakBPHTBController@get_total_notaris')->name('get_total_notaris');
                Route::get('/datatable_kontribusi_op', 'BPHTB\ObjekPajakBPHTBController@datatable_kontribusi_op')->name('datatable_kontribusi_op');
            });
        });
    });

    Route::group(['prefix' => 'retribusi'], function () {
        Route::name('retribusi.')->group(function () {

            Route::prefix('kelola-opd')->name('kelola-opd.')->group(function () {
                Route::get('/', 'AdminRetribusi\OpdController@index')->name('index');
                Route::post('/datatables', 'AdminRetribusi\OpdController@datatables')->name('datatables');
                Route::get('/create', 'AdminRetribusi\OpdController@create')->name('create');
                Route::post('/store', 'AdminRetribusi\OpdController@store')->name('store');
                Route::post('/show', 'AdminRetribusi\OpdController@show')->name('show');
                Route::post('/edit', 'AdminRetribusi\OpdController@edit')->name('edit');
                Route::post('/update', 'AdminRetribusi\OpdController@update')->name('update');
                Route::post('/banned', 'AdminRetribusi\OpdController@banned')->name('banned');
                Route::post('/unbanned', 'AdminRetribusi\OpdController@unbanned')->name('unbanned');

                Route::get('detail/{id_opd}', 'AdminRetribusi\OpdController@detail')->name('detail');
                Route::post('get_data_detail', 'AdminRetribusi\OpdController@get_data_detail')->name('get_data_detail');
                Route::post('detail/hapus', 'AdminRetribusi\OpdController@hapus_detail')->name('hapus_detail');
                Route::post('detail/get_retribusi', 'AdminRetribusi\OpdController@get_retribusi');
                Route::get('detail/get_retribusi/{id}', 'AdminRetribusi\OpdController@get_retribusi');
                Route::post('detail/simpan', 'AdminRetribusi\OpdController@simpan_retribusi')->name('simpan_retribusi');
            });
            Route::prefix('kelola-retribusi')->name('kelola-retribusi.')->group(function () {
                Route::get('/', 'AdminRetribusi\RetribusiController@index')->name('index');
                Route::get('/get_data', 'AdminRetribusi\RetribusiController@get_data')->name('get_data');
                Route::post('/simpan', 'AdminRetribusi\RetribusiController@store')->name('simpan');
                Route::post('/hapus', 'AdminRetribusi\RetribusiController@hapus')->name('hapus');
            });

            Route::prefix('penerimaan')->name('penerimaan.')->group(function () {
                Route::get('/', 'Retribusi\PenerimaanRetribusiController@index')->name('index');
                Route::get('/penerimaan_perbulan', 'Retribusi\PenerimaanRetribusiController@penerimaan_perbulan')->name('penerimaan_perbulan');
                Route::get('/penerimaan_akumulasi', 'Retribusi\PenerimaanRetribusiController@penerimaan_akumulasi')->name('penerimaan_akumulasi');
                Route::get('/penerimaan_harian', 'Retribusi\PenerimaanRetribusiController@penerimaan_harian')->name('penerimaan_harian');
            });

            Route::prefix('op')->name('op.')->group(function () {
                Route::get('/', 'Retribusi\ObjekRetribusiController@index')->name('index');
                Route::get('/datatable_kontribusi_op', 'Retribusi\ObjekRetribusiController@datatable_kontribusi_op')->name('datatable_kontribusi_op');
            });
            // Route::prefix('input-opd')->name('input-opd.')->group(function () {
            //     Route::get('/', 'Retribusi\RetribusiOPDController@index')->name('index');
            //     Route::get('/datatable_kontribusi_op', 'Retribusi\RetribusiOPDController@datatable_kontribusi_op')->name('datatable_kontribusi_op');
            // });

            Route::prefix('opd')->name('opd.')->group(function () {
                Route::get('/', 'Retribusi\RetribusiOPDController@index')->name('index');
                Route::get('/datatable_kontribusi_op', 'Retribusi\RetribusiOPDController@datatable_kontribusi_op')->name('datatable_kontribusi_op');
                Route::get('/form_opd', 'Retribusi\RetribusiOPDController@form_opd')->name('form_opd');
                Route::post('/form_opd', 'Retribusi\RetribusiOPDController@form_opd_form')->name('form_opd_form');
                Route::get('/form_opd/{id}/edit', 'Retribusi\RetribusiOPDController@form_opd_form_edit')->name('form_opd_form_edit');
                Route::put('/form_opd/{id}', 'Retribusi\RetribusiOPDController@form_opd_form_update')->name('form_opd_form_update');
                Route::delete('/form_opd/{id}', 'Retribusi\RetribusiOPDController@form_opd_form_delete')->name('form_opd_form_delete');
            });
            Route::prefix('realisasi')->name('realisasi.')->group(function () {
                Route::get('/', 'Retribusi\RetribusiOPDController@realisasi_index')->name('realisasi_index');
                Route::get('/datatable_target_realisasi_opd', 'Retribusi\RetribusiOPDController@datatable_target_realisasi_opd')->name('datatable_target_realisasi_opd');
                Route::get('/detail_target_realisasi/{id}', 'Retribusi\RetribusiOPDController@detail_target_realisasi')->name('detail_target_realisasi');
                Route::get('/datatable_detail_target_realisasi', 'Retribusi\RetribusiOPDController@datatable_detail_target_realisasi')->name('datatable_detail_target_realisasi');
                Route::post('/update_target_realisasi', 'Retribusi\RetribusiOPDController@update_target_realisasi')->name('update_target_realisasi');
            });
        });
    });

    Route::group(['prefix' => 'log'], function () {
        Route::name('log.')->group(function () {

            Route::get('/', 'LogScheduler\LogSchedulerController@index')->name('index');
            Route::get('/datatable_log_scheduler', 'LogScheduler\LogSchedulerController@datatable_log_scheduler')->name('datatable_log_scheduler');
        });
    });
    Route::group(['prefix' => 'pad'], function () {
        Route::name('pad.')->group(function () {

            Route::get('/', 'PAD\PADController@index')->name('index');
            Route::get('/datatables', 'PAD\PADController@datatables')->name('datatables');
            Route::get('/target_realisasi_pajak', 'PAD\PADController@target_realisasi_pajak')->name('target_realisasi_pajak');
            Route::get('/get_detail', 'PAD\PADController@get_detail')->name('get_detail');
            Route::get('/target_realisasi_retribusi', 'PAD\PADController@target_realisasi_retribusi')->name('target_realisasi_retribusi');
            Route::get('/target_realisasi_pad', 'PAD\PADController@target_realisasi_pad')->name('target_realisasi_pad');
            Route::get('/komposisi_pad', 'PAD\PADController@komposisi_pad')->name('komposisi_pad');
            Route::get('/komposisi_pajak', 'PAD\PADController@komposisi_pajak')->name('komposisi_pajak');
            Route::get('/trend_target_realisasi', 'PAD\PADController@trend_target_realisasi')->name('trend_target_realisasi');
            Route::get('/datatable_target_realisasi', 'PAD\PADController@datatable_target_realisasi')->name('datatable_target_realisasi');
            Route::get('/datatable_target_pajak', 'PAD\PADController@datatable_target_pajak')->name('datatable_target_pajak');
            Route::get('/show_qty_target_pajak', 'PAD\PADController@show_qty_target_pajak')->name('show_qty_target_pajak');
            Route::get('/get_akumulasi_pajak_pad', 'PAD\PADController@get_akumulasi_pajak_pad')->name('get_akumulasi_pajak_pad');

            Route::get('/datatable_target_opd', 'PAD\PADController@datatable_target_opd')->name('datatable_target_opd');
            Route::get('target_opd/detail_target_opd/{tahun}/{id_retribusi}', 'PAD\PADController@detail_target_opd')->name('detail_target_opd');
            Route::get('datatable_detail_target_opd', 'PAD\PADController@datatable_detail_target_opd')->name('datatable_detail_target_opd');
            Route::get('datatable_detail_target_opd_bulan', 'PAD\PADController@datatable_detail_target_opd_bulan')->name('datatable_detail_target_opd_bulan');
            Route::get('/penerimaan_peropd', 'PAD\PADController@penerimaan_peropd')->name('penerimaan_peropd');

            Route::get('/datatable_laporan_realisasi_retribusi_daerah', 'PAD\PADController@datatable_laporan_realisasi_retribusi_daerah')->name('datatable_laporan_realisasi_retribusi_daerah');
        });
    });

    Route::group(['prefix' => 'target_pajak'], function () {
        Route::name('target_pajak.')->group(function () {
            Route::get('/', 'Master\TargetPajakController@index')->name('import-target_pajak');
            Route::get('/datatable_target_pajak', 'Master\TargetPajakController@datatable_target_pajak')->name('datatable_target_pajak');

            Route::post('/import_target_pajak', 'Data\ImportController@import_target_pajak')->name('import_target_pajak');
        });
    });

    Route::group(['prefix' => 'data'], function () {
        Route::name('data.')->group(function () {

            Route::post('/getdata_target_realisasi', 'Data\GetDataController@getdata_target_realisasi')->name('getdata_target_realisasi');
            Route::post('/getdata_detail_objek_pajak', 'Data\GetDataController@getdata_detail_objek_pajak')->name('getdata_detail_objek_pajak');
            Route::post('/getdata_detail_ketetapan', 'Data\GetDataController@getdata_detail_ketetapan')->name('getdata_detail_ketetapan');
            Route::post('/getdata_detail_pelaporan', 'Data\GetDataController@getdata_detail_pelaporan')->name('getdata_detail_pelaporan');
            Route::post('/getdata_detail_tunggakan', 'Data\GetDataController@getdata_detail_tunggakan')->name('getdata_detail_tunggakan');
            Route::post('/getdata_jam_tempat_bayar', 'Data\GetDataController@getdata_jam_tempat_bayar')->name('getdata_jam_tempat_bayar');
            Route::post('/getdata_keberatan_bphtb', 'Data\GetDataController@getdata_keberatan_bphtb')->name('getdata_keberatan_bphtb');
            Route::post('/getdata_kepatuhan_objek', 'Data\GetDataController@getdata_kepatuhan_objek')->name('getdata_kepatuhan_objek');
            Route::post('/getdata_kontribusi', 'Data\GetDataController@getdata_kontribusi')->name('getdata_kontribusi');
            Route::post('/getdata_objek_pajak', 'Data\GetDataController@getdata_objek_pajak')->name('getdata_objek_pajak');
            Route::post('/getdata_objek_pajak_wilayah', 'Data\GetDataController@getdata_objek_pajak_wilayah')->name('getdata_objek_pajak_wilayah');
            Route::post('/getdata_pelaporan', 'Data\GetDataController@getdata_pelaporan')->name('getdata_pelaporan');
            Route::post('/getdata_pelaporan_ppat_bphtb', 'Data\GetDataController@getdata_pelaporan_ppat_bphtb')->name('getdata_pelaporan_ppat_bphtb');
            Route::post('/getdata_pembayaran_objek', 'Data\GetDataController@getdata_pembayaran_objek')->name('getdata_pembayaran_objek');
            Route::post('/getdata_penerimaan_bulanan', 'Data\GetDataController@getdata_penerimaan_bulanan')->name('getdata_penerimaan_bulanan');
            Route::post('/getdata_penerimaan_harian', 'Data\GetDataController@getdata_penerimaan_harian')->name('getdata_penerimaan_harian');
            Route::post('/getdata_penerimaan_notaris', 'Data\GetDataController@getdata_penerimaan_notaris')->name('getdata_penerimaan_notaris');
            Route::post('/getdata_penerimaan_tahun_sppt', 'Data\GetDataController@getdata_penerimaan_tahun_sppt')->name('getdata_penerimaan_tahun_sppt');
            Route::post('/getdata_penerimaan_tempat_bayar', 'Data\GetDataController@getdata_penerimaan_tempat_bayar')->name('getdata_penerimaan_tempat_bayar');
            Route::post('/getdata_rekap_ketetapan', 'Data\GetDataController@getdata_rekap_ketetapan')->name('getdata_rekap_ketetapan');
            Route::post('/getdata_rekap_ketetapan_bphtb_nihil_bayar', 'Data\GetDataController@getdata_rekap_ketetapan_bphtb_nihil_bayar')->name('getdata_rekap_ketetapan_bphtb_nihil_bayar');
            Route::post('/getdata_rekap_ketetapan_bphtb_validasi', 'Data\GetDataController@getdata_rekap_ketetapan_bphtb_validasi')->name('getdata_rekap_ketetapan_bphtb_validasi');
            Route::post('/getdata_rekap_ketetapan_perolehan_bphtb', 'Data\GetDataController@getdata_rekap_ketetapan_perolehan_bphtb')->name('getdata_rekap_ketetapan_perolehan_bphtb');
            Route::post('/getdata_rekap_ketetapan_peruntukan_bphtb', 'Data\GetDataController@getdata_rekap_ketetapan_peruntukan_bphtb')->name('getdata_rekap_ketetapan_peruntukan_bphtb');
            Route::post('/getdata_rekap_tunggakan', 'Data\GetDataController@getdata_rekap_tunggakan')->name('getdata_rekap_tunggakan');
            Route::post('/getdata_tunggakan', 'Data\GetDataController@getdata_tunggakan')->name('getdata_tunggakan');
            Route::post('/getdata_tunggakan_buku', 'Data\GetDataController@getdata_tunggakan_buku')->name('getdata_tunggakan_buku');
            Route::post('/getdata_tunggakan_detail', 'Data\GetDataController@getdata_tunggakan_detail')->name('getdata_tunggakan_detail');
            Route::post('/getdata_tunggakan_level', 'Data\GetDataController@getdata_tunggakan_level')->name('getdata_tunggakan_level');
            Route::post('/getdata_tunggakan_level_detail', 'Data\GetDataController@getdata_tunggakan_level_detail')->name('getdata_tunggakan_level_detail');


            Route::post('/import_target_realisasi', 'Data\ImportController@import_target_realisasi')->name('import_target_realisasi');
            Route::post('/import_penerimaan_bulanan', 'Data\ImportController@import_penerimaan_bulanan')->name('import_penerimaan_bulanan');
            Route::post('/import_penerimaan_harian', 'Data\ImportController@import_penerimaan_harian')->name('import_penerimaan_harian');
            Route::post('/import_penerimaan_tahun_sppt', 'Data\ImportController@import_penerimaan_tahun_sppt')->name('import_penerimaan_tahun_sppt');
            Route::post('/import_tunggakan', 'Data\ImportController@import_tunggakan')->name('import_tunggakan');
            Route::post('/import_tunggakan_detail', 'Data\ImportController@import_tunggakan_detail')->name('import_tunggakan_detail');
            Route::post('/import_tunggakan_level', 'Data\ImportController@import_tunggakan_level')->name('import_tunggakan_level');
            Route::post('/import_tunggakan_level_detail', 'Data\ImportController@import_tunggakan_level_detail')->name('import_tunggakan_level_detail');
            Route::post('/import_penerimaan_tempat_bayar', 'Data\ImportController@import_penerimaan_tempat_bayar')->name('import_penerimaan_tempat_bayar');
            Route::post('/import_proporsi_tempat_bayar', 'Data\ImportController@import_proporsi_tempat_bayar')->name('import_proporsi_tempat_bayar');
            Route::post('/import_jam_tempat_bayar', 'Data\ImportController@import_jam_tempat_bayar')->name('import_jam_tempat_bayar');
            Route::post('/import_kepatuhan_objek', 'Data\ImportController@import_kepatuhan_objek')->name('import_kepatuhan_objek');
            Route::post('/import_pembayaran_objek', 'Data\ImportController@import_pembayaran_objek')->name('import_pembayaran_objek');
            Route::post('/import_objek_pajak_wilayah', 'Data\ImportController@import_objek_pajak_wilayah')->name('import_objek_pajak_wilayah');
            Route::post('/import_rekap_tunggakan', 'Data\ImportController@import_rekap_tunggakan')->name('import_rekap_tunggakan');
            Route::post('/import_detail_tunggakan', 'Data\ImportController@import_detail_tunggakan')->name('import_detail_tunggakan');
            Route::post('/import_pelaporan', 'Data\ImportController@import_pelaporan')->name('import_pelaporan');
            Route::post('/import_detail_pelaporan', 'Data\ImportController@import_detail_pelaporan')->name('import_detail_pelaporan');
            Route::post('/import_kontribusi', 'Data\ImportController@import_kontribusi')->name('import_kontribusi');
            Route::post('/import_detail_objek_pajak', 'Data\ImportController@import_detail_objek_pajak')->name('import_detail_objek_pajak');
            Route::post('/import_penerimaan_notaris', 'Data\ImportController@import_penerimaan_notaris')->name('import_penerimaan_notaris');
            Route::post('/import_keberatan_bphtb', 'Data\ImportController@import_keberatan_bphtb')->name('import_keberatan_bphtb');
            Route::post('/import_rekap_ketetapan', 'Data\ImportController@import_rekap_ketetapan')->name('import_rekap_ketetapan');
            Route::post('/import_detail_ketetapan', 'Data\ImportController@import_detail_ketetapan')->name('import_detail_ketetapan');
            Route::post('/import_objek_pajak', 'Data\ImportController@import_objek_pajak')->name('import_objek_pajak');
            Route::post('/import_tunggakan_buku', 'Data\ImportController@import_tunggakan_buku')->name('import_tunggakan_buku');
            Route::post('/import_pelaporan_ppat_bphtb', 'Data\ImportController@import_pelaporan_ppat_bphtb')->name('import_pelaporan_ppat_bphtb');
            Route::post('/import_rekap_ketetapan_bphtb_nihil_bayar', 'Data\ImportController@import_rekap_ketetapan_bphtb_nihil_bayar')->name('import_rekap_ketetapan_bphtb_nihil_bayar');
            Route::post('/import_rekap_ketetapan_bphtb_validasi', 'Data\ImportController@import_rekap_ketetapan_bphtb_validasi')->name('import_rekap_ketetapan_bphtb_validasi');
            Route::post('/import_rekap_ketetapan_perolehan_bpthb', 'Data\ImportController@import_rekap_ketetapan_perolehan_bpthb')->name('import_rekap_ketetapan_perolehan_bpthb');
            Route::post('/import_rekap_ketetapan_peruntukan_bphtb', 'Data\ImportController@import_rekap_ketetapan_peruntukan_bphtb')->name('import_rekap_ketetapan_peruntukan_bphtb');

            // Route::middleware(['cekgroup:superadmin'])->group(function () {
            // });
            Route::get('/', 'Data\DataController@index')->name('index');
            Route::get('/target_realisasi_pad', 'Data\DataController@target_realisasi_pad')->name('target_realisasi_pad');
            Route::get('/target_realisasi_pajak', 'Data\DataController@target_realisasi_pajak')->name('target_realisasi_pajak');
            Route::get('/target_realisasi_retribusi', 'Data\DataController@target_realisasi_retribusi')->name('target_realisasi_retribusi');
            Route::get('/komposisi_pad', 'Data\DataController@komposisi_pad')->name('komposisi_pad');
            Route::get('/penerimaan_per_bulan', 'Data\DataController@penerimaan_per_bulan')->name('penerimaan_per_bulan');
            Route::get('/penerimaan_akumulasi', 'Data\DataController@penerimaan_akumulasi')->name('penerimaan_akumulasi');
            Route::get('/penerimaan_harian', 'Data\DataController@penerimaan_harian')->name('penerimaan_harian');
            Route::get('/penerimaan_tahun_sppt', 'Data\DataController@penerimaan_tahun_sppt')->name('penerimaan_tahun_sppt');
            Route::get('/tunggakan_nop', 'Data\DataController@tunggakan_nop')->name('tunggakan_nop');
            Route::get('/tunggakan_rp', 'Data\DataController@tunggakan_rp')->name('tunggakan_rp');
            Route::get('/tunggakan_detail', 'Data\DataController@tunggakan_detail')->name('tunggakan_detail');
            Route::get('/tunggakan_level', 'Data\DataController@tunggakan_level')->name('tunggakan_level');
            Route::get('/tunggakan_level_detail', 'Data\DataController@tunggakan_level_detail')->name('tunggakan_level_detail');
            Route::get('/pembayaran_tunggakan', 'Data\DataController@pembayaran_tunggakan')->name('pembayaran_tunggakan');
            Route::get('/penerimaan_tempat_bayar', 'Data\DataController@penerimaan_tempat_bayar')->name('penerimaan_tempat_bayar');
            Route::get('/proporsi_tempat_bayar', 'Data\DataController@proporsi_tempat_bayar')->name('proporsi_tempat_bayar');
            Route::get('/jam_tempat_bayar', 'Data\DataController@jam_tempat_bayar')->name('jam_tempat_bayar');
            Route::get('/perbandingan_tempat_bayar', 'Data\DataController@perbandingan_tempat_bayar')->name('perbandingan_tempat_bayar');
            Route::get('/kepatuhan_objek', 'Data\DataController@kepatuhan_objek')->name('kepatuhan_objek');
            Route::get('/pembayaran_awal', 'Data\DataController@pembayaran_awal')->name('pembayaran_awal');
            Route::get('/pembayaran_tinggi', 'Data\DataController@pembayaran_tinggi')->name('komposisi_pad');
            Route::get('/objek_pajak_wilayah', 'Data\DataController@objek_pajak_wilayah')->name('objek_pajak_wilayah');
            Route::get('/rekap_tunggakan', 'Data\DataController@rekap_tunggakan')->name('rekap_tunggakan');
            Route::get('/detail_tunggakan', 'Data\DataController@detail_tunggakan')->name('detail_tunggakan');
            Route::get('/pelaporan', 'Data\DataController@pelaporan')->name('pelaporan');
            Route::get('/detail_pelaporan', 'Data\DataController@detail_pelaporan')->name('detail_pelaporan');
            Route::get('/objek_pajak_belum_lapor', 'Data\DataController@objek_pajak_belum_lapor')->name('objek_pajak_belum_lapor');
            Route::get('/detail_objek_pajak_belum_lapor', 'Data\DataController@detail_objek_pajak_belum_lapor')->name('detail_objek_pajak_belum_lapor');
            Route::get('/kontribusi', 'Data\DataController@kontribusi')->name('kontribusi');
            Route::get('/jumlah_objek_pajak', 'Data\DataController@jumlah_objek_pajak')->name('jumlah_objek_pajak');
            Route::get('/jumlah_objek_retribusi', 'Data\DataController@jumlah_objek_retribusi')->name('jumlah_objek_retribusi');
            Route::get('/objek_pajak_aktif', 'Data\DataController@objek_pajak_aktif')->name('objek_pajak_aktif');
            Route::get('/daftar_tutup_objek_pajak', 'Data\DataController@daftar_tutup_objek_pajak')->name('daftar_tutup_objek_pajak');
            Route::get('/penerimaan_notaris', 'Data\DataController@penerimaan_notaris')->name('penerimaan_notaris');
            Route::get('/keberatan_bphtb', 'Data\DataController@keberatan_bphtb')->name('keberatan_bphtb');
            Route::get('/rekap_ketetapan', 'Data\DataController@rekap_ketetapan')->name('rekap_ketetapan');
            Route::get('/detail_ketetapan', 'Data\DataController@detail_ketetapan')->name('detail_ketetapan');
            Route::get('/subjek_pajak', 'Data\DataController@subjek_pajak')->name('subjek_pajak');
            Route::get('/detail_objek_pajak', 'Data\DataController@detail_objek_pajak')->name('detail_objek_pajak');
            Route::get('/tunggakan_buku', 'Data\DataController@tunggakan_buku')->name('tunggakan_buku');
            Route::get('/pelaporan_ppat_bphtb', 'Data\DataController@pelaporan_ppat_bphtb')->name('pelaporan_ppat_bphtb');
            Route::get('/rekap_ketetapan_bphtb_nihil_bayar', 'Data\DataController@rekap_ketetapan_bphtb_nihil_bayar')->name('rekap_ketetapan_bphtb_nihil_bayar');
            Route::get('/rekap_ketetapan_bphtb_validasi', 'Data\DataController@rekap_ketetapan_bphtb_validasi')->name('rekap_ketetapan_bphtb_validasi');
            Route::get('/rekap_ketetapan_perolehan_bpthb', 'Data\DataController@rekap_ketetapan_perolehan_bpthb')->name('rekap_ketetapan_perolehan_bpthb');
            Route::get('/rekap_ketetapan_peruntukan_bphtb', 'Data\DataController@rekap_ketetapan_peruntukan_bphtb')->name('rekap_ketetapan_peruntukan_bphtb');

            Route::get('/datatables', 'Data\DataController@datatables')->name('datatables');
            Route::get('/datatable_target_realisasi_pad', 'Data\DataController@datatable_target_realisasi_pad')->name('datatable_target_realisasi_pad');
            Route::get('/datatable_target_realisasi_pajak', 'Data\DataController@datatable_target_realisasi_pajak')->name('datatable_target_realisasi_pajak');
            Route::get('/datatable_target_realisasi_retribusi', 'Data\DataController@datatable_target_realisasi_retribusi')->name('datatable_target_realisasi_retribusi');
            Route::get('/datatable_komposisi_pad', 'Data\DataController@datatable_komposisi_pad')->name('datatable_komposisi_pad');
            Route::get('/datatable_penerimaan_per_bulan', 'Data\DataController@datatable_penerimaan_per_bulan')->name('datatable_penerimaan_per_bulan');
            Route::get('/datatable_penerimaan_akumulasi', 'Data\DataController@datatable_penerimaan_akumulasi')->name('datatable_penerimaan_akumulasi');
            Route::get('/datatable_penerimaan_harian', 'Data\DataController@datatable_penerimaan_harian')->name('datatable_penerimaan_harian');
            Route::get('/datatable_penerimaan_tahun_sppt', 'Data\DataController@datatable_penerimaan_tahun_sppt')->name('datatable_penerimaan_tahun_sppt');
            Route::get('/datatable_tunggakan_nop', 'Data\DataController@datatable_tunggakan_nop')->name('datatable_tunggakan_nop');
            Route::get('/datatable_tunggakan_rp', 'Data\DataController@datatable_tunggakan_rp')->name('datatable_tunggakan_rp');
            Route::get('/datatable_tunggakan_detail', 'Data\DataController@datatable_tunggakan_detail')->name('datatable_tunggakan_detail');
            Route::get('/datatable_tunggakan_level', 'Data\DataController@datatable_tunggakan_level')->name('datatable_tunggakan_level');
            Route::get('/datatable_tunggakan_level_detail', 'Data\DataController@datatable_tunggakan_level_detail')->name('datatable_tunggakan_level_detail');
            Route::get('/datatable_pembayaran_tunggakan', 'Data\DataController@datatable_pembayaran_tunggakan')->name('datatable_pembayaran_tunggakan');
            Route::get('/datatable_penerimaan_tempat_bayar', 'Data\DataController@datatable_penerimaan_tempat_bayar')->name('datatable_penerimaan_tempat_bayar');
            Route::get('/datatable_proporsi_tempat_bayar', 'Data\DataController@datatable_proporsi_tempat_bayar')->name('datatable_proporsi_tempat_bayar');
            Route::get('/datatable_jam_tempat_bayar', 'Data\DataController@datatable_jam_tempat_bayar')->name('datatable_jam_tempat_bayar');
            Route::get('/datatable_perbandingan_tempat_bayar', 'Data\DataController@datatable_perbandingan_tempat_bayar')->name('datatable_perbandingan_tempat_bayar');
            Route::get('/datatable_kepatuhan_objek', 'Data\DataController@datatable_kepatuhan_objek')->name('datatable_kepatuhan_objek');
            Route::get('/datatable_pembayaran_awal', 'Data\DataController@datatable_pembayaran_awal')->name('datatable_pembayaran_awal');
            Route::get('/datatable_pembayaran_tinggi', 'Data\DataController@datatable_pembayaran_tinggi')->name('datatable_pembayaran_tinggi');
            Route::get('/datatable_objek_pajak_wilayah', 'Data\DataController@datatable_objek_pajak_wilayah')->name('datatable_objek_pajak_wilayah');
            Route::get('/datatable_rekap_tunggakan', 'Data\DataController@datatable_rekap_tunggakan')->name('datatable_rekap_tunggakan');
            Route::get('/datatable_detail_tunggakan', 'Data\DataController@datatable_detail_tunggakan')->name('datatable_detail_tunggakan');
            Route::get('/datatable_pelaporan', 'Data\DataController@datatable_pelaporan')->name('datatable_pelaporan');
            Route::get('/datatable_detail_pelaporan', 'Data\DataController@datatable_detail_pelaporan')->name('datatable_detail_pelaporan');
            Route::get('/datatable_objek_pajak_belum_lapor', 'Data\DataController@datatable_objek_pajak_belum_lapor')->name('datatable_objek_pajak_belum_lapor');
            Route::get('/datatable_detail_objek_pajak_belum_lapor', 'Data\DataController@datatable_detail_objek_pajak_belum_lapor')->name('datatable_detail_objek_pajak_belum_lapor');
            Route::get('/datatable_kontribusi', 'Data\DataController@datatable_kontribusi')->name('datatable_kontribusi');
            Route::get('/datatable_jumlah_objek_pajak', 'Data\DataController@datatable_jumlah_objek_pajak')->name('datatable_jumlah_objek_pajak');
            Route::get('/datatable_jumlah_objek_retribusi', 'Data\DataController@datatable_jumlah_objek_retribusi')->name('datatable_jumlah_objek_retribusi');
            Route::get('/datatable_objek_pajak_aktif', 'Data\DataController@datatable_objek_pajak_aktif')->name('datatable_objek_pajak_aktif');
            Route::get('/datatable_daftar_tutup_objek_pajak', 'Data\DataController@datatable_daftar_tutup_objek_pajak')->name('datatable_daftar_tutup_objek_pajak');
            Route::get('/datatable_penerimaan_notaris', 'Data\DataController@datatable_penerimaan_notaris')->name('datatable_penerimaan_notaris');
            Route::get('/datatable_keberatan_bphtb', 'Data\DataController@datatable_keberatan_bphtb')->name('datatable_keberatan_bphtb');
            Route::get('/datatable_rekap_ketetapan', 'Data\DataController@datatable_rekap_ketetapan')->name('datatable_rekap_ketetapan');
            Route::get('/datatable_detail_ketetapan', 'Data\DataController@datatable_detail_ketetapan')->name('datatable_detail_ketetapan');
            Route::get('/datatable_subjek_pajak', 'Data\DataController@datatable_subjek_pajak')->name('datatable_subjek_pajak');
            Route::get('/datatable_detail_objek_pajak', 'Data\DataController@datatable_detail_objek_pajak')->name('datatable_detail_objek_pajak');
            Route::get('/datatable_tunggakan_buku', 'Data\DataController@datatable_tunggakan_buku')->name('datatable_tunggakan_buku');
            Route::get('/datatable_rekap_ketetapan_peruntukan_bphtb', 'Data\DataController@datatable_rekap_ketetapan_peruntukan_bphtb')->name('datatable_rekap_ketetapan_peruntukan_bphtb');
            Route::get('/datatable_rekap_ketetapan_perolehan_bpthb', 'Data\DataController@datatable_rekap_ketetapan_perolehan_bpthb')->name('datatable_rekap_ketetapan_perolehan_bpthb');
            Route::get('/datatable_rekap_ketetapan_bphtb_validasi', 'Data\DataController@datatable_rekap_ketetapan_bphtb_validasi')->name('datatable_rekap_ketetapan_bphtb_validasi');
            Route::get('/datatable_rekap_ketetapan_bphtb_nihil_bayar', 'Data\DataController@datatable_rekap_ketetapan_bphtb_nihil_bayar')->name('datatable_rekap_ketetapan_bphtb_nihil_bayar');
            Route::get('/datatable_pelaporan_ppat_bphtb', 'Data\DataController@datatable_pelaporan_ppat_bphtb')->name('datatable_pelaporan_ppat_bphtb');
        });
    });
});
