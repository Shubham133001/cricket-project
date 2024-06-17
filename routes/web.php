<?php

use Illuminate\Support\Facades\Route;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

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

Route::get('/sendtestemail', function () {
    Mail::to('test32@yopmail.com')->send(new TestMail());
    return 'Test email sent!';
});
// Route::get('/admin/downloadReceipt/{id}', [App\Http\Controllers\AppointmentsController::class, 'downloadReceipt'])->name('receipt.downloadpdf');
// Route::get('/admin/invoice/downloadpdf/{id}', [App\Http\Controllers\InvoicesController::class, 'downloadpdf'])->name('invoices.downloadpdf');
// Route::get('/admin/appointment/download/{id}', [App\Http\Controllers\AppointmentsController::class, 'downloadappointment'])->name('appointment.download');
// Route::get('/admin/appointment/downloadinvoices/{id}', [App\Http\Controllers\AppointmentsController::class, 'downloadinvoices'])->name('appointment.downloadinvoices');
Route::get('/{any}', 'App\Http\Controllers\SpaController@index')->where('any', '.*');

