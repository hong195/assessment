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
              ref="create-assessment-option"
              v-model="formValue"
              scope="create-assessment-option"
              :schema="schema"
              :on-submit="submit"
            />
          </v-card>
        </v-col>
      </v-row>
    </base-material-card>
    <the-description-adding
      ref="the-adding-description"
      :final-grade-id="id"
      @description-added="descriptionAdded"
    />
  </v-container>
</template>

<script>
  import { mapActions } from 'vuex'
  import FormBase from '@/components/form/FormBase'
  import EmployeeInfo from './EmployeeInfo'
  import FinalGradeInfo from './FinalGradeInfo'
  import moment from 'moment'
  import Swal from 'sweetalert2'
  import TheDescriptionAdding from './TheDescriptionAdding'

  export default {
    name: 'CreateAssessment',
    components: { FormBase, EmployeeInfo, FinalGradeInfo, TheDescriptionAdding },
    data () {
      return {
        formValue: '',
        schema: [
          {
            attributes: {},
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
      }
    },
    computed: {
      id () {
        return this.$route.params.finalGradeId
      },
    },
    mounted () {
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
              value: null,
            })

            this.schema.push({
              attributes: [],
              component: 'textarea',
              label: 'Примечание',
              name: `criteria.${index}.description`,
              placeholder: null,
              options: [],
              value: null,
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

          this.schema[0].value = startMonth
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
      ...mapActions('finalGrade', ['createAssessment', 'findById']),
      ...mapActions('employee', {
        findEmployeeById: 'findById',
      }),
      ...mapActions('pharmacy', {
        findPharmacyById: 'findById',
      }),
      descriptionAdded () {
        console.log('added description')
      },
      submit () {
        this.createAssessment({
          id: this.id,
          params: this.formValue,
        })
          .then(() => {
            if (this.assessmentCount <= 10) {
              ++this.finalGrade.assessments_count
            }

            this.$nextTick(() => {
              this.$refs['create-assessment-option'].reset()
            })

            if (this.finalGrade.assessments_count === 10) {
              Swal.fire({
                text: 'Итогоая оценка создана',
                icon: 'success',
              })
                .then(() => {
                  this.$refs['the-adding-description'].openDialog()
                })
            } else {
              this.$store.commit('successMessage', 'Оценка созданая')
            }
          })
          .catch(() => {
            this.$store.commit('errorMessage', 'Ошибка создания оценки')
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
