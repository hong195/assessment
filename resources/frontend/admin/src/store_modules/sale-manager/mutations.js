export default {
  setSaleMangerPharmacies (state, payload) {
      state.pharmacies = payload.pharmacies
      state.pharmaciesIds = state.pharmacies.map((pharmacy) => pharmacy.id)
  },
}
