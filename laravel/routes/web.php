<?php

// use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MsBarangController;
use App\Http\Controllers\BuyingPowerChartsController;
use App\Http\Controllers\FinansialController;
use App\Http\Controllers\StokBarangController;
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


Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Route::get('/', function () {
    //     return view('dashboard');
    // })->name('welcome');
    //Route::resource('dashboard', HomeController::class);

    //DASHBOARD
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [HomeController::class, 'index'])->name('dashboard.home');
    //PAGES MSBARANG
    Route::resource('/master/msbarang', MsBarangController::class);
    Route::post('/master/msbarang/get-data', [MsBarangController::class, 'getDataMsBarang'])->name('msbarang.getdata');

    //DASHBOARD ROUTES
    Route::post('/home/refresh-purchase-chart', [HomeController::class, 'refreshPurchaseChart'])->name('home.refreshpurchasechart');
    Route::post('/home/refresh-sales-chart', [HomeController::class, 'refreshSalesChart'])->name('home.refreshsaleschart');
    Route::post('/home/refresh-profitloss-chart', [HomeController::class, 'refreshProfitLossChart'])->name('home.refreshprofitlosschart');
    Route::post('/home/refresh-bestseller-chart', [HomeController::class, 'refreshBestsellerChart'])->name('home.refreshbestsellerchart');

    //PAGES BUYING POWER CHART
    Route::get('/charts/buying-power', [BuyingPowerChartsController::class, 'index'])->name('charts.buyingpower');
    Route::post('/charts/buying-power/refresh-daily-buying-power-chart', [BuyingPowerChartsController::class, 'refreshDailyBuyingPowerChart'])->name('charts.refreshdailybuyingpowerchart');
    Route::post('/charts/buying-power/refresh-hourly-buying-power-chart', [BuyingPowerChartsController::class, 'refreshHourlyBuyingPowerChart'])->name('charts.refreshhourlybuyingpowerchart');
    Route::post('/charts/buying-power/refresh-daily-sales-chart', [BuyingPowerChartsController::class, 'refreshDailySalesChart'])->name('charts.refreshdailysaleschart');
    Route::post('/charts/buying-power/refresh-hourly-sales-chart', [BuyingPowerChartsController::class, 'refreshHourlySalesChart'])->name('charts.refreshhourlysaleschart');
    Route::post('/charts/buying-power/refresh-daily-transaction-chart', [BuyingPowerChartsController::class, 'refreshDailyTransactionChart'])->name('charts.refreshdailytransactionchart');
    Route::post('/charts/buying-power/refresh-hourly-transaction-chart', [BuyingPowerChartsController::class, 'refreshHourlyTransactionChart'])->name('charts.refreshhourlytransactionchart');

    //PAGES REPORTS HUTANG PIUTANG
    Route::get('/reports/hutang-piutang', [FinansialController::class, 'reportHutangPiutang'])->name('reports.hutangpiutang');
    Route::post('/reports/get-hutang-piutang', [FinansialController::class, 'getHutangPiutang'])->name('reports.gethutangpiutang');

    //PAGES REPORTS EXPIRY DATE
    Route::get('/reports/expiry-date', [StokBarangController::class, 'reportExpiryDate'])->name('reports.expirydate');
    Route::post('/reports/get-expiry-date', [StokBarangController::class, 'getExpiryDate'])->name('reports.getexpirydate');
});

// Route::get('/greeting', function () {
//     return 'Hello World';
// });
