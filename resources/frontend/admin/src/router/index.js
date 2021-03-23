import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'
import middlewarePipeline from '@/middleware/middlewarePipeline'
import routes from './routes'

Vue.use(Router)

const router = new Router(routes)

router.beforeEach((to, from, next) => {
  if (!to.meta.middleware) {
    return next()
  }

  if (to.path === '/') {
    return next({
      name: 'home',
    })
  }

  const middleware = to.meta.middleware
  const context = {
    to,
    from,
    next,
    store,
  }

  return middleware[0]({
    ...context,
    next: middlewarePipeline(context, middleware, 1),
  })
})
export default router
