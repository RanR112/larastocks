<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Suplayer\SuplayerController;
use App\Models\Roles;
use App\Http\Controllers\Staff\SupplierController;
use App\Http\Controllers\Staff\MaterialController;
use App\Http\Controllers\Staff\Po_NoController;
use App\Http\Controllers\Staff\UserController;
use App\Http\Controllers\Staff\ControllPoController;
use App\Http\Controllers\Staff\ActualReciveController;
use App\Http\Controllers\Staff\FrotcastController;
use App\Http\Controllers\Staff\FractionController;
use App\Http\Controllers\Staff\DelivNoteController;
use App\Http\Controllers\Staff\ForecastSuppleirController;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    if (Auth::check()) {
        $role = Roles::where('name', 'staff')->first();
        if (Auth::user()->role_id == $role->id) {
            return app(StaffController::class)->index();
        } else {
            $role = Roles::where('name', 'supplier')->first();
            if (Auth::user()->role_id == $role->id) {
                return app(SuplayerController::class)->index();
            }
        }
    }
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Staff routes
Route::middleware(['auth'])->group(function () {
    Route::get('/supplier', [SupplierController::class, 'index'])->name('staff.supplier');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('staff.supplier.store');
    Route::put('/supplier/{supplier}', [SupplierController::class, 'update'])->name('staff.supplier.update');
    Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('staff.supplier.destroy');

    Route::get('/material', [MaterialController::class, 'index'])->name('staff.material.list');
    Route::post('/material', [MaterialController::class, 'store'])->name('staff.material.store');
    Route::put('/material/{material}', [MaterialController::class, 'update'])->name('staff.material.update');
    Route::delete('/material/{material}', [MaterialController::class, 'destroy'])->name('staff.material.destroy');

    // Material Details routes
    Route::post('/material/{material}/details', [MaterialController::class, 'storeDetail'])->name('staff.material.detail.store');
    Route::put('/material/details/{materialDetail}', [MaterialController::class, 'updateDetail'])->name('staff.material.detail.update');
    Route::delete('/material/details/{materialDetail}', [MaterialController::class, 'destroyDetail'])->name('staff.material.detail.destroy');

    // PO Number routes
    Route::get('/po-no', [Po_NoController::class, 'index'])->name('staff.po-no');
    Route::post('/po-no', [Po_NoController::class, 'store'])->name('staff.po-no.store');
    Route::put('/po-no/{po_no}', [Po_NoController::class, 'update'])->name('staff.po-no.update');
    Route::delete('/po-no/{po_no}', [Po_NoController::class, 'destroy'])->name('staff.po-no.destroy');

    // User Management routes
    Route::get('/user', [UserController::class, 'index'])->name('staff.user');
    Route::post('/user', [UserController::class, 'store'])->name('staff.user.store');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('staff.user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('staff.user.destroy');

    // Tambahkan route export
    Route::get('/material/export/pdf', [MaterialController::class, 'exportPDF'])->name('staff.material.export.pdf');
    Route::get('/material/export/excel', [MaterialController::class, 'exportExcel'])->name('staff.material.export.excel');

    Route::get('/user/export/pdf', [UserController::class, 'exportPDF'])->name('staff.user.export.pdf');
    Route::get('/user/export/excel', [UserController::class, 'exportExcel'])->name('staff.user.export.excel');
    Route::get('/supplier/export/pdf', [SupplierController::class, 'exportPDF'])->name('staff.supplier.export.pdf');
    Route::get('/supplier/export/excel', [SupplierController::class, 'exportExcel'])->name('staff.supplier.export.excel');

    // Tambahkan route untuk Control PO
    Route::get('/controll-po', [ControllPoController::class, 'index'])->name('staff.controll-po');
    Route::post('/controll-po', [ControllPoController::class, 'store'])->name('staff.controll-po.store');
    Route::put('/controll-po/{controlPo}', [ControllPoController::class, 'update'])->name('staff.controll-po.update');
    Route::delete('/controll-po/{controlPo}', [ControllPoController::class, 'destroy'])->name('staff.controll-po.destroy');
    Route::get('/controll-po/export/pdf', [ControllPoController::class, 'exportPDF'])->name('staff.controll-po.export.pdf');
    Route::get('/controll-po/export/excel', [ControllPoController::class, 'exportExcel'])->name('staff.controll-po.export.excel');

    Route::get('/forecast', [FrotcastController::class, 'index'])->name('staff.forecast');
    Route::get('/forecast/export/pdf', [FrotcastController::class, 'exportPDF'])->name('staff.forecast.export.pdf');
    Route::get('/forecast/export/excel', [FrotcastController::class, 'exportExcel'])->name('staff.forecast.export.excel');

    // Fraction routes
    Route::get('/fraction', [FractionController::class, 'index'])->name('staff.fraction.index');
    Route::post('/fraction', [FractionController::class, 'store'])->name('staff.fraction.store');
    Route::put('/fraction/{fraction}', [FractionController::class, 'update'])->name('staff.fraction.update');
    Route::delete('/fraction/{fraction}', [FractionController::class, 'destroy'])->name('staff.fraction.destroy');
});
Route::get('/actual-receive', [ActualReciveController::class, 'index'])->name('staff.actual-receive');
Route::post('/actual-receive', [ActualReciveController::class, 'store'])->name('staff.actual-receive.store');
Route::put('/actual-receive/{actualReceive}', [ActualReciveController::class, 'update'])->name('staff.actual-receive.update');
Route::delete('/actual-receive/{actualReceive}', [ActualReciveController::class, 'destroy'])->name('staff.actual-receive.destroy');
Route::get('/actual-receive/export/pdf', [ActualReciveController::class, 'exportPDF'])->name('staff.actual-receive.export.pdf');
Route::get('/actual-receive/export/excel', [ActualReciveController::class, 'exportExcel'])->name('staff.actual-receive.export.excel');

// Supplier routes
Route::middleware(['auth'])->group(function () {
    Route::get('/supplier/material', [SuplayerController::class, 'material'])->name('suplayer.material');
    Route::get('/supplier/export-users', [SuplayerController::class, 'exportUsers'])->name('suplayer.export.users');

    // Supplier Download Routes
    Route::get('/supplier/deliv-note', [App\Http\Controllers\Suplayer\DelivNoteController::class, 'index'])
        ->name('suplayer.deliv-note');
    Route::get('/supplier/deliv-note/download/{controll}', [App\Http\Controllers\Suplayer\DelivNoteController::class, 'download'])
        ->name('suplayer.deliv.download');

    Route::get('/supplier/forecast', [App\Http\Controllers\Suplayer\ForecastSuppleirController::class, 'index'])
        ->name('suplayer.forecast');
    Route::get('/supplier/forecast/download/{controll}', [App\Http\Controllers\Suplayer\ForecastSuppleirController::class, 'download'])
        ->name('suplayer.forecast.download');
});

// Delivery Note Routes
Route::get('/deliv-note', [DelivNoteController::class, 'index'])->name('staff.deliv-note');
Route::post('/deliv-note', [DelivNoteController::class, 'store'])->name('deliv-note.store');
Route::delete('/deliv-note/{controll}', [DelivNoteController::class, 'destroy'])->name('deliv-note.destroy');

// Forecast Supplier Routes
Route::get('/forecast-supplier', [ForecastSuppleirController::class, 'index'])->name('staff.forecast-supplier');
Route::post('/forecast-supplier', [ForecastSuppleirController::class, 'store'])->name('forecast-supplier.store');
Route::delete('/forecast-supplier/{controll}', [ForecastSuppleirController::class, 'destroy'])->name('forecast-supplier.destroy');

require __DIR__ . '/auth.php';
