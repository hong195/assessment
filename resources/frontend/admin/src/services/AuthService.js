import HttpService from '@/services/HttpService'
import authConfig from '@/store/user/utils'

export default class AuthService {
  static login (credentials) {
    return HttpService.post('auth/login', credentials)
  }

  static logOut () {
    return HttpService.post('auth/logout')
  }

  static refreshToken () {
    return HttpService.post('auth/refresh', {}, authConfig())
  }

  static currentUser () {
    return HttpService.post('auth/me', {}, authConfig())
  }
}
