<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// PDL QRIS
Route::post('/QrisPaymentPDL','API\QrisPDLController@QrisPayment');
// PBB QRIS
Route::post('/QrisPaymentPBB','API\QrisPBBController@QrisPayment');
// BPHTB QRIS
Route::post('/QrisPaymentBPHTB','API\QrisBPHTBController@QrisPayment');

// PDL
Route::get('/inquiry','API\PDLController@inquiry');
Route::post('/payment','API\PDLController@payment');
Route::post('/reversal','API\PDLController@reversal');

Route::post('/pbbinquiry','API\PBBController@inquiry'); //PBB
Route::post('/pbbpayment','API\PBBController@payment'); //PBB
Route::post('/pbbreversal','API\PBBController@reversal'); //PBB
Route::post('/pbbpayment_test','API\PBBController@payment_test');

//BPHTB 
Route::get('/bphtb/{id}','API\BPHTBController@show'); //percobaan only
Route::post('/getBPHTBService','API\BPHTBController@getBPHTBService'); //getBPHTBService
Route::get('/getPBBService','API\BPHTBController@getPBBService'); //getBPHTBService
Route::post('/PostDataBPN','API\BPHTBController@PostDataBPN'); //getBPHTBService
Route::get('/getInquiryBPHTB','API\BPHTBController@getInquiryBPHTB'); //getBPHTBService
Route::post('/postPaymentBPHTB','API\BPHTBController@postPaymentBPHTB');
Route::post('/postReversalBPHTB','API\BPHTBController@postReversalBPHTB');