import AuthService from '@/services/AuthService'

export default {
  login ({ commit, dispatch }, credentials) {
    return AuthService.login(credentials)
      .then(({ data }) => {
        commit('setToken', data)
        return dispatch('fetchUser')
      })
      .catch((error) => {
        commit('authFailed')
        return Promise.reject(error)
      })
  },
  logOut ({ commit }) {
    return AuthService.logOut()
      .then(() => {
        commit('authFailed')
      })
  },
  fetchUser ({ commit }) {
    return AuthService.currentUser()
      .then(({ data }) => {
        commit('authSuccess')
        commit('setUser', data)
        return Promise.resolve(data)
      })
  },
  refreshToken ({ commit }) {
    return AuthService.refreshToken()
      .then(({ data }) => {
        commit('authSuccess', data)
        return Promise.resolve(data)
      })
  },
}
