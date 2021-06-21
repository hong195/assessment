<template>
  <v-container id="data-tables" tag="section">
    <base-v-component heading="Рейтинг сотрудников" />
    <base-material-card
      color="indigo"
      icon="mdi-vuetify"
      inline
      class="px-5 py-3"
    >
      <template v-slot:after-heading>
        <div class="display-2 font-weight-light">
          Анализ эффективности сотрудников
        </div>
      </template>

      <v-row justify="end">
        <v-col cols="12" sm="12" md="3" lg="2">
          <month-picker v-model="date" />
        </v-col>
        <v-col cols="12" sm="12" md="3" lg="2">
          <v-select v-model="pharmacyId"
                    :items="pharmacies"
                    item-text="name"
                    item-value="id"
                    label="Аптека"
                    clearable
                    outlined
          />
        </v-col>
        <v-col cols="12" sm="12" md="3" lg="2">
          <v-text-field
            v-model.lazy="search"
            class="ml-auto"
            label="Поиск"
            single-line
            outlined
          />
        </v-col>
      </v-row>

      <v-data-table :headers="headers" />

      <v-divider class="mt-3" />
    </base-material-card>
  </v-container>
</template>

<script>
  import moment from 'moment'
  import DataTable from '@/components/dashboard/DataTable'
  import MonthPicker from '@/components/dashboard/MonthPicker'
  import RatingColor from '@/components/dashboard/mixins/RatingColor'
  import Conversion from '@/components/dashboard/Graphs/table_parts/Conversion'
  import RatingScore from '@/components/dashboard/Graphs/table_parts/RatingScore'

  export default {
    name: 'StaffRating',
    components: { RatingScore, Conversion, DataTable, MonthPicker },
    mixins: [RatingColor],
    data () {
      return {
        date: null,
        menu: false,
        dialog: false,
        items: [],
        search: undefined,
        checks: [],
        rating: {},
        pharmacyId: null,
        pharmacies: [],
        ratings: [],
        showRating: [
          {
            id: 0,
            label: 'Всех',
          },
          {
            id: 1,
            label: 'С только тех у кого есть рейтингом',
          },
        ],
        withRating: 0,
        headers: [
          {
            text: 'Фамилия',
            value: 'user.last_name',
            sortable: false,
          },
          {
            text: 'Имя',
            value: 'user.first_name',
            sortable: false,
          },
          {
            text: 'Отчество',
            value: 'user.patronymic',
            sortable: false,
          },
          {
            text: 'Рейтинг',
            value: 'rating',
          },
          {
            text: 'Конверсия',
            value: 'conversion',
          },
        ],
      }
    },
    computed: {
      searchParams () {
        const date = moment(this.date)
        return {
          name: this.search,
          ratingYear: date.format('YYYY'),
          ratingMonth: date.format('M'),
          pharmacyId: this.pharmacyId,
          withRating: this.withRating,
        }
      },
    },
    watch: {
      date (val) {
        const date = moment(val)
        // this.$http.get(`pharmacy-rating?year=${date.format('YYYY')}&month=${date.format('M')}`).then(res => {
        //   this.pharmacies = res.data.data
        // })
      },
    },
    mounted () {
      this.axios.get('efficiency-analyzes')
        .then(({ data }) => {
          this.ratings = data
          console.log(data)
        })

      if (this.$route.query.rating_id) {
        this.rating = {}
        this.rating.id = parseInt(this.$route.query.rating_id)
        this.dialog = true
      }
    },
    methods: {
      closeDialog () {
        this.dialog = false
      },
      setRating (rating) {
        this.dialog = true
        this.rating = rating
      },
      setDate (date, dialog) {
        dialog.save(date)
        this.date = date
      },
    },
  }
</script>
<style lang="scss">
.rating {
  &__btn {
    & .v-btn__content {
      color: #fff;
    }
  }
}
</style>
