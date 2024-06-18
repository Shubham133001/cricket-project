export default [
  //   {
  //   path: '/auth/signin',
  //   name: 'auth-signin',
  //   component: () => import(/* webpackChunkName: "auth-signin" */ '@/pages/auth/SigninPage.vue'),
  //   meta: {
  //     layout: 'auth'
  //   }
  // }, {
  //   path: '/auth/signup',
  //   name: 'auth-signup',
  //   component: () => import(/* webpackChunkName: "auth-signup" */ '@/pages/auth/SignupPage.vue'),
  //   meta: {
  //     layout: 'auth'
  //   }
  //   },
  {
    path: '/auth/verify-email',
    name: 'auth-verify-email',
    component: () => import(/* webpackChunkName: "auth-verify-email" */ '@/pages/auth/VerifyEmailPage.vue'),
    meta: {
      layout: 'auth'
    }
  }, {
    path: '/auth/forgot-password',
    name: 'auth-forgot',
    component: () => import(/* webpackChunkName: "auth-forgot" */ '@/pages/auth/ForgotPage.vue'),
    meta: {
      layout: 'auth'
    }
  }, {
    path: '/auth/resetpassword',
    name: 'auth-reset',
    component: () => import(/* webpackChunkName: "auth-reset" */ '@/pages/auth/ResetPage.vue'),
    meta: {
      layout: 'auth'
    }
  }, {
    path: '/error/not-found',
    name: 'error-not-found',
    component: () => import(/* webpackChunkName: "error-not-found" */ '@/pages/error/NotFoundPage.vue'),
    meta: {
      layout: 'error'
    }
  }, {
    path: '/error/unexpected',
    name: 'error-unexpected',
    component: () => import(/* webpackChunkName: "error-unexpected" */ '@/pages/error/UnexpectedPage.vue'),
    meta: {
      layout: 'error'
    }
  }, {
    path: '/utility/maintenance',
    name: 'utility-maintenance',
    component: () => import(/* webpackChunkName: "utility-maintenance" */ '@/pages/utility/MaintenancePage.vue'),
    meta: {
      layout: 'auth'
    }
  }, {
    path: '/utility/coming-soon',
    name: 'utility-soon',
    component: () => import(/* webpackChunkName: "utility-soon" */ '@/pages/utility/SoonPage.vue'),
    meta: {
      layout: 'auth'
    }
  }, {
    path: '/utility/help',
    name: 'utility-help',
    component: () => import(/* webpackChunkName: "utility-help" */ '@/pages/utility/HelpPage.vue')
  },
  {
    path: '/categories',
    name: 'allcategories',
    component: () => import('@/pages/bookings/AllBookings.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/bookings/add',
    name: 'bookings-add',
    component: () => import('@/pages/bookings/Bookings.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/bookings/slots/:id',
    name: 'bookings-slots',
    component: () => import('@/pages/bookings/Add.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/user/team',
    name: 'userteam',
    component: () => import('@/pages/users/Team.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/pay/:id',
    name: 'payinvoice',
    component: () => import('@/pages/landing/Payment.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/invoice/:id',
    name: 'adminviewinvoice',
    component: () => import('@/pages/admin/invoices/ViewInvoice.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/user/auth/google',
    name: 'googleauth',
    component: () => import('@/pages/auth/GoogleAuth.vue'),
    meta: {
      layout: 'landing'
    }
  },
  //   {
  //   path: '/bookappointment',
  //   name: 'bookappointment',
  //   component: () => import(/* webpackChunkName: "bookappointment" */ '@/pages/appointments/BookAppointment'),
  //   meta: {
  //     layout: 'landing'
  //   }
  // },
  // {
  //   path: '/doctorprofile',
  //   name: 'doctorprofile',
  //   component: () => import('@/pages/Doctors/DoctorProfile'),
  //   meta: {
  //     layout: 'landing'
  //   }
  //   },
  {
    path: '/admin/unauthorized',
    name: 'unauthorized',
    component: () => import('@/pages/error/NotFoundPage.vue'),
    meta: {
      layout: 'error'
    }
  },
  {
    path: '/me',
    name: 'myprofile',
    component: () => import('@/pages/users/MyProfile.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/mybookings',
    name: 'mybookings',
    component: () => import('@/pages/users/Bookings.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/myinvoice',
    name: 'myinvoice',
    component: () => import('@/pages/users/Invoice.vue'),
    meta: {
      layout: 'landing'
    }
  }, {
    path: '/thankyou/:id',
    name: 'thankyou',
    component: () => import('@/pages/landing/Thankyou.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/contactus',
    name: 'contactus',
    component: () => import('@/pages/usefullinks/Contactus.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/about',
    name: 'about',
    component: () => import('@/pages/usefullinks/About.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/terms',
    name: 'about',
    component: () => import('@/pages/usefullinks/TermsConditions.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/privacy',
    name: 'about',
    component: () => import('@/pages/usefullinks/PrivacyPolicy.vue'),
    meta: {
      layout: 'landing'
    }
  },
  {
    path: '/directions',
    name: 'direction',
    component: () => import('@/pages/Direction.vue'),
    meta: {
      layout: 'landing'
    }
  }
]
