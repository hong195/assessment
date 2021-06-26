export default {
  authFailed (state) {
    state.currentUser = null
    state.isLoggedIn = false
    state.isActive = false
    state.authError = true
    state.isAdmin = false
    state.isEditor = false
    state.permissions = []
    localStorage.removeItem('token')
  },
  authSuccess (state, payload) {
    state.authError = false
    state.isLoggedIn = true
    state.isActive = false

    if (payload.access_token) {
      state.token = payload.access_token
      localStorage.setItem('token', payload.access_token)
    }
  },
  setUser (state, payload) {
    state.currentUser = payload.user

    if (payload.user.permissions.length) {
      state.permissions = payload.user.permissions
    }

    if (payload.user.role && ['Admin'].includes(payload.user.role.name)) {
      state.isAdmin = true
      state.isEditor = true
    }

    if (payload.user.role && ['Editor'].includes(payload.user.role.name)) {
      state.isEditor = true
    }
  },
}
