export default [{
  path: '/landing',
  name: 'landing-home',
  component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/landing/HomePage.vue'),
  meta: {
    layout: 'landing'
  }
}, {
  path: '/landing/pricing',
  name: 'landing-pricing',
  component: () => import(/* webpackChunkName: "landing-pricing" */ '@/pages/landing/PricingPage.vue'),
  meta: {
    layout: 'landing'
  }
},
{
  path: '/thankyou/:id',
  name: 'thankyou',
  component: () => import(/* webpackChunkName: "landing-thankyou" */ '@/pages/landing/Thankyou.vue'),
  meta: {
    layout: 'landing'
  }
}
]
