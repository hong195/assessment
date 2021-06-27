import axios from 'axios'

export default {
  fetchAll (params = {}) {
    return axios.get('final-grades', params)
  },
  create ({ commit }, params = {}) {
    return axios.post('final-grades', params)
  },
  createAssessment ({ commit }, { id, params = {} }) {
    return axios.post(`final-grades/${id}/assessments`, params)
  },
}
