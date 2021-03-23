export default function isAdmin ({ next, store }) {
  if (!store.state.user.isEditor) {
    return next({
      name: 'login',
    })
  }
  return next()
}
