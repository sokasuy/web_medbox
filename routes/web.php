<?php

use App\Http\Controllers\MsBarangController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('msbarang', MsBarangController::class);

// Route::get('/tes', function () {
//     return view('msbarang.index');
// });

// Route::get('/greeting', function () {
//     return 'Hello World';
// });
