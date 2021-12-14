import axios from 'axios'

export default {
  fetchAll ({ commit }, { params = {} }) {
    return axios.get('final-grades', {
      params,
    })
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
  addDescription ({ commit }, { finalGradeId, description }) {
    return axios.post(`final-grade/${finalGradeId}/description`, {
      description: description,
    })
  },
  remove ({ commit }, finalGradeId) {
    return axios.delete(`final-grades/${finalGradeId}`)
  },
}
