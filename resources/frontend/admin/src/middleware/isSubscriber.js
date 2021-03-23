export default function isAdmin ({ next, store }) {
  if (store.getters.isSubscriber) {
    return next()
  }
  return next({
    name: 'login',
  })
}
