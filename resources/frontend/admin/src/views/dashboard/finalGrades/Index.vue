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
          Итоговые оценки сотрдуников
        </div>
      </template>

      <v-row justify="end">
        <v-col>
          <v-btn color="primary" @click="addFinalGrade">
            Добавить
          </v-btn>
        </v-col>
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
          <v-select v-model="status"
                    :items="statuses"
                    item-text="name"
                    item-value="id"
                    label="Статус"
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

      <v-data-table :headers="headers" :items="finalGrades" show-expand :single-expand="true">
        <template v-slot:item.employee="{ item }">
          {{ getEmployeeName(item.employeeId) }}
        </template>
        <template v-slot:item.scored="{ item }">
          <template v-if="item.status === 'uncompleted'">
            Не сформирован
          </template>
          <template v-else>
            {{ item.scored }}
          </template>
        </template>
        <template v-slot:item.month="{ item }">
          <span style="text-transform: capitalize">{{ formatMonth(item.month) }}</span>
        </template>
        <template v-slot:item.status="{ item }">
          <template v-if="item.status === 'uncompleted'">
            Не завершен
          </template>
          <template v-else>
            Завершен
          </template>
        </template>
        <template v-slot:expanded-item="{ headers, item }">
          <td colspan="7" style="padding: 0">
            <v-data-table :headers="assessmentHeaders" :items="items.assessments" class="assessment-list" />
            <v-container>
              <v-row justify="center" style="padding: 20px 0;">
                <v-btn color="primary">
                  Добавить Оценку Сотрудника
                </v-btn>
              </v-row>
            </v-container>
          </td>
        </template>
      </v-data-table>

      <v-divider class="mt-3" />
    </base-material-card>
    <create ref="createPopup" />
  </v-container>
</template>

<script>
  import moment from 'moment'
  import DataTable from '@/components/dashboard/DataTable'
  import MonthPicker from '@/components/dashboard/MonthPicker'
  import RatingColor from '@/components/dashboard/mixins/RatingColor'
  import Conversion from '@/components/dashboard/Graphs/table_parts/Conversion'
  import RatingScore from '@/components/dashboard/Graphs/table_parts/RatingScore'
  import Create from './Create'
  import { mapActions } from 'vuex'

  export default {
    name: 'Index',
    components: { Create, RatingScore, Conversion, DataTable, MonthPicker },
    mixins: [RatingColor],
    data () {
      return {
        date: null,
        menu: false,
        dialog: false,
        items: [],
        search: undefined,
        finalGrades: [],
        pharmacyId: null,
        pharmacies: [],
        ratings: [],
        status: 'completed',
        employees: [],
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
        withRating: 0,
        headers: [
          {
            text: 'Сотрудник',
            value: 'employee',
            sortable: false,
          },
          {
            text: 'Общаяя Сумма Обслуживани',
            value: 'total_amount',
          },
          {
            text: 'Общая Конверсия Обслуживания',
            value: 'total_sale_conversion',
          },
          {
            text: 'Месяц',
            value: 'month',
          },
          {
            text: 'Рейтинг',
            value: 'scored',
          },
          {
            text: 'Статус',
            value: 'status',
          },
        ],
        assessmentHeaders: [
          {
            text: 'Сумма Обслуживания',
            value: 'amount',
          },
          {
            text: 'Конверсия Обслуживания',
            value: 'sale_conversion',
          },
          {
            text: 'Дата обсулуживания',
            value: 'service_date',
          },
          {
            text: 'Набранный бал',
            value: 'scored',
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
      this.fetchAll()
        .then(({ data }) => {
          this.finalGrades = data.data
        })

      this.getEmployees()
        .then(({ data }) => {
          this.employees = data.data
        })
    // if (this.$route.query.rating_id) {
    //   this.rating = {}
    //   this.rating.id = parseInt(this.$route.query.rating_id)
    //   this.dialog = true
    // }
    },
    methods: {
      ...mapActions('finalGrade', ['fetchAll']),
      ...mapActions('employee', {
        getEmployees: 'fetchAll',
      }),
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
      addFinalGrade () {
        this.$refs.createPopup.openPopupForm()
      },
      formatMonth (month) {
        return moment(month).locale('ru').format('MMMM YYYY')
      },
      getEmployeeById (employeeId) {
        return this.employees.find((employee) => {
          return employee.id === employeeId
        })
      },
      getEmployeeName (employeeId) {
        const employee = this.getEmployeeById(employeeId)

        return `${employee.first_name} ${employee.middle_name} ${employee.last_name}`
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
.assessment-list {
  .v-data-footer {
    &__select, &__pagination, &__icons-before, &__icons-after {
      display: none;
    }
  }
}
</style>
