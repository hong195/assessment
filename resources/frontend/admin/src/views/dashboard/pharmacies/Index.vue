<template>
  <v-container id="extended-tables" fluid tag="section">
    <base-material-card
      color="success"
      icon="mdi-clipboard-text"
      inline
      title="Список аптек с рейтингом"
      class="px-5 py-3 my-6"
    >
      <data-table
        ref="data-table"
        fetch-url="pharmacies"
        :headers="headers"
        :search-options="searchParams"
      >
        <template v-slot:item.actions="{ item }">
          <actions :item="item" @actionDeletedResponse="actionDeletedResponse" />
        </template>
        <template v-slot:item.address="{ item }">
          <a v-if="item.coordinates" :href="`http://www.google.com/maps/place/${item.coordinates[1]},${item.coordinates[0]}`"
             target="_blank"
             v-text="item.address"
          />
          <div v-else>
            {{ item.address }}
          </div>
        </template>
      </data-table>
    </base-material-card>
    <div class="py-3" />
  </v-container>
</template>

<script>
  import can from '@/plugins/directives/v-can'
  import Actions from '@/components/dashboard/Actions/PharmacyActions'
  import DataTable from '@/components/dashboard/DataTable'
  export default {
    name: 'Pharmacy',
    components: { DataTable, Actions },
    directives: {
      can: can,
    },
    data: () => ({
      headers: [
        {
          text: 'Название аптеки',
          value: 'name',
        },
        {
          text: 'Количество сотрудников',
          value: 'users_count',
        },
        {
          text: 'Электронная почта',
          value: 'email',
          sortable: false,
        },
        {
          text: 'Адрес аптеки',
          value: 'address',
          sortable: false,
        },
        {
          text: '**Действия',
          value: 'actions',
          align: 'right',
          sortable: false,
        },
      ],
      searchParams: {
        name: '',
        with: 'users',
      },
    }),
    methods: {
      actionDeletedResponse (val) {
        this.items.splice(
          this.items.findIndex(({ id }) => id === val),
          1,
        )
      },
    },
  }
</script>
