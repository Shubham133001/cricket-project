export default [{
  path: '/admin/login',
  name: 'admin-login',
  component: () => import(/* webpackChunkName: "admin-login" */ '@/pages/admin/auth/SigninPage'),
  meta: {
    layout: 'auth'
  }
}, {
  path: '/admin',
  name: 'admin',
  component: () => import(/* webpackChunkName: "admin-dashboard" */ '@/pages/admin/auth/SigninPage')
}, {
  path: '/admin/dashboard',
  name: 'admin-dashboard',
  component: () => import(/* webpackChunkName: "admin-dashboard" */ '@/pages/admin/dashboard/DashboardPage')
}, {
  path: '/admin/adminusers',
  name: 'admin-users',
  component: () => import('@/pages/admin/adminuser/AdminUser.vue')
}, {
  path: '/admin/edit',
  name: 'admin-edit',
  component: () => import(/* webpackChunkName: "admin-edit" */ '@/pages/admin/adminuser/EditUserPage.vue')
}, {
  path: '/admin/adminuser/add',
  name: 'admin-add',
  component: () => import('@/pages/admin/adminuser/AddAdminUser.vue')
}, {
  path: '/admin/adminuser/groups',
  name: 'admin-users-groups',
  component: () => import('@/pages/admin/adminuser/AdminGroups.vue')
},
{
  path: '/admin/adminuser/edit/:id',
  name: 'admin-edit-adminuser',
  component: () => import('@/pages/admin/adminuser/EditAdminUser.vue'),
},
{
  path: '/admin/adminuser/addgroup',
  name: 'admin-addgroup',
  component: () => import('@/pages/admin/adminuser/AddAdminGroup.vue'),
},
{
  path: '/admin/adminuser/editgroup/:id',
  name: 'admin-edit-admin-group',
  component: () => import('@/pages/admin/adminuser/EditAdminGroup.vue'),
}, {
  path: '/admin/settings',
  name: 'admin-settings',
  component: () => import('@/pages/admin/Settings/Settings.vue')
}, {
  path: '/admin/smtpsettings',
  name: 'admin-settings-smtp',
  component: () => import('@/pages/admin/Settings/Smtp.vue')
},
{
  path: '/admin/category/add',
  name: 'admin-category-add',
  component: () => import('@/pages/admin/categories/Add.vue')
},
{
  path: '/admin/category/edit/:id',
  name: 'admin-category-edit',
  component: () => import('@/pages/admin/categories/Edit.vue')
},
{
  path: '/admin/subcategory/edit/:id',
  name: 'admin-subcategory-edit',
  component: () => import('@/pages/admin/categories/Editsubcategory.vue')
},
{
  path: '/admin/categories',
  name: 'admin-category-list',
  component: () => import('@/pages/admin/categories/Categories.vue')
},
{
  path: '/admin/subcategory/:id',
  name: 'admin-subcategory-list',
  component: () => import('@/pages/admin/categories/Subcategories.vue')
},
{
  path: '/admin/category/addsubcategory/:id',
  name: 'admin-category-addsubcategory',
  component: () => import('@/pages/admin/categories/Addsubcategory.vue')
},
{
  path: '/admin/slot/add/:id',
  name: 'admin-slots-add',
  component: () => import('@/pages/admin/slots/Add.vue')
},
{
  path: '/admin/category/slots/:id',
  name: 'admin-category-slots-list',
  component: () => import('@/pages/admin/slots/Viewslots.vue')
},
{
  path: '/admin/slots',
  name: 'admin-slots-list',
  component: () => import('@/pages/admin/slots/List.vue')
},
{
  path: '/admin/bookings',
  name: 'admin-bookings-list',
  component: () => import('@/pages/admin/bookings/List.vue')
},
{
  path: '/admin/bookings/add',
  name: 'admin-bookings-add',
  component: () => import('@/pages/admin/bookings/Bookings.vue')
},
{
  path: '/admin/bookings/edit/:id',
  name: 'admin-bookings-edit',
  component: () => import('@/pages/admin/bookings/Edit.vue')
},
{
  path: '/admin/bookings/slots/:id',
  name: 'admin-bookings-slots',
  component: () => import('@/pages/admin/bookings/Add.vue')
},
{
  path: '/admin/abandonedbookings',
  name: 'admin-abandonedbookings-list',
  component: () => import('@/pages/admin/bookings/AbandonedBookings.vue')
},
{
  path: '/admin/invoices',
  name: 'admin-invoices-list',
  component: () => import('@/pages/admin/invoices/List.vue')
},
{
  path: '/admin/invoice/add',
  name: 'admin-invoices-add',
  component: () => import('@/pages/admin/invoices/AddInvoice.vue')
},
{
  path: '/admin/invoice/edit/:id',
  name: 'admin-invoices-edit',
  component: () => import('@/pages/admin/invoices/EditInvoice.vue')
},
{
  path: '/admin/invoice/view/:id',
  name: 'admin-invoices-view',
  component: () => import('@/pages/admin/invoices/ViewInvoice.vue')
},
{
  path: '/admin/transactions',
  name: 'admin-transactions',
  component: () => import('@/pages/admin/Transactions.vue')
},
{
  path: '/admin/todaytransactions',
  name: 'admin-todaytransactions',
  component: () => import('@/pages/admin/TodayTransactions.vue')
},
{
  path: '/admin/paymentgateways',
  name: 'admin-paymentgateways',
  component: () => import('@/pages/admin/Settings/Paymentgateways.vue')
},
{
  path: '/admin/users',
  name: 'admin-users-list',
  component: () => import('@/pages/users/UsersPage.vue')
},
{
  path: '/admin/user/edit/:id',
  name: 'admin-users-edit',
  component: () => import('@/pages/users/EditUserPage.vue')
},
{
  path: '/admin/user/add',
  name: 'admin-users-add',
  component: () => import('@/pages/users/AddUserPage.vue')
},
{
  path: '/admin/cancellation',
  name: 'admin-cancellation-requests',
  component: () => import('@/pages/admin/bookings/Cancellationlist.vue')
},

]
