<template>
  <div style="display: flex; justify-content: center;">
    <v-tooltip v-if="rating.id" bottom>
      <template v-slot:activator="{ on, attrs }">
        <span
          v-bind="attrs"
          v-on="on"
        >
          <v-btn
            :color="getColor(rating.scored)"
            rounded
            class="rating__btn"
            depressed
            @click.prevent="getRating(rating)"
          >
            <span style="color: white;">{{ `${rating.scored}/${rating.total}` }}</span>
          </v-btn>
        </span>
      </template>
      <span>Нажмите, чтобы просмотреть подробную информацию о рейтинге</span>
    </v-tooltip>
    <div v-else style="margin-right: auto;">
      Нет Рейтинга
    </div>
    <view-final-grade-modal
      ref="finalGrade"
      :final-grade="rating"
    />
  </div>
</template>
<script>

  import FinalGradeColor from '../../mixins/FinalGradeColor'
  import ViewFinalGradeModal from '../../../../views/dashboard/finalGrades/ViewFinalGradeModal'
  export default {
    name: 'RatingScore',
    components: { ViewFinalGradeModal },
    mixins: [FinalGradeColor],
    props: {
      rating: {
        required: true,
        type: [Array, Object],
      },
    },
    data () {
      return {
        dialog: false,
        ratingId: null,
      }
    },
    methods: {
      getRating (rating) {
        this.rating = rating
        this.$refs.finalGrade.openModal()
      },
    },
  }
</script>
