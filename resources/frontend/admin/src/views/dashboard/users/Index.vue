<template>
  <v-container id="data-tables" tag="section">
    <base-material-card
      color="indigo"
      icon="mdi-account-multiple"
      inline
      class="px-5 py-3 mt-6"
    >
      <div class="d-flex align-center">
        <v-btn color="success" :to="addUserRouteParams">
          Добавить пользователя
        </v-btn>
      </div>

      <v-divider class="mt-3" />
      <data-table
        ref="data-table"
        fetch-url="users"
        :headers="headers"
      >
        <template v-slot:item.actions="{ item }">
          <employee-actions :item="item" />
        </template>
      </data-table>
    </base-material-card>
  </v-container>
</template>

<script>
  import EmployeeActions from '@/components/dashboard/Actions/EmployeeActions'
  import DataTable from '@/components/dashboard/DataTable'
  export default {
    name: 'Users',
    components: { EmployeeActions, DataTable },
    data () {
      return {
        headers: [
          {
            text: 'Имя',
            value: 'first_name',
            sortable: false,
          },
          {
            text: 'Фамилия',
            value: 'last_name',
            sortable: false,
          },
          {
            text: 'Отчество',
            value: 'middle_name',
            sortable: false,
          },
          {
            text: 'Логин',
            value: 'login',
            sortable: false,
          },
          {
            text: 'Роль',
            value: 'role',
            sortable: false,
          },
          {
            sortable: false,
            text: 'Действия',
            value: 'actions',
            align: 'right',
          },
        ],
      }
    },
    computed: {
      addUserRouteParams () {
        return {
          name: 'create-user',
        }
      },
    },
    methods: {
      openChecksDialog (id) {
        this.$refs.checksDialog.dialog = true
        this.$refs.checksDialog.userId = id
        this.$refs.checksDialog.fetchUserChecks()
      },
    },
  }
</script>
