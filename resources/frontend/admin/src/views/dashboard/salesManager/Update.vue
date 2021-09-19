<template>
  <v-container>
    <template>
      <base-material-card
        color="success"
        icon="mdi-account"
        title="Руководители отдела продаж"
        class="px-5 py-3 mb-10"
      >
        <form-base
          ref="default-form"
          :schema="schema"
          scope="form"
          :on-submit="save"
        >
          <template v-slot:sale_manager-field="{ field, updateFieldValue }">
            <v-text-field type="text" :disabled="true" :placeholder="field.label" :value="currentSaleManager ? currentSaleManager.name : ''" />
          </template>
          <template v-slot:pharmacies_ids-field="{ field, updateFieldValue }">
            <validation-provider
              v-slot="{ errors }"
              tag="div"
              rules="required"
              :name="field.label"
              :vid="field.name"
            >
              <v-select v-model="selectedPharmacies"
                        :error-messages="errors"
                        v-bind="field.attributes"
                        :items="pharmacies"
                        :placeholder="field.label"
              />
            </validation-provider>
          </template>
        </form-base>
      </base-material-card>
    </template>
  </v-container>
</template>

<script>
  import FormBase from '@/components/form/FormBase'
  import { mapActions, mapState } from 'vuex'
  export default {
    name: 'Index',
    components: { FormBase },
    data () {
      return {
        saleManagers: [],
        currentSaleManagerId: null,
        currentSaleManager: null,
        pharmacies: [],
        selectedPharmacies: null,
        schema: [
          {
            component: 'text',
            label: 'Руководитель отдела продаж',
            name: 'sale_manager',
            placeholder: null,
            value: null,
            attributes: {
              disabled: true,
            },
          },
          {
            component: 'select',
            label: 'Список аптек',
            name: 'pharmacies_ids',
            placeholder: 'Аптеки',
            options: [],
            rule: 'required',
            value: null,
            attributes: {
              'item-text': 'number',
              'item-value': 'id',
              multiple: true,
            },
          },
        ],
      }
    },
    mounted () {
      this.fetchAll()
        .then(({ data }) => {
          this.pharmacies = data.data
        })

      this.selectedPharmacies = this.saleManagerPharmacies
      this.currentSaleManagerId = this.$route.params.id

      this.fetchSaleManager(this.$route.params.id)
        .then(({ data }) => {
          this.currentSaleManager = data.data

          this.selectedPharmacies = this.currentSaleManager.pharmacies.map((pharmacy) => {
            return pharmacy.id
          })
        })
    },
    methods: {
      ...mapActions('saleManager', ['fetchSaleManager', 'saveSaleManegersPharmacies']),
      ...mapActions('pharmacy', ['fetchAll']),
      save () {
        this.saveSaleManegersPharmacies({
          params: {
            sale_manager: this.currentSaleManagerId,
            pharmacies_ids: this.selectedPharmacies,
          },
        })
          .then(({ data }) => {
            this.$store.commit('successMessage', data.message)
            setTimeout(() => {
              this.$router.push({
                name: 'sale-mangers',
              })
            })
          })
          .catch(({ data }) => {
            this.$store.commit('successMessage', data.message)
          })
      },
    },
  }
</script>
