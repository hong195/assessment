import axios from 'axios'
import router from '@/router'
import store from '../../store'
axios.interceptors.request.use(function (config) {
  const token = 'Bearer ' + localStorage.getItem('token')
  if (router.history.current.name !== 'login') {
    config.headers.Authorization = token
  }

  return config
})

axios.interceptors.response.use((response) => {
  return response
}, (error) => {
  if (error.response.status !== 401) {
    return new Promise((resolve, reject) => {
      reject(error)
    })
  }

  if (['auth/refresh', 'auth/me', 'auth/login', 'auth/register'].includes(error.config.url)) {
    return new Promise((resolve, reject) => {
      reject(error)
    })
  }

  return store.dispatch('user/refreshToken')
    .then((data) => {
      const config = error.config
      config.headers.Authorization = `Bearer ${data.access_token}`

      return new Promise((resolve, reject) => {
        axios.request(config).then(response => {
          resolve(response)
        }).catch(error => {
          reject(error)
        })
      })
    })
})
