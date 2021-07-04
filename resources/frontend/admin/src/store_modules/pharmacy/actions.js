import axios from 'axios'

export default {
  fetchAll ({ commit }, params = {}) {
    return axios.get('pharmacies', params)
  },
  findById ({ commit }, id) {
    return axios.get(`pharmacies/${id}`)
  },
}
