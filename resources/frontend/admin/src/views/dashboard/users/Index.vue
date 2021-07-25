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
          <user-actions :item="item" @actionDeletedResponse="fetchUsers" />
        </template>
      </data-table>
    </base-material-card>
  </v-container>
</template>

<script>
  import UserActions from '@/components/dashboard/Actions/UserActions'
  import DataTable from '@/components/dashboard/DataTable'
  import { mapActions } from 'vuex'
  export default {
    name: 'Users',
    components: { UserActions, DataTable },
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
      fetchUsers () {
        this.$refs['data-table'].fetchPosts()
      },
    },
  }
</script>
