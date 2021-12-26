import axios from 'axios'

export default {
  fetchAll ({ commit }, params = {}) {
    return axios.get('criteria', params)
  },
  fetchCriterionOption ({ commit }, { criterionId }) {
    return axios.get(`criteria/${criterionId}/options`)
  },
  create ({ commit }, params = {}) {
    return axios.post('criteria', params)
  },
  fetchOption ({ commit }, { criterionId, optionId, params = {} }) {
    return axios.get(`criteria/${criterionId}/options/${optionId}`, params)
  },
  createOption ({ commit }, { id, params = {} },) {
    return axios.post(`criteria/${id}/options`, params)
  },
  updateOption ({ commit }, { criterionId, optionId, params = {} },) {
    return axios.put(`criteria/${criterionId}/options/${optionId}`, params)
  },
  deleteOption ({ commit }, { criterionId, optionId }) {
    return axios.delete(`criteria/${criterionId}/options/${optionId}`)
  },
  deleteCriterion ({ commit }, { criterionId }) {
    return axios.delete(`criteria/${criterionId}`)
  },
  updateCriterion ({ commit }, { criterionId, params }) {
    return axios.put(`criteria/${criterionId}`, params)
  },
}
