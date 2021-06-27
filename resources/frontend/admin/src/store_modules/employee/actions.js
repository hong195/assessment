import axios from 'axios'

export default {
  fetchAll ({ commit }, params = {}) {
    return axios.get('employees', params)
  },
}
