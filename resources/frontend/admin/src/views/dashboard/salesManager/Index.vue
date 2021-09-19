<template>
  <v-container>
    <template v-if="isSaleManager">
      <pharmacy-rating
        :show-attribute-wheel="false"
      />
    </template>
    <template v-if="isAdmin">
      <base-material-card
        color="success"
        icon="mdi-account"
        title="Руководители отдела продаж"
        class="px-5 py-3 mb-10"
      >
        <data-table
          ref="data-table"
          fetch-url="sale-manager-pharmacies"
          :headers="headers"
        >
          <template v-slot:item.pharmacies="{ item }">
            {{ getSaleManagerPharmaciesName(item) }}
          </template>
          <template v-slot:item.actions="{ item }">
            <v-btn
              class="px-2 ml-1"
              color="green"
              min-width="0"
              small
              @click="updateSaleManagerPharmacies(item.id)"
            >
              <v-icon small color="white" v-text="'mdi-pencil'" />
            </v-btn>
          </template>
        </data-table>
      </base-material-card>
    </template>
  </v-container>
</template>

<script>
  import PharmacyRating from '@/components/dashboard/Graphs/PharmacyRating'
  import DataTable from '@/components/dashboard/DataTable'
  import { mapState } from 'vuex'
  export default {
    name: 'SalesManagerPharmacies',
    components: { PharmacyRating, DataTable },
    data () {
      return {
        headers: [
          {
            text: 'Идентификатор',
            value: 'id',
            sortable: false,
          },
          {
            text: 'Фамилия Имя Отчество',
            value: 'name',
            sortable: false,
          },
          {
            text: 'Привязанные аптеки',
            value: 'pharmacies',
            sortable: false,
          },
          {
            text: '**Действия',
            value: 'actions',
            align: 'right',
            sortable: false,
          },
        ],
      }
    },
    computed: {
      ...mapState('user', ['isSaleManager', 'isAdmin']),
    },
    methods: {
      getSaleManagerPharmaciesName (saleManager) {
        if (!saleManager || !saleManager.pharmacies.length) {
          return ''
        }

        return saleManager.pharmacies.map(pharmacy => pharmacy.number).join(', ')
      },
      updateSaleManagerPharmacies (saleManagerId) {
        this.$router.push({
          name: 'sale-mangers-pharmacies',
          params: {
            id: saleManagerId,
          },
        })
      },
    },
  }
</script>
