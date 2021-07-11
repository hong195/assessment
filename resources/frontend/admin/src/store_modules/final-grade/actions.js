import axios from 'axios'

export default {
  fetchAll (params = {}) {
    return axios.get('final-grades', params)
  },
  create ({ commit }, params = {}) {
    return axios.post('final-grades', params)
  },
  findById ({ commit }, id) {
    return axios.get(`final-grades/${id}`)
  },
  createAssessment ({ commit }, { id, params = {} }) {
    return axios.post(`final-grade/${id}/assessments`, params)
  },
  fetchAssessment ({ commit }, { finalGradeId, assessmentId }) {
    return axios.get(`final-grade/${finalGradeId}/assessments/${assessmentId}`)
  },
  updateAssessment ({ commit }, { finalGradeId, assessmentId, params = {} }) {
    return axios.put(`final-grade/${finalGradeId}/assessments/${assessmentId}`, params)
  },
}
