import axios from 'axios'

export default {
  fetchAll ({ commit }, params = {}) {
    return axios.get('employees', params)
  },
  findById ({ commit }, id) {
    return axios.get(`employees/${id}`)
  },
  createEmployee ({ commit }, params = {}) {
    return axios.post('employees', params)
  },
  updateEmployee ({ commit }, { id, params = {} }) {
    return axios.post(`employees/${id}`, params)
  },
  removeEmployee ({ commit }, employeeId) {
    return axios.delete(`employees/${employeeId}`)
  },
}
