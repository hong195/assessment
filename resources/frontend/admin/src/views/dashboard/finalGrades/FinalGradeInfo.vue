<template>
  <v-simple-table>
    <template v-slot:default>
      <tbody>
        <tr>
          <td>Месяц</td>
          <td style="text-transform: capitalize">
            {{ formattedMonth }}
          </td>
        </tr>
        <tr>
          <td>Количество проверок</td>
          <td>{{ finalGrade.assessments_count }} из 10</td>
        </tr>
        <tr>
          <td>Статус</td>
          <td>{{ $t(finalGrade.status) }}</td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>

<script>
  import moment from 'moment'
  export default {
    name: 'FinalGradeInfo',
    props: {
      finalGrade: {
        type: Object,
        required: true,
      },
      assessmentCount: {
        type: Number,
        required: false,
      },
    },
    computed: {
      formattedMonth () {
        return moment(this.finalGrade.month).locale('ru').format('MMMM YYYY')
      },
    },
  }
</script>

<style scoped>

</style>
