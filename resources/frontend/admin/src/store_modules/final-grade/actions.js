import axios from 'axios'

export default {
  fetchAll (params = {}) {
    return axios.get('final-grades', params)
  },
  create ({ commit }, params = {}) {
    return axios.post('final-grades', params)
  },
}
