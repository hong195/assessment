import axios from 'axios'
import authConfig from './utils'

export default {
  login ({ commit, dispatch }, credentials) {
    return axios.post('auth/login', credentials)
      .then(({ data }) => {
        commit('authSuccess', data)
        return dispatch('fetchUser')
      })
      .catch((error) => {
        commit('authFailed')
        return Promise.reject(error)
      })
  },
  logOut ({ commit }) {
    return axios.post('auth/logout', '', authConfig())
      .then(() => {
        commit('authFailed')
      })
      .catch((error) => {
        console.error(error)
      })
  },
  register ({ commit, dispatch }, credentials) {
    return axios.post('auth/register', credentials)
      .then(({ data }) => {
        commit('authSuccess', data)
        return dispatch('fetchUser')
      })
      .catch((error) => {
        commit('authFailed')
        console.error(error)
        return Promise.reject(error)
      })
  },
  fetchUser ({ commit }) {
    return axios.post('auth/me', '', authConfig())
      .then(({ data }) => {
        commit('setUser', data)
        return Promise.resolve(data)
      })
      .catch((error) => {
        commit('authFailed')
        return Promise.reject(error)
      })
  },
  refreshToken ({ commit }) {
    return axios.post('auth/refresh', '', authConfig())
      .then(({ data }) => {
        commit('authSuccess', data)
        return Promise.resolve(data)
      })
      .catch((error) => {
        commit('authFailed')
        return Promise.reject(error)
      })
  },
}
