export default function auth ({ next, store }) {
  if (!store.state.user.isLoggedIn) {
    return next({
      name: 'login',
    })
  }

  return next()
}
