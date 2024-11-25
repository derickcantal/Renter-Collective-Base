<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyCabinetController;
use App\Http\Controllers\MyRentalController;
use App\Http\Controllers\MyRequestController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'displayall'])->name('dashboard.index');

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'displayall'])->name('dashboard.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar',[AvatarController::class,'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {

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
    Route::get('/reports', [ReportsController::class, 'displayall'])->name('reports.index');
    Route::get('reports/search', [ReportsController::class, 'searchhsales'])->name('reports.search');
    Route::get('/top/salesbranch', [ReportsController::class, 'topsalesbranch'])->name('reports.topsalesbranch');
    Route::get('/top/search/salesbranch', [ReportsController::class, 'searchtopsalesbranch'])->name('reports.searchtopsalesbranch');
});

require __DIR__.'/auth.php';
