import axios from 'axios'

export default {
  fetchAll ({ commit }, params = {}) {
    return axios.get('pharmacies', params)
  },
  findById ({ commit }, id) {
    return axios.get(`pharmacies/${id}`)
  },
  createPharmacy ({ commit }, { params }) {
    return axios.post('pharmacies', params)
  },
  updatePharmacy ({ commit }, { pharmacyId, params }) {
    return axios.put(`pharmacies/${pharmacyId}`, params)
  },
  getPharmacyEmployees ({ commit }, pharmacyId) {
    return axios.get(`pharmacy/${pharmacyId}/employees`)
  },
  removePharmacy ({ commit }, pharmacyId) {
    return axios.delete(`pharmacies/${pharmacyId}`)
  },
  fetchYearlyFinalGrade ({ commit }, { pharmacyId, params = {} }) {
    return axios.get(`pharmacy/${pharmacyId}/final-grade`, {
      params,
    })
  },
}
