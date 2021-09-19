import axios from 'axios'

export default {
  fetchSaleManagers () {
    return axios.get('sale-manager-pharmacies')
  },
  saveSaleManegersPharmacies ({ commit }, { params }) {
    return axios.post('sale-manager-pharmacies', params)
  },
  fetchSaleManager ({ commit }, id) {
    return axios.get(`sale-manager-pharmacies/${id}`)
  },
}
