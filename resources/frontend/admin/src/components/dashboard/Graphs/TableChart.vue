<template>
  <div>
    <div class="d-flex">
      <v-spacer />
      <v-btn outlined large class="mr-3" @click="sortRatings">
        Сортировать рейтинги <v-icon>
          {{ asc ? 'mdi-menu-up' : 'mdi-menu-down' }}
        </v-icon>
      </v-btn>
      <export-to-pdf :excel-data="excelData" :max="items[0].rating.out_of" :date="date" />
    </div>
    <v-data-table
      :items="items"
      :headers="headers"
      :loading="isLoading"
      :disable-sort="true"
      :single-expand="true"
      item-key="name"
      :expanded.sync="expanded"
      show-expand
    >
      <template v-slot:item.rating="{ item }">
        <rating-score :rating="item.rating" />
      </template>
      <template v-slot:expanded-item="{ headers, item }">
        <td class="pa-3" :colspan="headers.length">
          <users-rating-by-pharmacy :ratings="item.ratings" :users="item.users" />
        </td>
      </template>
      <template v-slot:item.rating.conversion="{ item }">
        <conversion :conversion="item.rating.conversion" />
      </template>
    </v-data-table>
  </div>
</template>

<script>
  import ExportToPdf from '@/components/dashboard/ExportToPdf'
  import UsersRatingByPharmacy from '@/components/dashboard/Graphs/table_parts/UsersRatingByPharmacy'
  import RatingScore from '@/components/dashboard/Graphs/table_parts/RatingScore'
  import Conversion from '@/components/dashboard/Graphs/table_parts/Conversion'
  export default {
    name: 'TableChart',
    components: { Conversion, RatingScore, UsersRatingByPharmacy, ExportToPdf },
    props: {
      items: {
        type: Array,
        default: () => ([]),
      },
      isLoading: {
        type: Boolean,
      },
      date: {
        type: String,
      },
    },
    data () {
      return {
        asc: 0,
        headers: [
          {
            text: 'Аптека',
            value: 'name',
            width: '450',
          },
          {
            text: 'Рейтинг',
            value: 'rating',
            width: '300',
          },
          {
            text: 'Конверсия',
            value: 'rating.conversion',
          },
          { text: '', value: 'data-table-expand' },
        ],
        expanded: [],

      }
    },
    computed: {
      excelData () {
        var copy = []
        let arr = []
        this.items.forEach((item) => {
          item.ratings.forEach((el, key) => {
            arr = []
            arr.length = item.ratings.length
            arr.pharmacy = item.name
            arr.user = el.user.last_name + ' ' + el.user.first_name
            arr.index = key + 1
            arr.scored = el.scored
            copy.push(arr)
          })
        })
        return copy
      },
    },
    methods: {
      sortRatings () {
        this.asc = this.asc ? 0 : 1
        if (this.asc === 0) {
          this.items = this.items.sort((a, b) => (a.rating.scored > b.rating.scored) ? 1 : ((b.rating.scored > a.rating.scored) ? -1 : 0))
        } else {
          this.items = this.items.sort((a, b) => (a.rating.scored < b.rating.scored) ? 1 : ((b.rating.scored < a.rating.scored) ? -1 : 0))
        }
      },
    },
  }
</script>
