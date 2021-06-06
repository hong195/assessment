<template>
  <v-container id="extended-tables" fluid tag="section">
    <base-material-card
      color="success"
      icon="mdi-clipboard-text"
      inline
      title="Список Атрибутов"
      class="px-5 py-3 my-6"
    >
      <data-table
        ref="data-table"
        fetch-url="check-attributes"
        :headers="headers"
        :search-options="searchParams"
      >
        <template v-slot:item.type="{ item }">
          <tr>
            <td>{{ item.type === 'radio' ? 'Радио кнопка' : 'Текстовое поле' }}</td>
          </tr>
        </template>
        <template v-slot:item.use_in_rating="{ item }">
          <tr>
            <td>{{ item.use_in_rating ? 'Да' : 'Нет' }}</td>
          </tr>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn
            v-for="(action, i) in actions"
            :key="i"
            class="px-2 ml-1"
            :color="action.color"
            min-width="0"
            small
            @click="actionMethod(action.method, item)"
          >
            <v-icon small v-text="action.icon" />
          </v-btn>
        </template>
      </data-table>
    </base-material-card>
    <detail ref="detail" :item="activeItem" />
    <div class="py-3" />
  </v-container>
</template>

<script>
  import Detail from './Detail'
  import DataTable from '@/components/dashboard/DataTable'
  export default {
    name: 'Index',
    components: {
      DataTable,
      Detail,
    },
    data: () => ({
      attributes: [],
      search: null,
      dialog: false,
      activeItem: null,
      headers: [
        {
          text: 'Название',
          value: 'label',
        },
        {
          text: 'Учитывается при создания рейтинга',
          value: 'use_in_rating',
        },
        {
          text: 'Порядок',
          value: 'order',
        },
        {
          sortable: false,
          text: 'Действия',
          value: 'actions',
          align: 'right',
        },
      ],
      actions: [
        {
          color: 'info',
          icon: 'mdi-eye',
          can: 'view',
          method: 'viewItem',
        },
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
      searchParams: {
        query_search: '',
      },
    }),
    created () {
      this.fetchAttributes()
    },
    methods: {
      actionMethod (funcName, item) {
        this[funcName](item)
      },
      fetchAttributes () {
        this.axios.get('check-attributes')
          .then(({ data }) => {
            this.attributes = data.data
          })
      },
      deleteItem (item) {
        this.axios.delete(`check-attributes/${item.id}`)
          .then(({ data }) => {
            this.fetchAttributes()
            this.$store.commit('successMessage', data.message)
          })
          .catch(error => {
            this.$store.commit('errorMessage', error)
          })
      },
      editItem (item) {
        this.$router.push({ name: 'update-attributes', params: { id: item.id } })
      },
      viewItem (item) {
        this.activeItem = item
        this.$refs.detail.dialog = true
      },
    },
  }
</script>
