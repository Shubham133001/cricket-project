import Vue from 'vue'
import Router from 'vue-router'

// Routes
import PagesRoutes from './pages.routes'
import UsersRoutes from './users.routes'
import LandingRoutes from './landing.routes'
import AdminRoutes from './admin.routes'

Vue.use(Router)

export const routes = [{
  path: '/',
  name: 'home',
  component: () => import(/* webpackChunkName: "coming-soon" */ '@/pages/landing/HomePage.vue'),
  meta: {
    layout: 'landing'
  }
},
...PagesRoutes,
...UsersRoutes,
...AdminRoutes,
...LandingRoutes,
{
  path: '/blank',
  name: 'blank',
  component: () => import(/* webpackChunkName: "blank" */ '@/pages/BlankPage.vue')
},
{
  path: '*',
  name: 'error',
  component: () => import(/* webpackChunkName: "error" */ '@/pages/error/NotFoundPage.vue'),
  meta: {
    layout: 'error'
  }
}
]

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL || '/',
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition

    return { x: 0, y: 0 }
  },
  routes
})

/**
 * Before each route update
 */
router.beforeEach((to, from, next) => {
  return next()
})

/**
 * After each route update
 */
router.afterEach((to, from) => {
})

export default router
