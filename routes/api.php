<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AdminContoller;
use App\Http\Controllers\SlotsController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\UserAuthController;
use \App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CategoriesController;
use \App\Http\Controllers\admin\DoctorController;
use \App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\InvoicesController;
use App\Models\Payment;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PaymentgatewaysController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AbandonedbookingsController;

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

Route::get('category/{id}', [CategoriesController::class, 'editcategories'])->name('getcategory');
Route::get('store', [CommonController::class, 'store'])->name('settingstore');
Route::get('categories', [CategoriesController::class, 'getcategorieswithslots'])->name('getcategorieswithslots');
Route::post('slots', [SlotsController::class, 'getslots'])->name('getslots');
Route::get('getgateways', [PaymentgatewaysController::class, 'getgateways'])->name('getgateways');
Route::post('addbooking', [CommonController::class, 'addbooking'])->name('addbooking');
Route::get('getinvoicebyid/{id}', [InvoicesController::class, 'getInvoiceById'])->name('getInvoiceById');
Route::post('changegateway', [InvoicesController::class, 'changegateway'])->name('changegateway');
Route::get('downloadpdf/{id}', [InvoicesController::class, 'downloadPdf'])->name('downloadPdf');
Route::get('viewpdf/{id}', [InvoicesController::class, 'viewpdf'])->name('viewpdf');

