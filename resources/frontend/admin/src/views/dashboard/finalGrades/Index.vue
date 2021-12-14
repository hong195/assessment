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
        <filters @filters-changed="fetchFinalGrades" />
      </v-row>

      <v-data-table :headers="headers" :items="finalGrades" :single-expand="true">
        <template v-slot:item.employee="{ item }">
          {{ getEmployeeName(item.employee_id) }}
        </template>
        <template v-slot:item.scored="{ item }">
          <template v-if="item.status === 'uncompleted'">
            Не сформирован
          </template>
          <template v-else>
            <v-chip :color="getColor(item.scored, item.total)" @click="view(item)">
              {{ item.scored }}/ {{ item.total }}
            </v-chip>
          </template>
        </template>
        <template v-slot:item.month="{ item }">
          <span style="text-transform: capitalize">{{ formatMonth(item.month) }}</span>
        </template>
        <template v-slot:item.status="{ item }">
          {{ $t(item.status) }}
        </template>
        <template v-slot:item.assessment_count="{ item }">
          <div @click="view(item)">
            <assessment-btn-count :added-count="item.assessments_count" />
          </div>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn v-if="canManage" small
                 :icon="true"
                 color="green"
                 :disabled="(item.status === 'completed')"
                 @click="addAssessment(item)"
          >
            <v-icon>mdi-plus</v-icon>
          </v-btn>
          <v-btn v-if="canManage" small
                 :icon="true"
                 color="red"
                 :disabled="(item.status === 'completed')"
                 @click="removeFinalaGrade(item.id)"
          >
            <v-icon>mdi-delete</v-icon>
          </v-btn>
        </template>
      </v-data-table>
      <v-divider class="mt-3" />
    </base-material-card>
    <create ref="createPopup" @refresh="fetchFinalGrades" />
    <view-final-grade-modal ref="finalGradeModal" :final-grade="activeFinalGrade" />
  </v-container>
</template>

<script>
  import moment from 'moment'
  import FinalGradeColor from '@/components/dashboard/mixins/FinalGradeColor'
  import Create from './Create'
  import { mapActions } from 'vuex'
  import ViewFinalGradeModal from './ViewFinalGradeModal'
  import AssessmentBtnCount from './AssessmentBtnCount'
  import Filters from './Filters'
  import canManage from '@/mixins/RoleMixin'

  export default {
    name: 'Index',
    components: { Create, AssessmentBtnCount, Filters, ViewFinalGradeModal },
    mixins: [FinalGradeColor, canManage],
    data () {
      return {
        date: null,
        menu: false,
        dialog: false,
        items: [],
        search: undefined,
        activeFinalGrade: null,
        finalGrades: [],
        pharmacyId: null,
        pharmacies: [],
        ratings: [],
        status: 'completed',
        employees: [],
        withRating: 0,
        filters: null,
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
            text: 'Кол-во проверок',
            value: 'assessment_count',
          },
          {
            text: 'Рейтинг',
            value: 'scored',
          },
          {
            text: 'Статус',
            value: 'status',
          },
          {
            text: 'Действия',
            value: 'actions',
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
    mounted () {
      this.getEmployees()
        .then(({ data }) => {
          this.employees = data.data
        })
    },
    methods: {
      ...mapActions('finalGrade', ['fetchAll', 'remove']),
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

        if (!employee) {
          return null
        }

        return `${employee.first_name} ${employee.middle_name} ${employee.last_name}`
      },
      view (finalGrade) {
        this.activeFinalGrade = finalGrade
        this.$refs.finalGradeModal.openModal()
      },
      removeFinalaGrade (finalGrade) {
        this.remove(finalGrade)
          .then((data) => {
            this.$store.commit('successMessage', data.message)
            this.fetchFinalGrades(this.filters)
          })
      },
      addAssessment (item) {
        this.$router.push({
          name: 'final-grades-create-assessments',
          params: {
            finalGradeId: item.id,
          },
        })
      },
      fetchFinalGrades (filters) {
        if (filters) {
          this.filters = filters
        }

        this.fetchAll({ params: this.filters })
          .then(({ data }) => {
            this.finalGrades = data.data
          })
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
