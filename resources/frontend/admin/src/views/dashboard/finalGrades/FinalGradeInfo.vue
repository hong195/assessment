<template>
  <v-simple-table>
    <template v-slot:default>
      <tbody>
        <tr>
          <td>Месяц</td>
          <td>{{ finalGrade.month }}</td>
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
  }
</script>

<style scoped>

</style>
