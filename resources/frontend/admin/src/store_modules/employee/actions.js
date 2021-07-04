import axios from 'axios'

export default {
  fetchAll ({ commit }, params = {}) {
    return axios.get('employees', params)
  },
  findById ({ commit }, id) {
    return axios.get(`employees/${id}`)
  },
}
