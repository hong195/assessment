<template>
  <v-dialog
    v-model="dialog"
    max-width="700px"
    transition="dialog-bottom-transition"
  >
    <v-card min-height="400px">
      <v-card-title class="align-top">
        <month-picker v-model="date" />
        <v-spacer />
        <v-btn :to="{name: 'create-checks', query: {'user_id': userId } }" x-large outlined
               color="success"
               :disabled="canCrud"
        >
          Создать
        </v-btn>
        <v-spacer />
        <v-icon @click="dialog=false">
          mdi-close
        </v-icon>
      </v-card-title>
      <v-card-text>
        <v-data-table
          :headers="headers"
          width="700px"
          :loading="isLoading"
          :items="checks"
          :search.sync="search"
          :sort-by="['created_at']"
          :sort-desc="[false, true]"
          hide-default-footer
          :single-expand="true"
          show-expand
          :expanded.sync="expanded"
          class="table-checks"
        >
          <template v-slot:item.created_at="{ item }">
            {{ formattedDate(item.created_at) }}
          </template>
          <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="headers.length">
              <h3 class="text-center py-3">
                Информация о чеке
              </h3>
              <view-check :active-check="item" />
            </td>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-btn
              v-for="(action, i) in actions"
              :key="i"
              class="px-2 ml-1"
              :disabled="canCrud"
              :color="action.color"
              min-width="0"
              small
              @click="actionMethod(action.method, item)"
            >
              <v-icon small v-text="action.icon" />
            </v-btn>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
  import MonthPicker from '@/components/dashboard/MonthPicker'
  import ViewCheck from '@/components/dashboard/ViewCheck'
  import moment from 'moment'
  export default {
    name: 'Checks',
    components: { ViewCheck, MonthPicker },
    data () {
      return {
        isLoading: false,
        expanded: [],
        userId: null,
        dialog: false,
        checks: [],
        search: null,
        date: null,
        headers: [
          {
            text: '№',
            value: 'key',
          },
          {
            text: 'Наименование',
            value: 'name',
          },
          {
            text: 'Дата чека',
            value: 'created_at',
            align: 'left',
          },
          {
            sortable: false,
            text: 'Действия',
            value: 'actions',
            align: 'right',
          },
          { text: '', value: 'data-table-expand', align: 'right' },
        ],
        actions: [
          {
            color: 'success',
            icon: 'mdi-pencil',
            can: 'edit',
            method: 'editItem',
          },
          {
            color: 'error',
            icon: 'mdi-close',
            can: 'delete',
            method: 'deleteItem',
          },
        ],
      }
    },
    computed: {
      canCrud () {
        return this.checks.length >= 10
      },
    },
    watch: {
      date () {
        this.fetchUserChecks()
      },
    },
    methods: {
      formattedDate (val) {
        return moment(val).locale('ru').format('D MMMM YYYY HH:mm')
      },
      fetchUserChecks () {
        this.isLoading = true
        const date = moment(this.date || new Date())
        this.axios.get('checks', {
          params: {
            userId: this.userId,
            year: date.format('Y'),
            month: date.format('M'),
          },
        })
          .then(({ data }) => {
            this.isLoading = false
            this.checks = data.data
            this.checks.forEach((item, key) => { item.key = key + 1 })
          }).catch(e => {
            this.isLoading = false
            console.log(e)
          })
      },
      actionMethod (funcName, item) {
        this[funcName](item)
      },
      deleteItem (item) {
        this.axios.delete(`checks/${item.id}`)
          .then(({ data }) => {
            this.fetchUserChecks()
            this.$store.commit('successMessage', data.message)
          })
          .catch(error => {
            this.$store.commit('errorMessage', error)
          })
      },
      editItem (item) {
        this.$router.push({ name: 'update-checks', params: { id: item.id } })
      },
      viewItem (item) {
        this.activeItem = item
        this.$refs.detail.dialog = true
      },
    },
  }
</script>
<style lang="scss">
.table-checks {
  .v-list-item__title {
    white-space: normal !important;
  }
}
.table-detail {
  width: 100%;

  td {
    padding: 10px 0;
    border-bottom: 1px solid #c5c5c5;

    span.meta {
      color: rgba(0, 0, 0, 0.6);
    }
  }

  td:nth-child(2) {
    color: #1a1a1a;
    font-size: 16px;
  }

  tr:last-child {
    td {
      border-bottom: none;
    }
  }
}
</style>
