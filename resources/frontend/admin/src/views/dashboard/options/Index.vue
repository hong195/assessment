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
        fetch-url="check-attribute-option"
        :headers="headers"
        :search-options="searchParams"
        class="options-table"
      >
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
    <div class="py-3" />
  </v-container>
</template>

<script>
  import DataTable from '@/components/dashboard/DataTable'
  export default {
    name: 'Index',
    components: { DataTable },
    data: () => ({
      options: [],
      dialog: false,
      headers: [
        {
          text: 'Название',
          value: 'label',
        },
        {
          text: 'Слаг',
          value: 'name',
        },
        {
          text: 'Атрибут',
          value: 'attribute.label',
        },
        {
          text: 'Значение',
          value: 'value',
        },
        {
          text: 'Примечание',
          value: 'description',
          sortable: false,
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
      searchParams: {},
    }),
    methods: {
      actionMethod (funcName, item) {
        this[funcName](item)
      },
      editItem (item) {
        this.$router.push({ name: 'update-attribute-options', params: { id: item.id } })
      },
      deleteItem (item) {
        this.axios.delete(`check-attribute-option/${item.id}`)
          .then(({ data }) => {
            this.options.splice(
              this.options.findIndex(({ id }) => id === item.id),
              1,
            )
            this.$store.commit('successMessage', data.message)
          })
          .catch(error => {
            this.$store.commit('errorMessage', error)
          })
      },
    },
  }
</script>
<style  lang="scss">
.options-table {
  .description {
    max-width: 350px;
  }
  .value {
    min-width: 100px;
    max-width: 100px;
  }
  .attribute.label {
    max-width: 350px;
  }
}
</style>
