<template>
  <div>
    <div class="d-flex">
      <v-spacer />
      <v-btn outlined large class="mr-3" @click="sortRatings">
        Сортировать рейтинги <v-icon>
          {{ asc ? 'mdi-menu-up' : 'mdi-menu-down' }}
        </v-icon>
      </v-btn>
      <!--      <export-to-pdf :excel-data="excelData" :max="items[0].final_grades.total" :date="date" />-->
    </div>
    <v-data-table
      :items="items"
      :headers="headers"
      :loading="isLoading"
      :disable-sort="true"
      :single-expand="true"
      item-key="number"
      :expanded.sync="expanded"
      show-expand
    >
      <template v-slot:item.rating="{ item }">
        <rating-score :rating="item.employees[0].final_grade" />
      </template>
      <template v-slot:item.conversion="{ item }">
        <v-btn
          :color="getColor(item.employees[0].final_grade.conversion)"
          rounded
          class="rating__btn"
          depressed
        >
          <span style="color: #fff;">
            {{ item.employees[0].final_grade.conversion ?
              item.employees[0].final_grade.conversion : 0 }} %
          </span>
        </v-btn>
      </template>
      <template v-slot:expanded-item="{ headers, item }">
        <td class="pa-3" :colspan="headers.length">
          <users-rating-by-pharmacy :pharmacy="item" />
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
  import ExportToPdf from '@/components/dashboard/ExportToPdf'
  import UsersRatingByPharmacy from '@/components/dashboard/Graphs/table_parts/UsersRatingByPharmacy'
  import RatingScore from '@/components/dashboard/Graphs/table_parts/RatingScore'
  import FinalGradeColor from '../mixins/FinalGradeColor'

  export default {
    name: 'TableChart',
    components: { RatingScore, UsersRatingByPharmacy, ExportToPdf },
    mixins: [FinalGradeColor],
    props: {
      items: {
        type: Array,
        default: () => [],
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
            value: 'number',
            width: '450',
          },
          {
            text: 'Рейтинг',
            value: 'rating',
            width: '300',
          },
          {
            text: 'Конверсия',
            value: 'conversion',
          },
          { text: '', value: 'data-table-expand' },
        ],
        expanded: [],

      }
    },
    computed: {
      excelData () {
        var copy = []
        const arr = []
        // this.items.forEach((item) => {
        //   item.employees.forEach((el, key, arr2) => {
        //     el.final_grade.forEach((el2, key, arr2) => {
        //       arr = []
        //       arr.length = arr2.length
        //       arr.pharmacy = item.name
        //       arr.user = el.employee.name
        //       arr.index = key + 1
        //       arr.scored = el.scored
        //       copy.push(arr)
        //     })
        //   })
        // })
        return copy
      },
    },
    methods: {
      sortRatings () {
        this.asc = this.asc ? 0 : 1
        if (this.asc === 0) {
          this.items = this.items.sort((a, b) => (a.employees[0].final_grade.scored > b.employees[0].scored) ? 1 : ((b.employees[0].scored > a.employees[0].scored) ? -1 : 0))
        } else {
          this.items = this.items.sort((a, b) => (a.employees[0].scored < b.employees[0].scored) ? 1 : ((b.employees[0].scored < a.employees[0].scored) ? -1 : 0))
        }
      },
    },
  }
</script>
