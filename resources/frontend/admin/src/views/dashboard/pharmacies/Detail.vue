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
              Название аптеки
            </td>
            <td>
              {{ item.name }}
            </td>
          </tr>
          <tr>
            <td>
              Количество сотрудников
            </td>
            <td>
              {{ item.users_count }}
            </td>
          </tr>
          <tr>
            <td>
              Адрес аптеки
            </td>
            <td>
              {{ item.address }}
            </td>
          </tr>
          <tr v-if="item.meta && item.meta.length">
            <td>
              Дополнительно
            </td>
            <td>
              <div v-for="meta in item.meta" :key="meta.name">
                <span class="meta">{{ $t(meta.name) }}:</span> {{ meta.value }}
              </div>
            </td>
          </tr>
        </table>
        <v-divider class="my-6" />
        <h3 class="display-2">
          Список сотрудников
        </h3>
        <v-data-table
          :items="users"
          :headers="headers"
          :loading="loading"
        >
          <template v-slot:item.actions="{ item }">
            <actions :item="item" @actionDeletedResponse="actionDeletedResponse" />
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
  import Actions from '@/components/dashboard/Actions/StaffActions'

  export default {
    name: 'PharmacyDetail',
    components: { Actions },
    props: ['item'],
    data () {
      return {
        users: [],
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
            value: 'patronymic',
          },
          {
            text: 'Электронная почта',
            value: 'email',
          },
          {
            sortable: false,
            text: 'Действия',
            value: 'actions',
          },
        ],
      }
    },
    methods: {
      getUsers () {
        this.loading = true
        this.$http.get('users-by-pharmacy?pharmacy_id=' + this.item.id).then(response => {
          this.users = response.data.data
        }).finally(() => {
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
