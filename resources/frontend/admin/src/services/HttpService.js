import axios from 'axios'

export default class HttpService {
  static get (url, params = {}, headers) {
    return this.makeRequest('get', params, headers)
  }

  static post (url, params = {}, headers) {
    return this.makeRequest(url, 'post', params, headers)
  }

  static put (url, params = {}, headers) {
    return this.makeRequest(url, 'put', params, headers)
  }

  static patch (url, params = {}, headers) {
    return this.makeRequest(url, 'patch', params, headers)
  }

  static delete (url, params = {}, headers) {
    return this.makeRequest(url, 'patch', headers)
  }

  static makeRequest (url, method, params, headers) {
    return axios.request({
      method: method,
      url: url,
      data: params,
      headers: headers,
    })
  }
}
