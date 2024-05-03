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

Route::get('category/{id}', [CategoriesController::class, 'editcategories']);
Route::get('store', [CommonController::class, 'store']);
Route::get('categories', [CategoriesController::class, 'getcategorieswithslots']);
Route::post('slots', [SlotsController::class, 'getslots']);
Route::get('getgateways', [PaymentgatewaysController::class, 'getgateways']);
Route::post('addbooking', [CommonController::class, 'addbooking']);
Route::get('getinvoicebyid/{id}', [InvoicesController::class, 'getInvoiceById']);
Route::post('changegateway', [InvoicesController::class, 'changegateway']);

// add admin group with middleware
Route::group(['prefix' => 'admin'], function () {
    Route::post('signin', [AdminAuthController::class, 'signin']);
    Route::group(['middleware' => ['auth:sanctum', 'checkadmin', 'CheckDisable']], function () {

        Route::get('/getadmins', [App\Http\Controllers\AdminAuthController::class, 'getadmins'])->name('admin.getadmins');
        Route::get('/users', [App\Http\Controllers\UsersController::class, 'getusers'])->name('admin.users');
        Route::get('/user/edit/{id}', [App\Http\Controllers\UsersController::class, 'edituser'])->name('admin.edituser');
        Route::post('/user/update/', [App\Http\Controllers\UsersController::class, 'updateuser'])->name('admin.updateuser');
        Route::post('/user/deleteuser', [App\Http\Controllers\UsersController::class, 'deleteuser'])->name('admin.deleteuser');
        Route::post('/user/add', [App\Http\Controllers\UsersController::class, 'adduser'])->name('admin.adduser');

        //Global Routes
        Route::get('/me', [App\Http\Controllers\AdminAuthController::class, 'me'])->name('admin.me');


        Route::get('/getadmins', [App\Http\Controllers\AdminContoller::class, 'getadmins'])->name('admin.getadmins');
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

        Route::get('/gettodaypayments', [App\Http\Controllers\PaymentsController::class, 'todaytransactions'])->name('payments.gettodaypayments');
        Route::get('/getpayments', [App\Http\Controllers\PaymentsController::class, 'getpayments'])->name('payments.getpayments');
        Route::post('/deletetransaction/{id}', [App\Http\Controllers\PaymentsController::class, 'deletetransaction'])->name('admin.deletetransaction');


        // Route::post('/settings/update', [App\Http\Controllers\admin\SettingsController::class, 'update'])->name('storedetails.update');
        // //Route::post('/settings/smtp', [App\Http\Controllers\admin\SettingsController::class, 'smtp'])->name('storedetails.smtp');

        // Route::post('/settings/smtp/update', [App\Http\Controllers\admin\SettingsController::class, 'smtpupdate'])->name('storedetails.smtpupdate');
        // Route::post('/settings/sms/update', [App\Http\Controllers\admin\SettingsController::class, 'smsupdate'])->name('storedetails.smsupdate');
        // Route::post('/settings/smtp/test', [App\Http\Controllers\admin\SettingsController::class, 'testsmtp'])->name('storedetails.smtptest');

        Route::post('addpaymentgateways', [PaymentgatewaysController::class, 'addpaymentgateways']);
        Route::post('removepaymentgateways', [PaymentgatewaysController::class, 'removepaymentgateways']);
        Route::get('getpaymentgateways', [PaymentgatewaysController::class, 'getpaymentgateways']);
        Route::get('getconfig/{gateway}', [PaymentgatewaysController::class, 'getconfig']);
        Route::post('saveconfig', [PaymentgatewaysController::class, 'saveconfig']);
        Route::post('signout', [AdminAuthController::class, 'signout']);
        Route::post('settings/update', [CommonController::class, 'storeupdate']);
        Route::group(['prefix' => 'category'], function () {
            Route::post('add', [CategoriesController::class, 'add']);
            Route::get('list', [CategoriesController::class, 'getcategories']);
            Route::post('getsubcategories', [CategoriesController::class, 'getsubcategories']);
            Route::get('listwithslots', [CategoriesController::class, 'getcategorieswithslots']);
            Route::post('getwithslots', [CategoriesController::class, 'getcategorywithslots']);
            Route::get('edit/{id}', [CategoriesController::class, 'editcategories']);
            Route::post('update', [CategoriesController::class, 'updatecategories']);
            Route::post('changetype', [CategoriesController::class, 'changetype']);
            Route::post('delete', [CategoriesController::class, 'delete']);
            Route::get('all', [CategoriesController::class, 'getallcategories']);
        });
        Route::group(['prefix' => 'slots'], function () {
            Route::post('add', [SlotsController::class, 'add']);
            Route::post('list', [SlotsController::class, 'getallslotsforcategory']);
            Route::post('get', [SlotsController::class, 'getslots']);
            Route::get('all', [SlotsController::class, 'getallslots']);
            Route::get('edit/{id}', [SlotsController::class, 'editslots']);
            Route::post('update', [SlotsController::class, 'updateslots']);
            Route::post('delete', [SlotsController::class, 'delete']);
        });
        Route::group(['prefix' => 'invoices'], function () {
            Route::post('add', [InvoicesController::class, 'add']);
            Route::get('list', [InvoicesController::class, 'getallinvoices']);
            Route::get('all', [InvoicesController::class, 'getallinvoices']);
            Route::get('edit/{id}', [InvoicesController::class, 'edit']);
            Route::post('update', [InvoicesController::class, 'update']);
            Route::post('delete', [InvoicesController::class, 'delete']);
            Route::post('payinvoice', [InvoicesController::class, 'payinvoice']);
        });
        Route::group(['prefix' => 'bookings'], function () {
            Route::post('list', [CommonController::class, 'getbookings']);
            Route::post('update', [CommonController::class, 'updatebooking']);
            Route::post('delete', [CommonController::class, 'deletebooking']);
            Route::get('edit/{id}', [CommonController::class, 'editbooking']);
            Route::post('update', [CommonController::class, 'updatebooking']);
            Route::get('cancellationrequests', [CommonController::class, 'cancellationrequests']);
            Route::post('cancelbooking', [CommonController::class, 'cancelbooking']);
            Route::post('rejectcancellation', [CommonController::class, 'rejectcancellation']);
        });
    });
});