// add admin group with middleware
Route::group(['prefix' => 'admin'], function () {
    Route::post('signin', [AdminAuthController::class, 'signin'])->name('admin.signin');
    Route::group(['middleware' => ['auth:sanctum', 'checkadmin', 'CheckDisable']], function () {

        Route::get('/getadmins', [App\Http\Controllers\AdminAuthController::class, 'getadmins'])->name('admin.getadmins');
        Route::get('/users', [App\Http\Controllers\UsersController::class, 'getusers'])->name('admin.users');
        Route::get('/user/edit/{id}', [App\Http\Controllers\UsersController::class, 'edituser'])->name('admin.edituser');
        Route::post('/user/update/', [App\Http\Controllers\UsersController::class, 'updateuser'])->name('admin.updateuser');
        Route::post('/user/deleteuser', [App\Http\Controllers\UsersController::class, 'deleteuser'])->name('admin.deleteuser');
        Route::post('/user/add', [App\Http\Controllers\UsersController::class, 'adduser'])->name('admin.adduser');

        //Global Routes
        Route::get('/me', [App\Http\Controllers\AdminAuthController::class, 'me'])->name('admin.me');


       // Route::get('/getadmins', [App\Http\Controllers\AdminContoller::class, 'getadmins'])->name('admin.getadmins');
        Route::post('/addadminuser', [App\Http\Controllers\AdminContoller::class, 'addadminuser'])->name('admin.addadminuser');
        Route::post('/updateadminuser/{id}', [App\Http\Controllers\AdminContoller::class, 'updateadminuser'])->name('admin.updateadminuser');
        Route::get('/deleteadminuser/{id}', [App\Http\Controllers\AdminContoller::class, 'deleteadminuser'])->name('admin.deleteadminuser');


        Route::get('/getadmingroups', [App\Http\Controllers\AdminContoller::class, 'getadmingroups'])->name('admin.getadmingroups');
        Route::get('/getadmingroupslist', [App\Http\Controllers\AdminContoller::class, 'getadmingroupslist'])->name('admin.getadmingroupslist');
        Route::get('/getadminuser/{id}', [App\Http\Controllers\AdminContoller::class, 'getadminuser'])->name('admin.getadminuser');
        Route::get('/deletegrpoup/{id}', [App\Http\Controllers\AdminContoller::class, 'deletegrpoup'])->name('admin.deletegrpoup');
        Route::post('/addadmingroup', [App\Http\Controllers\AdminContoller::class, 'addadmingroup'])->name('admin.addadmingroup');
        Route::get('/getgroupadmin/{id}', [App\Http\Controllers\AdminContoller::class, 'getgroupadmin'])->name('admin.getgroupadmin');
        Route::post('/updateadmingroup/{id}', [App\Http\Controllers\AdminContoller::class, 'updateadmingroup'])->name('admin.updateadmingroup');

        Route::get('/getroutes', [App\Http\Controllers\AdminContoller::class, 'getRouters'])->name('admin.getroutes');

        Route::get('/gettodaypayments', [App\Http\Controllers\PaymentsController::class, 'todaytransactions'])->name('admin.payments.gettodaypayments');
        Route::get('/getpayments', [App\Http\Controllers\PaymentsController::class, 'getpayments'])->name('admin.payments.getpayments');
        Route::post('/deletetransaction/{id}', [App\Http\Controllers\PaymentsController::class, 'deletetransaction'])->name('admin.deletetransaction');


        // Route::post('/settings/update', [App\Http\Controllers\admin\SettingsController::class, 'update'])->name('storedetails.update');
        // //Route::post('/settings/smtp', [App\Http\Controllers\admin\SettingsController::class, 'smtp'])->name('storedetails.smtp');

        // Route::post('/settings/smtp/update', [App\Http\Controllers\admin\SettingsController::class, 'smtpupdate'])->name('storedetails.smtpupdate');
        // Route::post('/settings/sms/update', [App\Http\Controllers\admin\SettingsController::class, 'smsupdate'])->name('storedetails.smsupdate');
        // Route::post('/settings/smtp/test', [App\Http\Controllers\admin\SettingsController::class, 'testsmtp'])->name('storedetails.smtptest');

        Route::post('addpaymentgateways', [PaymentgatewaysController::class, 'addpaymentgateways'])->name('admin.addpaymentgateways');
        Route::post('removepaymentgateways', [PaymentgatewaysController::class, 'removepaymentgateways'])->name('admin.removepaymentgateways');
        Route::get('getpaymentgateways', [PaymentgatewaysController::class, 'getpaymentgateways'])->name('admin.getpaymentgateways');
        Route::get('getconfig/{gateway}', [PaymentgatewaysController::class, 'getconfig'])->name('admin.getconfig');
        Route::post('saveconfig', [PaymentgatewaysController::class, 'saveconfig'])->name('admin.saveconfig');
        Route::post('signout', [AdminAuthController::class, 'signout'])->name('admin.signout');
        Route::post('settings/update', [CommonController::class, 'storeupdate'])->name('admin.storeupdate');
        Route::group(['prefix' => 'category'], function () {
            Route::post('add', [CategoriesController::class, 'add'])->name('admin.category.add');
            Route::get('list', [CategoriesController::class, 'getcategories'])->name('admin.category.getcategories');
            Route::post('getsubcategories', [CategoriesController::class, 'getsubcategories'])->name('admin.category.getsubcategories');
            Route::get('listwithslots', [CategoriesController::class, 'getcategorieswithslots'])->name('admin.category.getcategorieswithslots');
            Route::post('getwithslots', [CategoriesController::class, 'getcategorywithslots'])->name('admin.category.getcategorywithslots');
            Route::get('edit/{id}', [CategoriesController::class, 'editcategories'])->name('admin.category.editcategories');
            Route::post('update', [CategoriesController::class, 'updatecategories'])->name('admin.category.updatecategories');
            Route::post('changetype', [CategoriesController::class, 'changetype'])->name('admin.category.changetype');
            Route::post('delete', [CategoriesController::class, 'delete'])->name('admin.category.delete');
            Route::get('all', [CategoriesController::class, 'getallcategories'])->name('admin.category.getallcategories');
        });
        Route::group(['prefix' => 'slots'], function () {
            Route::post('add', [SlotsController::class, 'add'])->name('admin.slots.add');
            Route::post('list', [SlotsController::class, 'getallslotsforcategory'])->name('admin.slots.getallslotsforcategory');
            Route::post('get', [SlotsController::class, 'getslots'])->name('admin.slots.getslots');
            Route::get('all', [SlotsController::class, 'getallslots'])->name('admin.slots.getallslots');
            Route::get('edit/{id}', [SlotsController::class, 'editslots'])->name('admin.slots.editslots');
            Route::post('update', [SlotsController::class, 'updateslots'])->name('admin.slots.updateslots');
            Route::post('delete', [SlotsController::class, 'delete'])->name('admin.slots.delete');
        });
        Route::group(['prefix' => 'invoices'], function () {
            Route::post('add', [InvoicesController::class, 'add'])->name('admin.invoices.add');
            Route::get('list', [InvoicesController::class, 'getallinvoices'])->name('admin.invoices.list');
            Route::get('all', [InvoicesController::class, 'getallinvoices'])->name('admin.invoices.all');
            Route::get('edit/{id}', [InvoicesController::class, 'edit'])->name('admin.invoices.edit');
            Route::post('update', [InvoicesController::class, 'update'])->name('admin.invoices.update');
            Route::post('delete', [InvoicesController::class, 'delete'])->name('admin.invoices.delete');
            Route::post('payinvoice', [InvoicesController::class, 'payinvoice'])->name('admin.invoices.payinvoice');
            Route::post('addpayments', [PaymentsController::class, 'addpayments'])->name('admin.invoices.addpayments');
        });
        Route::group(['prefix' => 'bookings'], function () {
            Route::post('list', [CommonController::class, 'getbookings'])->name('admin.bookings.getbookings');
            Route::post('abandonedbookings', [AbandonedbookingsController::class, 'getabandonedbookings'])->name('admin.bookings.getabandonedbookings');
            Route::post('deleteabandonedbooking', [AbandonedbookingsController::class, 'removeabandonedbookings'])->name('admin.bookings.removeabandonedbookings');
            Route::post('update', [CommonController::class, 'updatebooking'])->name('admin.bookings.updatebooking');
            Route::post('delete', [CommonController::class, 'deletebooking'])->name('admin.bookings.deletebooking');
            Route::get('edit/{id}', [CommonController::class, 'editbooking'])->name('admin.bookings.editbooking');
            //Route::post('update', [CommonController::class, 'updatebooking'])->name('bookings.');
            Route::get('cancellationrequests', [CommonController::class, 'cancellationrequests'])->name('admin.bookings.cancellationrequests');
            Route::post('cancelbooking', [CommonController::class, 'cancelbooking'])->name('admin.bookings.cancelbooking');
            Route::post('rejectcancellation', [CommonController::class, 'rejectcancellation'])->name('admin.bookings.rejectcancellation');
            Route::post('approvebooking', [CommonController::class, 'approvebooking'])->name('admin.bookings.approvebooking');
            Route::post('completebooking', [CommonController::class, 'completebooking'])->name('admin.bookings.completebooking');
        });
    });
});

