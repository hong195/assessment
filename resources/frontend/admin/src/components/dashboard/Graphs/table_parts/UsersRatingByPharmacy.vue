<template>
  <v-simple-table>
    <template v-slot:default>
      <tbody>
        <tr v-for="(employee, index) in pharmacy.employees" :key="`${employee.id}-index`">
          <td width="15" v-html="index+1" />
          <td width="400" v-html="employee.name" />
          <td width="300">
            <rating-score v-if="employee.final_grade"
                          :rating="employee.final_grade"
            />
            <span v-else>Нет рейтинга</span>
          </td>
          <td>
            <v-btn
              :color="getColor(employee.final_grade.conversion)"
              rounded
              class="rating__btn"
              depressed
            >
              <span style="color: #fff;">
                {{ employee.final_grade.conversion ? employee.final_grade.conversion : 0 }} %
              </span>
            </v-btn>
          </td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>
<script>
  import RatingScore from './RatingScore'
  import FinalGradeColor from '../../mixins/FinalGradeColor'

  export default {
    name: 'UsersRatingByPharmacy',
    components: {
      RatingScore,
    },
    mixins: [FinalGradeColor],
    props: {
      pharmacy: {
        type: [Array, Object],
      },
    },
  }
</script>
