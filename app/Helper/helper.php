<?php
if (!function_exists('getAllRoutes')) {
    function getAllRoutes()
    {
        // $routeLists = \Route::getRoutes();
        $routeLists = [];
        $routeLists['admin_permissions'] = [
            [
                'name' => 'List Admin',
                'slug' => 'admin.getadmins',
            ],
            [
                'name' => 'Add Admin',
                'slug' => 'admin.addadminuser',
            ],
            [
                'name' => 'Update Admin',
                'slug' => 'admin.updateadminuser',
            ], [
                'name' => 'Delete Admin User',
                'slug' => 'admin.deleteadminuser',
            ], [
                'name' => 'Get Admin Groups',
                'slug' => 'admin.getadmingroups',
            ], [
                'name' => 'Get Admin Groups List',
                'slug' => 'admin.getadmingroupslist',
            ], [
                'name' => 'Get Admin Data',
                'slug' => 'admin.getadminuser',
            ], [
                'name' => 'List User',
                'slug' => 'admin.users',
            ], [
                'name' => 'Get Doctors',
                'slug' => 'admin.getdoctor',
            ], [
                'name' => 'Update Doctor',
                'slug' => 'admin.updatedoctor',
            ], [
                'name' => 'Delete Doctor',
                'slug' => 'admin.deletedoctor',
            ], [
                'name' => 'Get Specialities',
                'slug' => 'admin.getdoctorspecializationslist',
            ], [
                'name' => 'Delete Specialities',
                'slug' => 'admin.deletespecialization',
            ], [
                'name' => 'Update Specialities',
                'slug' => 'admin.updatespecialization',
            ], [
                'name' => 'Add Specialities',
                'slug' => 'admin.addspecialization',
            ], [
                'name' => 'Patient List',
                'slug' => 'admin.getpatientsforadmin',
            ], [
                'name' => 'Add Patient',
                'slug' => 'admin.addpatientbyadmin',
            ], [
                'name' => 'Update Patient',
                'slug' => 'admin.updatepatient',
            ], [
                'name' => 'Delete Patient',
                'slug' => 'admin.deletepatient',
            ], [
                'name' => 'Appointments List',
                'slug' => 'admin.getappointments',
            ],
            [
                'name' => 'Book Appointment',
                'slug' => 'admin.getanothercategory',
            ],
            [
                'name' => 'Book Walkin',
                'slug' => 'appointments.getAppointmentFee',
            ],
            [
                'name' => 'Today Appointments',
                'slug' => 'admin.gettodayappointmentsonly',
            ],
            [
                'name' => 'Checkout Appointments',
                'slug' => 'admin.getappointmentscheckoutsonly',
            ],
            [
                'name' => 'Not Confirmed',
                'slug' => 'admin.getnotconfirmedappointments',
            ],
            [
                'name' => 'Waiting Appointments',
                'slug' => 'admin.gettodaywaittingappointments',
            ],
            [
                'name' => 'Appointments History',
                'slug' => 'admin.getappointmentoldhistory',
            ],
            [
                'name' => 'Block Slots',
                'slug' => 'admin.getblockappslots',
            ],
            [
                'name' => 'Edit Block Slots',
                'slug' => 'admin.editblockappslots',
            ],
            [
                'name' => 'Services',
                'slug' => 'services.getallservices',
            ],
            [
                'name' => 'Create Service',
                'slug' => 'services.create',
            ], [
                'name' => 'Update Service',
                'slug' => 'services.update',
            ], [
                'name' => 'Delete Services',
                'slug' => 'services.delete',
            ], [
                'name' => 'Get Service By Name',
                'slug' => 'services.getservicebyname',
            ], [
                'name' => 'Invoices List',
                'slug' => 'invoices.index',
            ], [
                'name' => 'Delete Invoice',
                'slug' => 'invoices.delete',
            ], [
                'name' => 'Pay Invoice',
                'slug' => 'invoice.pay',
            ],
            [
                'name' => 'Payment Overview',
                'slug' => 'payments.getpaymentsoverview',
            ],
            [
                'name' => 'Today Appointments',
                'slug' => 'admin.gettodayappointments',
            ],
            [
                'name' => 'Transactions List',
                'slug' => 'payments.getpayments',
            ], [
                'name' => 'Transaction Overview',
                'slug' => 'payments.getpaymentsoverview',
            ], [
                'name' => 'Delete Transaction',
                'slug' => 'payments.delete',
            ], [
                'name' => 'General Settings',
                'slug' => 'storedetails.store',
            ], [
                'name' => 'OPD',
                'slug' => 'admin.checkopdstatus',
            ],
            // [
            //     'name' => 'SMS Details',
            //     'slug' => 'api.sms',
            // ]

        ];
        return $routeLists;
    }
}
