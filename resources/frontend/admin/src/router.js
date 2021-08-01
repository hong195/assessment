import Vue from 'vue'
import Router from 'vue-router'
import store from './store'
import auth from './middleware/auth'
// import hasPermission from './middleware/hasPermission'
import middlewarePipeline from './middleware/middlewarePipeline'
import isAdmin from './middleware/isAdmin'
import isSubscriber from './middleware/isSubscriber'
import isEditor from './middleware/isEditor'
Vue.use(Router)
const router = new Router({
  mode: 'hash',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/pages',
      component: () => import('@/views/pages/Index'),
      children: [
        {
          name: 'login',
          path: 'login',
          component: () => import('@/views/pages/Login'),
        },
        {
          name: 'Register',
          path: 'register',
          component: () => import('@/views/pages/Register'),
        },
      ],
    },
    {
      path: '/',
      component: () => import('@/views/dashboard/Index'),
      name: 'App',
      meta: {
        middleware: [
          auth,
        ],
      },
      children: [
        {
          name: 'home',
          path: 'home',
          component: () => import('@/views/dashboard/Dashboard'),
          meta: {
            middleware: [
              auth,
            ],
          },
        },
        {
          name: 'final-grades',
          path: 'final-grades',
          component: () => import('@/views/dashboard/finalGrades/Index'),
          meta: {
            middleware: [
              // isEditor,
            ],
          },
        },
        {
          name: 'final-grades-create-assessments',
          path: 'final-grades/:finalGradeId/assessments',
          component: () => import('@/views/dashboard/finalGrades/CreateAssessment'),
          meta: {
            middleware: [
              // isEditor,
            ],
          },
        },
        {
          name: 'final-grades-update-assessment',
          path: 'final-grades/:finalGradeId/assessments/:assessmentId',
          component: () => import('@/views/dashboard/finalGrades/UpdateAssessment'),
          meta: {
            middleware: [
              // isEditor,
            ],
          },
        },
        {
          name: 'criteria',
          path: 'criteria',
          component: () => import('@/views/dashboard/criteria/Index'),
          meta: {
            middleware: [
              // isEditor,
            ],
          },
        },

        {
          name: 'pharmacies',
          path: 'pharmacies',
          component: () => import('@/views/dashboard/pharmacies/Index'),
          meta: {
            middleware: [
              isEditor,
            ],
          },
        },
        {
          name: 'create-pharmacy',
          path: 'pharmacies/create',
          component: () => import('@/views/dashboard/pharmacies/Create'),
          meta: {
            middleware: [
              isEditor,
            ],
          },
        },
        {
          name: 'pharmacy-rating',
          path: 'pharmacy-rating',
          component: () => import('@/views/dashboard/pharmacies/PharmacyByYear'),
          meta: {
            middleware: [
              // isEditor,
            ],
          },
        },
        {
          name: 'update-pharmacy',
          path: 'pharmacies/:id',
          component: () => import('@/views/dashboard/pharmacies/Update'),
          meta: {
            middleware: [
              isEditor,
            ],
          },
        },
        {
          name: 'users',
          path: 'users',
          component: () => import('@/views/dashboard/users/Index'),
          meta: {
            middleware: [
              // isEditor,
            ],
          },
        },
        {
          name: 'create-user',
          path: 'users/create',
          component: () => import('@/views/dashboard/users/Create'),
          meta: {
            middleware: [
              // isEditor,
            ],
          },
        },
        {
          name: 'update-user',
          path: 'users/:userId',
          component: () => import('@/views/dashboard/users/Update'),
          meta: {
            middleware: [
              // auth,
            ],
          },
        },
        {
          name: 'create-employee',
          path: 'pharmacy/:pharmacyId/employee/create',
          component: () => import('@/views/dashboard/employees/Create'),
          meta: {
            middleware: [
              // isEditor,
            ],
          },
        },
        {
          name: 'update-employee',
          path: 'pharmacy/:pharmacyId/employee/:employeeId',
          component: () => import('@/views/dashboard/employees/Update'),
          meta: {
            middleware: [
              // auth,
            ],
          },
        },
      ],
    },
    {
      path: '*',
      component: () => import('@/views/pages/Index'),
      children: [
        {
          name: '404 Error',
          path: '',
          component: () => import('@/views/pages/Error'),
        },
      ],
    },
  ],
})

// router.beforeEach((to, from, next) => {
//   if (!to.meta.middleware) {
//     return next()
//   }
//
//   if (to.path === '/') {
//     return next({
//       name: 'home',
//     })
//   }
//
//   const middleware = to.meta.middleware
//   const context = {
//     to,
//     from,
//     next,
//     store,
//   }
//
//   return middleware[0]({
//     ...context,
//     next: middlewarePipeline(context, middleware, 1),
//   })
// })
export default router
