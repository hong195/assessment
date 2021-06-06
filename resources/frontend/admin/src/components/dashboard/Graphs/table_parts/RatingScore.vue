<template>
  <div>
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
            @click.prevent="getRating(rating.id)"
          >
            <span style="color: white;">{{ `${rating.scored}/${rating.out_of}` }}</span>
          </v-btn>
        </span>
      </template>
      <span>Нажмите, чтобы просмотреть подробную информацию о рейтинге</span>
    </v-tooltip>
    <div v-else>
      Нет Рейтинга
    </div>
    <single-user-rating
      :show-dialog="dialog"
      :rating-id="ratingId"
      @close-dialog="dialog = false"
    />
  </div>
</template>
<script>
  import RatingColor from '@/components/dashboard/mixins/RatingColor'
  import SingleUserRating from '@/views/dashboard/ratings/SingleUserRating'

  export default {
    name: 'RatingScore',
    components: { SingleUserRating },
    mixins: [RatingColor],
    props: {
      rating: {
        required: true,
        type: Object,
      },
    },
    data () {
      return {
        dialog: false,
        ratingId: null,
      }
    },
    methods: {
      getRating (ratingId) {
        this.ratingId = ratingId
        this.dialog = true
      },
    },
  }
</script>
