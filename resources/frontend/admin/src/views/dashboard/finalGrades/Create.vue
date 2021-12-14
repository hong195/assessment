<template>
  <div class="text-center">
    <v-dialog
      v-model="dialog"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Создать Итоговую Оценку
        </v-card-title>
        <v-card-text>
          <v-container>
            <form-base
              ref="create-final-grade"
              v-model="formValue"
              scope="create-final-grade"
              :schema="schema"
              :on-submit="submit"
            >
              <template v-slot:pharmacy_id-field="{ field, updateFieldValue }">
                <select-field
                  v-model="field.value"
                  :name="field.name"
                  :label="field.label"
                  :options="pharmacies"
                  validation-rule="required"
                  :attributes="field.attributes"
                  @input="updateFieldValue"
                />
              </template>
              <template v-slot:employee_id-field="{ field, updateFieldValue }">
                <select-field
                  v-model="field.value"
                  :name="field.name"
                  :label="field.label"
                  :options="filteredEmployees"
                  validation-rule="required"
                  :attributes="field.attributes"
                  @input="updateFieldValue"
                />
              </template>
            </form-base>
          </v-container>
        </v-card-text>

        <v-divider />
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  import Swal from 'sweetalert2'
  import FormBase from '@/components/form/FormBase'
  import { mapActions } from 'vuex'
  import SelectField from '../../../components/form/fields/SelectField'
  import moment from 'moment'
  export default {
    name: 'Create',
    components: {
      SelectField,
      FormBase,
    },
    data () {
      return {
        formValue: null,
        dialog: false,
        employees: [],
        pharmacies: [],
      }
    },
    computed: {
      filteredEmployees () {
        if (!this.formValue) {
          return []
        }
        return this.employees.filter(employee => {
          return this.formValue.pharmacy_id === employee.pharmacy_id
        })
      },
      schema () {
        return [
          {
            attributes: [],
            component: 'select',
            label: 'Аптеки',
            name: 'pharmacy_id',
            placeholder: null,
            options: [],
            rule: 'required',
            value: null,
          },
          {
            attributes: [],
            component: 'select',
            label: 'Сотрудник Аптеки',
            name: 'employee_id',
            placeholder: null,
            options: [],
            rule: 'required',
            value: null,
          },
          {
            attributes: {
              type: 'month',
            },
            component: 'date',
            label: 'Месяц',
            name: 'month',
            placeholder: null,
            rule: 'required',
            value: null,
          },
        ]
      },
    },
    mounted () {
      this.getEmployees()
      this.fetchPharmacies()
        .then(({ data }) => {
          this.pharmacies = data.data.map((pharmacy) => {
            return {
              id: pharmacy.id,
              name: pharmacy.number,
            }
          })
        })
    },
    methods: {
      ...mapActions('employee', ['fetchAll']),
      ...mapActions('pharmacy', {
        fetchPharmacies: 'fetchAll',
      }),
      ...mapActions('finalGrade', ['create']),
      openPopupForm () {
        this.dialog = true
      },
      submit () {
        this.create({
          employee_id: this.formValue.employee_id,
          assessment_month: this.formValue.month,
        })
          .then(() => {
            this.$store.commit('successMessage', 'Итоговая оценка создана')
            this.$emit('refresh')
          })
          .catch(() => {
            this.$store.commit('errorMessage', 'Ошибка создания итоговой оценки')
          })
      },
      getEmployees () {
        return this.fetchAll()
          .then(({ data }) => {
            this.employees = data.data.map((employee) => {
              return {
                id: employee.id,
                name: `${employee.first_name} ${employee.last_name} ${employee.middle_name}`,
                ...employee,
              }
            })
          })
      },
    },
  }
</script>
