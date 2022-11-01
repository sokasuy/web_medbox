<?php

// use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MsBarangController;
use App\Http\Controllers\BuyingPowerChartsController;
use App\Http\Controllers\FinansialController;
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
    //PAGES CHART
    Route::get('/charts/buyingpower', [BuyingPowerChartsController::class, 'index'])->name('charts.buyingpower');
    //PAGES REPORTS MSBARANG
    Route::resource('msbarang', MsBarangController::class);
    //PAGES REPORTS HUTANG PIUTANG
    Route::get('/reports/hutangpiutang', [FinansialController::class, 'indexHutangPiutang'])->name('reports.hutangpiutang');

    //DASHBOARD ROUTES
    Route::post('/home/refreshpurchasechart', [HomeController::class, 'refreshPurchaseChart'])->name('home.refreshpurchasechart');
    Route::post('/home/refreshsaleschart', [HomeController::class, 'refreshSalesChart'])->name('home.refreshsaleschart');
    Route::post('/home/refreshprofitlosschart', [HomeController::class, 'refreshProfitLossChart'])->name('home.refreshprofitlosschart');
    Route::post('/home/refreshbestsellerchart', [HomeController::class, 'refreshBestsellerChart'])->name('home.refreshbestsellerchart');

    //BUYING POWER CHART ROUTES
    Route::post('/charts/buyingpower/refreshdailybuyingpowerchart', [BuyingPowerChartsController::class, 'refreshDailyBuyingPowerChart'])->name('charts.refreshdailybuyingpowerchart');
    Route::post('/charts/buyingpower/refreshhourlybuyingpowerchart', [BuyingPowerChartsController::class, 'refreshHourlyBuyingPowerChart'])->name('charts.refreshhourlybuyingpowerchart');
    Route::post('/charts/buyingpower/refreshdailysaleschart', [BuyingPowerChartsController::class, 'refreshDailySalesChart'])->name('charts.refreshdailysaleschart');
    Route::post('/charts/buyingpower/refreshhourlysaleschart', [BuyingPowerChartsController::class, 'refreshHourlySalesChart'])->name('charts.refreshhourlysaleschart');
    Route::post('/charts/buyingpower/refreshdailytransactionchart', [BuyingPowerChartsController::class, 'refreshDailyTransactionChart'])->name('charts.refreshdailytransactionchart');
    Route::post('/charts/buyingpower/refreshhourlytransactionchart', [BuyingPowerChartsController::class, 'refreshHourlyTransactionChart'])->name('charts.refreshhourlytransactionchart');
});

// Route::get('/greeting', function () {
//     return 'Hello World';
// });
