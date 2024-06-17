<?php
if (!function_exists('getAllRoutes')) {
    function getAllRoutes()
    {
        // $routeLists = \Route::getRoutes();
        $routeLists = [];
        $routeLists['admin_permissions'] = [
            [
                'name' => 'category',
                'slug' => 'getcategory',
            ],[
                'name' => 'store setting',
                'slug' => 'settingstore',
            ],[
                'name' => 'categories with slots',
                'slug' => 'getcategorieswithslots',
            ],[
                'name' => 'slots',
                'slug' => 'getslots',
            ],[
                'name' => 'gateways',
                'slug' => 'getgateways',
            ],[
                'name' => 'add booking',
                'slug' => 'addbooking',
            ],[
                'name' => 'InvoiceById',
                'slug' => 'getInvoiceById',
            ],[
                'name' => 'change gateway',
                'slug' => 'changegateway',
            ],[
                'name' => 'download Pdf',
                'slug' => 'downloadPdf',
            ],[
                'name' => 'view pdf',
                'slug' => 'viewpdf',
            ],[
                'name' => 'admin signin',
                'slug' => 'admin.signin',
            ],[
                'name' => 'admin list',
                'slug' => 'admin.getadmins',
            ],[
                'name' => 'users',
                'slug' => 'admin.users',
            ],[
                'name' => 'edit user',
                'slug' => 'admin.edituser',
            ],[
                'name' => 'update user',
                'slug' => 'admin.updateuser',
            ],[
                'name' => 'delete user',
                'slug' => 'admin.deleteuser',
            ],[
                'name' => 'adduser',
                'slug' => 'admin.adduser',
            ],[
                'name' => 'admin me',
                'slug' => 'admin.me',
            ],[
                'name' => 'add admin user',
                'slug' => 'admin.addadminuser',
            ],[
                'name' => 'update admin user',
                'slug' => 'admin.updateadminuser',
            ],[
                'name' => 'delete admin user',
                'slug' => 'admin.deleteadminuser',
            ],[
                'name' => 'admin groups',
                'slug' => 'admin.getadmingroups',
            ],[
                'name' => 'admin groups list',
                'slug' => 'admin.getadmingroupslist',
            ],[
                'name' => 'admin user',
                'slug' => 'admin.getadminuser',
            ],[
                'name' => 'delete group',
                'slug' => 'admin.deletegrpoup',
            ],[
                'name' => 'add admin group',
                'slug' => 'admin.addadmingroup',
            ],[
                'name' => 'get group admin',
                'slug' => 'admin.getgroupadmin',
            ],[
                'name' => 'update admin group',
                'slug' => 'admin.updateadmingroup',
            ],[
                'name' => 'get routes',
                'slug' => 'admin.getroutes',
            ],[
                'name' => 'today payments',
                'slug' => 'admin.payments.gettodaypayments',
            ],[
                'name' => 'payments',
                'slug' => 'admin.payments.getpayments',
            ],[
                'name' => 'delete transaction',
                'slug' => 'admin.deletetransaction',
            ],[
                'name' => 'add payment gateways',
                'slug' => 'admin.addpaymentgateways',
            ],[
                'name' => 'remove payment gateways',
                'slug' => 'admin.removepaymentgateways',
            ],[
                'name' => 'payment gateways',
                'slug' => 'admin.getpaymentgateways',
            ],[
                'name' => 'getconfig',
                'slug' => 'admin.getconfig',
            ],[
                'name' => 'saveconfig',
                'slug' => 'admin.saveconfig',
            ],[
                'name' => 'admin signout',
                'slug' => 'admin.signout',
            ],[
                'name' => 'admin store update',
                'slug' => 'admin.storeupdate',
            ],[
                'name' => 'admin category add',
                'slug' => 'admin.category.add',
            ],[
                'name' => 'categories',
                'slug' => 'admin.category.getcategories',
            ],[
                'name' => 'subcategories',
                'slug' => 'admin.category.getsubcategories',
            ],[
                'name' => 'categories with slots',
                'slug' => 'admin.category.getcategorieswithslots',
            ],[
                'name' => 'category with slots',
                'slug' => 'admin.category.getcategorywithslots',
            ],[
                'name' => 'edit categories',
                'slug' => 'admin.category.editcategories',
            ],[
                'name' => 'update categories',
                'slug' => 'admin.category.updatecategories',
            ],[
                'name' => 'changetype',
                'slug' => 'admin.category.changetype',
            ],[
                'name' => 'category delete',
                'slug' => 'admin.category.delete',
            ],[
                'name' => 'all categories',
                'slug' => 'admin.category.getallcategories',
            ],[
                'name' => 'slots.add',
                'slug' => 'admin.slots.add',
            ],[
                'name' => 'all slots for category',
                'slug' => 'admin.slots.getallslotsforcategory',
            ],[
                'name' => 'slots',
                'slug' => 'admin.slots.getslots',
            ],[
                'name' => 'all slots',
                'slug' => 'admin.slots.getallslots',
            ],[
                'name' => 'edit slots',
                'slug' => 'admin.slots.editslots',
            ],[
                'name' => 'update slots',
                'slug' => 'admin.slots.updateslots',
            ],[
                'name' => 'slots delete',
                'slug' => 'admin.slots.delete',
            ],[
                'name' => 'invoices add',
                'slug' => 'admin.invoices.add',
            ],[
                'name' => 'invoices list',
                'slug' => 'admin.invoices.list',
            ],[
                'name' => 'invoices all',
                'slug' => 'admin.invoices.all',
            ],[
                'name' => 'invoices edit',
                'slug' => 'admin.invoices.edit',
            ],[
                'name' => 'invoices update',
                'slug' => 'admin.invoices.update',
            ],[
                'name' => 'invoices delete',
                'slug' => 'admin.invoices.delete',
            ],[
                'name' => 'pay invoice',
                'slug' => 'admin.invoices.payinvoice',
            ],[
                'name' => 'add payments',
                'slug' => 'admin.invoices.addpayments',
            ],[
                'name' => 'bookings',
                'slug' => 'admin.bookings.getbookings',
            ],[
                'name' => 'abandoned bookings',
                'slug' => 'admin.bookings.getabandonedbookings',
            ],[
                'name' => 'remove abandoned bookings',
                'slug' => 'admin.bookings.removeabandonedbookings',
            ],[
                'name' => 'update booking',
                'slug' => 'admin.bookings.updatebooking',
            ],[
                'name' => 'delete booking',
                'slug' => 'admin.bookings.deletebooking',
            ],[
                'name' => 'edit booking',
                'slug' => 'admin.bookings.editbooking',
            ],[
                'name' => 'cancellation requests',
                'slug' => 'admin.bookings.cancellationrequests',
            ],[
                'name' => 'cancel booking',
                'slug' => 'admin.bookings.cancelbooking',
            ],[
                'name' => 'reject cancellation',
                'slug' => 'admin.bookings.rejectcancellation',
            ],[
                'name' => 'approve booking',
                'slug' => 'admin.bookings.approvebooking',
            ],[
                'name' => 'complete booking',
                'slug' => 'admin.bookings.completebooking',
            ],[
                'name' => 'user signin',
                'slug' => 'user.signin',
            ],[
                'name' => 'user signup',
                'slug' => 'user.signup',
            ],[
                'name' => 'user me',
                'slug' => 'user.me',
            ],[
                'name' => 'user signout',
                'slug' => 'user.signout',
            ],[
                'name' => 'user bookings',
                'slug' => 'user.getbookings',
            ],[
                'name' => 'user booking details',
                'slug' => 'user.bookingdetails',
            ],[
                'name' => 'add team',
                'slug' => 'user.addteam',
            ],[
                'name' => 'update team',
                'slug' => 'user.updateteam',
            ],[
                'name' => 'delete team',
                'slug' => 'user.deleteteam',
            ],[
                'name' => 'user team',
                'slug' => 'user.getuserteam',
            ],[
                'name' => 'user paynow',
                'slug' => 'user.paynow',
            ],[
                'name' => 'user edit',
                'slug' => 'user.edit',
            ],[
                'name' => 'update user',
                'slug' => 'user.updateuser',
            ],[
                'name' => 'cancel request',
                'slug' => 'user.cancelrequest',
            ],[
                'name' => 'cancel booking',
                'slug' => 'user.cancelbooking',
            ]
        ];
        return $routeLists;
    }

    if (!function_exists('getStoreDetails')) {
        function getStoreDetails($name = null)
        {
            // $storedata = Cache::get('storedata');
            // if ($storedata) {
            //     if ($name != null) {
            //         $return = new stdClass();
            //         foreach ($storedata as $key => $store) {
            //             if ($key == $name) {
            //                 $return->value = $store;
            //                 $return->name = $key;
            //                 break;
            //             }
            //         }
            //         if (isset($return->value)) {
            //             return $return;
            //         }
            //     } else {
            //         return $storedata;
            //     }
            // }
    
            // get last cron
            if ($name !=  null) {
                $return = \App\Models\Setting::select('setting', 'value', 'id')->where('setting', $name)->first();
                return $return;
            }
            $storedata = [];
            // $storedetail = \App\Models\Setting::select('name', 'value', 'id')->get();
            // if ($storedetail) {
            //     foreach ($storedetail as $key => $value) {
            //         $storedata[$value->name] = $value->value;
            //     }
            // }
            return $storedata;
        }
    }
}
