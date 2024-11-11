<?php

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
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

Route::post('/', 'App\Http\Controllers\CloudServersController@index');
// Route::get('/123', function () {
//     Auth::loginUsingId(1);
// });
Route::get('/test', 'App\Http\Controllers\testController@index');
Route::fallback(function () {return response()->view('500');});

///////////////////////////////////providers////////////////////////////////////////
Route::get('CloudServersProviders','App\Http\Controllers\CloudServersProviderController@index');
Route::post('addNewProvider','App\Http\Controllers\CloudServersProviderController@addNewProvider');
Route::post('getProviderById','App\Http\Controllers\CloudServersProviderController@getProviderById');
Route::post('deleteProvider','App\Http\Controllers\CloudServersProviderController@deleteProvider');
Route::post('updateProvider','App\Http\Controllers\CloudServersProviderController@updateProvider');
Route::post('getAvailableGeos','App\Http\Controllers\CloudServersProviderController@getAvailableGeos');

///////////////////////////////////Digital Ocean////////////////////////////////////////
Route::get('CloudServers','App\Http\Controllers\CloudServersController@index');
Route::get('CreateCloudServersIndex','App\Http\Controllers\CloudServersController@createCloudServersIndex');
Route::post('createCloudServers','App\Http\Controllers\CloudServersController@createCloudServers');
Route::post('deleteServers','App\Http\Controllers\CloudServersController@deleteServers');
Route::post('initializeCloudServers','App\Http\Controllers\CloudServersController@initializeCloudServers');
Route::post('checkServer','App\Http\Controllers\CloudServersController@checkServer');
Route::post('checkServerStatus','App\Http\Controllers\CloudServersController@checkServerStatus');
Route::post('reinstallServers','App\Http\Controllers\CloudServersController@reinstallServers');

/////////////////////////////////////Linode////////////////////////////////////////
Route::get('CreateLinodeServersIndex','App\Http\Controllers\LinodeController@createLinodeIndex');
Route::post('getLinodeOptions','App\Http\Controllers\LinodeController@getLinodeOptions');
Route::post('createLinode','App\Http\Controllers\LinodeController@createLinode');
Route::post('getLinodeStatus','App\Http\Controllers\LinodeController@getLinodeStatus');
Route::post('storeLinode','App\Http\Controllers\LinodeController@storeLinode');

/////////////////////////////////////Hetzner////////////////////////////////////////
Route::get('CreateHetznerServersIndex','App\Http\Controllers\HetznerController@createHetznerIndex');
Route::post('getHetznerOptions','App\Http\Controllers\HetznerController@getHetznerOptions');
Route::post('createHetznerServer','App\Http\Controllers\HetznerController@createHetznerServer');
Route::post('getHetznerStatus','App\Http\Controllers\HetznerController@getHetznerStatus');

///////////////////////////////////////Azure////////////////////////////////////////
Route::get('CreateAzureVMsIndex','App\Http\Controllers\AzureController@CreateAzureIndex');
Route::post('getSubscriptions','App\Http\Controllers\AzureController@getSubscriptions');
Route::post('getRegions','App\Http\Controllers\AzureController@getRegions');
Route::post('getSizes','App\Http\Controllers\AzureController@getSizes');
Route::post('createVirtualMachines','App\Http\Controllers\AzureController@createVirtualMachines');
Route::post('createResourceGroup','App\Http\Controllers\AzureController@createResourceGroup');

///////////////////////////////////////Kamatera////////////////////////////////////////
Route::get('CreateKamateraServersIndex','App\Http\Controllers\KamateraController@createKamateraIndex');
Route::post('getKamateraOptions','App\Http\Controllers\KamateraController@getKamateraOptions');
Route::post('createKamatera','App\Http\Controllers\KamateraController@createKamatera');
Route::post('getKamateraStatus','App\Http\Controllers\KamateraController@getKamateraStatus');
Route::post('storeKamatera','App\Http\Controllers\KamateraController@storeKamatera');

///////////////////////////////////settings////////////////////////////////////////
Route::get('settings','App\Http\Controllers\SettingsController@index');
Route::post('update_default_password','App\Http\Controllers\SettingsController@update_default_password');
Route::post('generateKeys','App\Http\Controllers\SettingsController@generateKeys');
Route::post('updateProvidersKeys','App\Http\Controllers\SettingsController@updateProvidersKeys');

//////////////////////////////////spamhauss////////////////////////////////////////
Route::post('checkSpamhaus','App\Http\Controllers\SpamhausController@checkSpamhaus');

///////////////////////////////////logs////////////////////////////////////////
Route::group(['middleware' => ['permission:super_admin']], function () {
    Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs.index');
});

require __DIR__.'/auth.php';


/////////////////////////////////idcloudhost/////////////////////////////////////


Route::get('CreateIdCloudHostIndex','App\Http\Controllers\IdCloudHostController@createIdCloudHostIndex');
