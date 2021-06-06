import axios from 'axios'
export default {
  state: {
    pharmacies: [],
  },
  getters: {
    getPharmacies: state => state.pharmacies,
  },
  mutations: {
    setPharmacies (state, payload) {
      state.pharmacies = payload
    },
  },
  actions: {
    async fetchAllPharmacies ({ commit }) {
      await axios.get('pharmacies', {
        params: {
          perPage: 100000,
        },
      })
        .then(({ data }) => {
          commit('setPharmacies', data.data)
        })
    },
  },
}
