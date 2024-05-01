import menuPages from './menus/pages.menu'

export default {
  // main navigation - side menu
  menu: [{
    text: '',
    key: '',
    items: [
      { icon: 'mdi-view-dashboard-outline', key: 'menu.dashboard', text: 'Dashboard', link: '/admin/dashboard' },
      { icon: 'mdi-account-multiple-outline', key: 'menu.users', text: 'Users', link: '/admin/users' }
    ]
  }, {
    text: '',
    key: '',
    items: [
      {
        icon: 'mdi-account-multiple-outline', text: 'Admins',
        items: [
          { icon: 'mdi-circle-medium', key: 'menu.list', text: 'List', link: '/admin/adminusers', apilink: '/api/admin/admins' },
          { icon: 'mdi-circle-medium', key: 'menu.addnew', text: 'Add New', link: '/admin/adminuser/add', apilink: '/api/admin/admin/add' },
          { icon: 'mdi-circle-medium', key: 'menu.groups', text: 'Groups', link: '/admin/adminuser/groups', apilink: '/api/admin/groups' },
        ]
      },

      // { icon: 'mdi-run-fast', text: 'Walkin Appointment', link: '/admin/walkin-appointments' },
      {
        icon: 'mdi-cogs', text: 'Categories',
        items: [
          { icon: 'mdi-circle-medium', text: 'List', link: '/admin/categories', apilink: '/api/admin/categories' },
          { icon: 'mdi-circle-medium', text: 'Add New', link: '/admin/category/add', apilink: '/api/admin/category/add' },
        ]
      },
      { icon: 'mdi-format-list-checks', text: 'Slots', link: '/admin/slots' },
      { icon: 'mdi-calendar', text: 'Bookings', link: '/admin/bookings' },
      { icon: 'mdi-table-cancel', text: 'Cancellation Requests', link: '/admin/cancellation' },
      {
        icon: 'mdi-receipt', text: 'Invoices',
        items: [
          { icon: 'mdi-circle-medium', text: 'List', link: '/admin/invoices', apilink: '/api/admin/invoices' },
          // { icon: 'mdi-circle-medium', text: 'Add New', link: '/admin/invoice/add', apilink: '/api/admin/invoice/add' },
        ]
      },

      {
        icon: 'mdi-file', text: 'Transactions',
        items: [
          { icon: 'mdi-circle-medium', text: 'All Transactions', link: '/admin/transactions', apilink: '/admin/transactions' },
          { icon: 'mdi-circle-medium', text: 'Today Transactions', link: '/admin/todaytransactions', apilink: '/admin/todaytransactions' },
        ]
      },
      // { icon: 'mdi-list-status', text: 'OPD Status', link: '/admin/opdstatus' },

    ]
  },

  {
    text: 'Settings',
    key: 'Settings',
    items: [
      { icon: 'mdi-cogs', text: 'General Settings', link: '/admin/settings' },

    ]
  },
  {
    text: 'Payment Gateways',
    key: 'paymentgateways',
    items: [
      { icon: 'mdi-credit-card-outline', text: 'Payment Gateways', link: '/admin/paymentgateways' },

    ]
  },
  ],
}
