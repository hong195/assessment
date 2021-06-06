export default function isAdmin ({ next, store }) {
  if (store.getters.isSubscriber) {
    // if (store.getters.isSubscriber || store.getters.isAdmin) {
    return next()
  }
  return next({
    name: 'login',
  })
}
