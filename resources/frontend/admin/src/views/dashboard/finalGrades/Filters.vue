<template>
  <v-row justify="end">
    <v-col cols="12" sm="12" md="3" lg="2">
      <month-picker v-model="filters.date" />
    </v-col>
    <v-col cols="12" sm="12" md="3" lg="2">
      <v-select v-model="filters.pharmacyId"
                :items="pharmacies"
                item-text="number"
                item-value="id"
                label="Аптека"
                clearable
                outlined
      />
    </v-col>
    <v-col cols="12" sm="12" md="3" lg="2">
      <v-select v-model="filters.status"
                :items="statuses"
                item-text="name"
                item-value="id"
                label="Статус"
                clearable
                outlined
      />
    </v-col>
    <!--    <v-col cols="12" sm="12" md="3" lg="2">-->
    <!--      <v-text-field-->
    <!--        v-model.lazy="filters.search"-->
    <!--        class="ml-auto"-->
    <!--        label="Поиск"-->
    <!--        single-line-->
    <!--        outlined-->
    <!--      />-->
    <!--    </v-col>-->
  </v-row>
</template>

<script>
  import MonthPicker from '@/components/dashboard/MonthPicker'
  import moment from 'moment'
  import { mapActions } from 'vuex'
  export default {
    name: 'Filters',
    components: {
      MonthPicker,
    },
    data () {
      return {
        filters: {
          pharmacyId: null,
          status: null,
          date: null,
          search: null,
        },
        pharmacies: [],
        statuses: [
          {
            id: 'completed',
            name: 'Завершенный',
          },
          {
            id: 'uncompleted',
            name: 'Незавершенный',
          },
        ],
      }
    },
    watch: {
      filters: {
        deep: true,
        handler () {
          const date = moment(this.filters.date)
          this.$emit('filters-changed', {
            ...this.filters,
            year: date.year(),
            month: date.month() + 1,
          })
        },
      },
    },
    mounted () {
      this.fetchPharmacies()
        .then(({ data }) => {
          this.pharmacies = data.data
          console.log(data.data)
        })
    },
    methods: {
      ...mapActions('pharmacy', {
        fetchPharmacies: 'fetchAll',
      }),
    },
  }
</script>