Route::group(['prefix' => 'user',], function () {
    Route::post('signin', [UserAuthController::class, 'signin'])->name('user.signin');
    Route::post('signup', [UserAuthController::class, 'signup'])->name('user.signup');
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/me', [UserAuthController::class, 'me'])->name('user.me');
        Route::post('signout', [UserAuthController::class, 'signout'])->name('user.signout');
       // Route::post('update', [UserAuthController::class, 'update']);
       // Route::post('changepassword', [UserAuthController::class, 'changepassword']);
        Route::post('getbookings', [UsersController::class, 'getbookings'])->name('user.getbookings');
        Route::get('invoice', [UsersController::class, 'userInvoice'])->name('user.invoice');
        Route::get('bookingdetails', [UsersController::class, 'bookingdetails'])->name('user.bookingdetails');
        Route::post('addteam', [TeamsController::class, 'addteam'])->name('user.addteam');
        Route::post('updateteam', [TeamsController::class, 'updateteam'])->name('user.updateteam');
        Route::post('deleteteam', [TeamsController::class, 'deleteteam'])->name('user.deleteteam');
        Route::get('getuserteam', [TeamsController::class, 'getuserteam'])->name('user.getuserteam');
        Route::post('pay', [PaymentsController::class, 'paynow'])->name('user.paynow');
        Route::get('editinvoice/{id}', [InvoicesController::class, 'edit'])->name('user.edit');
        Route::post('update', [UsersController::class, 'updateuser'])->name('user.updateuser');
        Route::post('cancelrequest', [CommonController::class, 'cancelrequest'])->name('user.cancelrequest');
        Route::post('cancelbooking', [CommonController::class, 'cancelbooking'])->name('user.cancelbooking');
    });
});

// add common url for all gateway payment
Route::post('payment/{gateway}', [PaymentsController::class, 'paynow']);
Route::post('callback/{gateway}/{invoiceid}', [PaymentsController::class, 'callback']);
