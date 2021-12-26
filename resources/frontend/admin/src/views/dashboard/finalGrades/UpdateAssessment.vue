<template>
  <v-container id="data-tables" tag="section">
    <base-v-component heading="Создание оценки сотрудника" />
    <base-material-card
      color="indigo"
      icon="mdi-vuetify"
      inline
      class="px-5 py-3"
    >
      <v-row>
        <v-col cols="3">
          <v-card outlined class="sidebar">
            <div v-if="finalGrade">
              <h4 class="final-grade-info">
                Информация о итоговой оценке
              </h4>
              <final-grade-info :final-grade="finalGrade" />
            </div>
            <div v-if="employee && pharmacy">
              <h4 class="employee-info">
                Информация о сотруднике
              </h4>
              <employee-info :employee="employee" :pharmacy="pharmacy" />
            </div>
          </v-card>
        </v-col>
        <v-col cols="9">
          <v-card outlined class="assessment-form">
            <form-base
              ref="update-assessment-option"
              v-model="formValue"
              scope="create-assessment-option"
              :schema="schema"
              :on-submit="submit"
            />
          </v-card>
        </v-col>
      </v-row>
    </base-material-card>
  </v-container>
</template>

<script>
  import { mapActions } from 'vuex'
  import FormBase from '@/components/form/FormBase'
  import EmployeeInfo from './EmployeeInfo'
  import FinalGradeInfo from './FinalGradeInfo'
  import moment from 'moment'

  export default {
    name: 'CreateAssessment',
    components: { FormBase, EmployeeInfo, FinalGradeInfo },
    data () {
      return {
        formValue: '',
        schema: [
          {
            attributes: [],
            component: 'date',
            type: 'hidden',
            name: 'service_date',
            label: 'Дата Обслуживания',
            placeholder: null,
            rule: 'required',
            value: null,
          },
          {
            attributes: {
              cols: 6,
            },
            component: 'text',
            type: 'number',
            name: 'amount',
            label: 'Сумма обслуживания',
            placeholder: null,
            rule: '',
            value: 0,
          },
          {
            attributes: {
              cols: 6,
            },
            component: 'text',
            type: 'number',
            name: 'conversion',
            label: 'Конверсия',
            placeholder: null,
            rule: '',
            value: 0,
          },
        ],
        criteria: [],
        finalGrade: null,
        assessmentCount: 0,
        employee: null,
        pharmacy: null,
        assessment: null,
      }
    },
    computed: {
      id () {
        return this.$route.params.finalGradeId
      },
      assessmentId () {
        return this.$route.params.assessmentId
      },
    },
    mounted () {
      this.fetchAssessment({
        finalGradeId: this.id, assessmentId: this.assessmentId,
      })
        .then(({ data }) => {
          this.assessment = data.data
          const criteria = this.assessment.criteria

          this.schema[0].value = this.assessment.check.service_date
          this.schema[1].value = this.assessment.check.amount
          this.schema[2].value = this.assessment.check.conversion

          this.fetchCriterion()
            .then(({ data }) => {
              data.data.forEach((criterion, index) => {
                this.schema.push({
                  attributes: [],
                  component: 'text',
                  type: 'hidden',
                  name: `criteria.${index}.name`,
                  placeholder: null,
                  rule: 'required',
                  value: criterion.name,
                })

                this.schema.push({
                  attributes: [],
                  component: 'radio',
                  label: criterion.label,
                  name: `criteria.${index}.selected`,
                  placeholder: null,
                  options: criterion.options.map((option) => {
                    return {
                      id: `${option.name}`,
                      label: option.name,
                      value: option.name,
                    }
                  }),
                  rule: 'required',
                  value: criteria[index].name === criterion.name ? criteria[index].selected : null,
                })

                this.schema.push({
                  attributes: [],
                  component: 'textarea',
                  label: 'Примечание',
                  name: `criteria.${index}.description`,
                  placeholder: null,
                  options: [],
                  value: criteria[index].description || null,
                })
              })
            })
        })

      this.findById(this.id)
        .then(({ data }) => {
          this.finalGrade = data.data

          const startMonth = moment(this.finalGrade.month).startOf('month').format('YYYY-MM-DD')
          const endMonth = moment(this.finalGrade.month).endOf('month').format('YYYY-MM-DD')
          this.schema[0].attributes = {
            min: startMonth,
            max: endMonth,
          }
        })
        .then(() => {
          return this.findEmployeeById(this.finalGrade.employee_id)
            .then(({ data }) => {
              this.employee = data.data
            })
        })
        .then(() => {
          this.findPharmacyById(this.employee.pharmacy_id)
            .then(({ data }) => {
              this.pharmacy = data.data
            })
        })
    },
    methods: {
      ...mapActions('criterion', {
        fetchCriterion: 'fetchAll',
      }),
      ...mapActions('finalGrade', ['updateAssessment', 'findById', 'fetchAssessment']),
      ...mapActions('employee', {
        findEmployeeById: 'findById',
      }),
      ...mapActions('pharmacy', {
        findPharmacyById: 'findById',
      }),
      submit () {
        this.updateAssessment({
          finalGradeId: this.id,
          assessmentId: this.assessmentId,
          params: this.formValue,
        })
          .then(() => {
            this.$store.commit('successMessage', 'Оценка обновлена')
            this.$router.push({
              name: 'final-grades',
            })
          })
          .catch(() => {
            this.$store.commit('errorMessage', 'Ошибка обновления оценки')
          })
      },
    },
  }
</script>
<style lang="scss">
.assessment-form, .sidebar {
  padding: 50px;
}
.employee-info, .final-grade-info {
    margin: 20px 0;
}
</style>
