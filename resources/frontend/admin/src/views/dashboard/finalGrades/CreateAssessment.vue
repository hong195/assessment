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
  </v-container>
</template>

<script>
  import { mapActions } from 'vuex'
  import FormBase from '@/components/form/FormBase'
  import EmployeeInfo from './EmployeeInfo'
  import FinalGradeInfo from './FinalGradeInfo'

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
        return this.$route.params.id
      },
    },
    mounted () {
      this.fetchAll()
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
              label: criterion.name,
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
      ...mapActions('criterion', ['fetchAll']),
      ...mapActions('finalGrade', ['createAssessment', 'findById']),
      ...mapActions('employee', {
        findEmployeeById: 'findById',
      }),
      ...mapActions('pharmacy', {
        findPharmacyById: 'findById',
      }),
      submit () {
        this.createAssessment({
          id: this.id,
          params: this.formValue,
        })
          .then(() => {
            if (this.assessmentCount <= 10) {
              ++this.finalGrade.assessments_count
            }
          })
          .catch(() => {
            console.log(1)
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
