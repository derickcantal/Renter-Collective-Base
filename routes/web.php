<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyAccount\MyCabinetController;
use App\Http\Controllers\MyAccount\MyRentalController;
use App\Http\Controllers\MyAccount\MyRequestController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\Reports\ReportRentalController;
use App\Http\Controllers\Reports\ReportRequestController;
use App\Http\Controllers\Reports\ReportSalesController;
use App\Http\Controllers\Dashboard\OverviewController;
use App\Http\Controllers\Dashboard\RentalController;
use App\Http\Controllers\Dashboard\RequestsController;
use App\Http\Controllers\Dashboard\SalesController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\MyAccount\MyAccountController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    Route::get('/dashboard', [DashboardController::class, 'displayall'])->name('dashboard.index');
    Route::get('/login', [DashboardController::class, 'login'])->name('dashboard.login');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'displayall'])->name('dashboard.index');
    Route::get('/dashboard/Overview', [OverviewController::class, 'index'])->name('dashboard.overview.index');
    Route::get('/dashboard/Rental', [RentalController::class, 'index'])->name('dashboard.rental.index');
    Route::get('/dashboard/Requests', [RequestsController::class, 'index'])->name('dashboard.requests.index');
    Route::get('/dashboard/Sales', [SalesController::class, 'index'])->name('dashboard.sales.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar',[AvatarController::class,'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {

    Route::get('myaccount', [MyAccountController::class, 'index'])->name('myaccount.index');

    Route::get('mycabinet/search', [MyCabinetController::class, 'search'])->name('mycabinet.search');
    Route::get('mycabinet/sales/{cabinetsales}', [MyCabinetController::class, 'cabinetsales'])->name('mycabinet.sales');
    Route::get('mycabinet/search/{cabid}/cabinet', [MyCabinetController::class, 'cabinetsearch'])->name('mycabinet.cabinetsearch');
    Route::resource('mycabinet', MyCabinetController::class);

    Route::get('myrequest/search', [MyRequestController::class, 'search'])->name('myrequest.search');
    Route::get('myrequest/select/date/range/{cabinet}/payments', [MyRequestController::class, 'create'])->name('myrequest.creates');
    Route::get('myrequest/select/date/range/{cabinet}', [MyRequestController::class, 'create_select_range'])->name('myrequest.create_select_range');
    Route::get('myrequest/{cabinet}/payments/process', [MyRequestController::class, 'store'])->name('myrequest.stores');
    Route::get('myrequest/{cabinet}/sales', [MyRequestController::class, 'sales'])->name('myrequest.sales');
    Route::resource('myrequest', MyRequestController::class);

    Route::get('myrental/search', [MyRentalController::class, 'search'])->name('myrental.search');
    Route::get('myrental/show/current/{cabid}/search', [MyRentalController::class, 'show_search'])->name('myrental.show_search');
    Route::get('myrental/show/previous/{cabid}/search', [MyRentalController::class, 'show_history_search'])->name('myrental.show_history_search');
    
    Route::get('myrental/show/{cabid}', [MyRentalController::class, 'show_history'])->name('myrental.show_history');
    Route::resource('myrental', MyRentalController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/reports/rental/payments', [ReportRentalController::class, 'index'])->name('reportrental.index');
    Route::get('/reports/request/payments', [ReportRequestController::class, 'index'])->name('reportrequest.index');
    
    Route::get('/reports/sales', [ReportSalesController::class, 'index'])->name('reportsales.index');
    Route::get('/reports/sales/search', [ReportSalesController::class, 'searchsales'])->name('reportsales.search');

   // Route::get('/reports/sales', [ReportsController::class, 'displayall'])->name('reports.index');
   // Route::get('reports/search', [ReportsController::class, 'searchhsales'])->name('reports.search');
   // Route::get('/top/salesbranch', [ReportsController::class, 'topsalesbranch'])->name('reports.topsalesbranch');
   // Route::get('/top/search/salesbranch', [ReportsController::class, 'searchtopsalesbranch'])->name('reports.searchtopsalesbranch');
});

require __DIR__.'/auth.php';