Route::group(['prefix' => 'user',], function () {
    Route::post('signin', [UserAuthController::class, 'signin']);
    Route::post('signup', [UserAuthController::class, 'signup']);
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/me', [App\Http\Controllers\UserAuthController::class, 'me'])->name('user.me');
        Route::post('signout', [UserAuthController::class, 'signout']);
        Route::post('update', [UserAuthController::class, 'update']);
        Route::post('changepassword', [UserAuthController::class, 'changepassword']);
        Route::post('getbookings', [UsersController::class, 'getbookings']);
        Route::post('addteam', [TeamsController::class, 'addteam']);
        Route::post('updateteam', [TeamsController::class, 'updateteam']);
        Route::post('deleteteam', [TeamsController::class, 'deleteteam']);
        Route::get('getuserteam', [TeamsController::class, 'getuserteam']);
        Route::post('pay', [PaymentsController::class, 'paynow']);
        Route::get('editinvoice/{id}', [InvoicesController::class, 'edit']);
        Route::post('update', [UsersController::class, 'updateuser'])->name('user.updateuser');
        Route::post('cancelrequest', [CommonController::class, 'cancelrequest']);
        Route::post('cancelbooking', [CommonController::class, 'cancelbooking']);
    });
});

// add common url for all gateway payment
Route::post('payment/{gateway}', [PaymentsController::class, 'paynow']);
Route::post('callback/{gateway}/{invoiceid}', [PaymentsController::class, 'callback']);
