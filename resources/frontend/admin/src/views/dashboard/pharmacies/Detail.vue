<template>
  <v-dialog
    v-model="dialog"
    max-width="768"
  >
    <v-card>
      <v-card-title>
        <span class="display-2">Информация об аптеке</span>
        <v-spacer />
        <v-icon @click="dialog=false">
          mdi-close
        </v-icon>
      </v-card-title>
      <v-card-text>
        <table class="table-detail">
          <tr>
            <td>
              Номер аптеки
            </td>
            <td>
              {{ pharmacy.number }}
            </td>
          </tr>
          <tr>
            <td>
              Количество сотрудников
            </td>
            <td>
              {{ pharmacy.employeeCount }}
            </td>
          </tr>
          <tr>
            <td>
              Адрес аптеки
            </td>
            <td>
              {{ pharmacy.address }}
            </td>
          </tr>
        </table>
        <v-divider class="my-6" />
        <h3 class="display-2">
          Список сотрудников
        </h3>
        <v-data-table
          :items="employees"
          :headers="headers"
          :loading="loading"
        >
          <template v-slot:item.actions="{ item }">
            <employee-actions :item="item" @actionDeletedResponse="actionDeletedResponse" />
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
  import EmployeeActions from '@/components/dashboard/Actions/EmployeeActions'
  import { mapActions } from 'vuex'

  export default {
    name: 'PharmacyDetail',
    components: { EmployeeActions },
    props: {
      pharmacy: {
        type: Object,
        default: () => {},
      },
    },
    data () {
      return {
        employees: [],
        dialog: false,
        loading: false,
        headers: [
          {
            text: 'Фамилия',
            value: 'last_name',
          },
          {
            text: 'Имя',
            value: 'first_name',
          },
          {
            text: 'Отчество',
            value: 'middle_name',
          },
          {
            sortable: false,
            text: 'Действия',
            value: 'actions',
          },
        ],
      }
    },
    watch: {
      pharmacy () {
        this.getEmployees()
      },
    },
    methods: {
      ...mapActions('pharmacy', ['getPharmacyEmployees']),
      getEmployees () {
        this.loading = true
        this.getPharmacyEmployees(this.pharmacy.id)
          .then(({ data }) => {
            this.employees = data.data
          })
          .finally(() => {
            this.loading = false
          })
      },
      actionDeletedResponse (val) {
        this.items.splice(
          this.items.findIndex(({ id }) => id === val),
          1,
        )
      },
    },
  }
</script>
<style lang="scss">
.table-detail{
  width: 100%;
  td{
    padding: 10px 0;
    border-bottom: 1px solid #c5c5c5;
    span.meta{
      color: rgba(0, 0, 0, 0.6);
    }
  }
  td:nth-child(2){
    color: #1a1a1a;
    font-size: 16px;
  }
  tr:last-child{
    td{
      border-bottom: none;
    }
  }
}
</style>
