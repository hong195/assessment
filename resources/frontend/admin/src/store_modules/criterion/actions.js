import axios from 'axios'

export default {
  fetchAll ({ commit }, params = {}) {
    return axios.get('criteria', params)
  },
  create ({ commit }, params = {}) {
    return axios.post('criteria', params)
  },
  createOption ({ commit }, { id, params = {} },) {
    return axios.post(`criteria/${id}/options`, params)
  },
}
