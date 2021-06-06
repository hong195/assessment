<template>
  <v-row justify="center">
    <v-dialog
      v-model="showDialog"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
      class="rating-info-wrapper"
    >
      <v-card class="rating-info">
        <v-toolbar
          dark
          color="primary"
        >
          <v-toolbar-title>Информация о рейтинге</v-toolbar-title>
          <v-spacer />
          <v-toolbar-items>
            <v-btn
              icon
              dark
              @click="closeDialog"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-list
          three-line
          subheader
        >
          <v-row class="main-content">
            <v-overlay :value="overlay" opacity="1" color="white">
              <v-progress-circular
                color="primary"
                indeterminate
                size="128"
              />
            </v-overlay>
            <v-col sm="12" xl="3">
              <h3 class="text-center">
                Список чеков
              </h3>

              <v-list v-if="activeCheck && rating && rating.checks">
                <v-select v-model="activeCheckId" :items="ratingChecks" item-text="name" item-value="id" />
                <v-row>
                  <v-col cols="12">
                    <strong>Дата чека:</strong> {{ formatCreationDate(activeCheck.created_at, 'LL , HH:mm') }}
                  </v-col>
                  <v-col v-if="activeCheck.sum" cols="12">
                    <strong>Сумма:</strong> {{ activeCheck.sum }} сум.
                  </v-col>
                  <v-col v-if="isAdmin && activeCheck.reviewer" cols="12">
                    <strong>Провел(а) оценку чека:</strong> {{ activeCheck.reviewer.full_name }}
                  </v-col>
                </v-row>
              </v-list>
            </v-col>
            <v-col sm="12" xl="6">
              <div style="margin: 0 50px;">
                <h3 class="text-center">
                  Информация о чеке
                </h3>
                <view-check :active-check="activeCheck" class="active-check" />
              </div>
            </v-col>
            <v-col sm="12" xl="3">
              <h3 class="text-center">
                Итоговый рейтинг
              </h3>
              <rating-total-info v-if="rating" :rating="rating" :reviewers-name="reviewersNames" />
            </v-col>
          </v-row>
        </v-list>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
  import moment from 'moment'
  import RatingTotalInfo from './RatingTotalInfo'
  import ViewCheck from '@/components/dashboard/ViewCheck'
  import { mapState } from 'vuex'

  export default {
    name: 'SingleUserRating',
    components: {
      RatingTotalInfo,
      ViewCheck,
    },
    props: {
      showDialog: {
        type: Boolean,
        default: false,
      },
      ratingId: {
        type: Number,
        default: 0,
      },
    },
    data: () => ({
      activeCheckId: null,
      rating: null,
      overlay: true,
    }),
    computed: {
      ...mapState({ isAdmin: state => state.user.isAdmin }),
      activeCheck () {
        if (!this.rating) {
          return
        }
        return this.ratingChecks.find((el) => {
          return el.id === this.activeCheckId
        })
      },
      ratingChecks () {
        if (!this.rating && (this.rating && this.rating.checks.length < 0)) {
          return []
        }
        return this.rating.checks.map((item, index) => {
          return {
            ...item,
            name: `Чек № ${index + 1}`,
            value: item.id,
          }
        })
      },
      reviewersNames () {
        const reviewersNames = []

        if (!this.rating) {
          return reviewersNames
        }

        this.ratingChecks.forEach((check) => {
          if (check.reviewer && !reviewersNames.includes(check.reviewer.full_name)) {
            reviewersNames.push(check.reviewer.full_name)
          }
        })

        return reviewersNames
      },
    },
    watch: {
      ratingId: {
        handler () {
          this.fetchRatingsChecks()
        },
      },
    },
    methods: {
      fetchRatingsChecks () {
        this.overlay = true
        this.axios.get(`ratings/${this.ratingId}`)
          .then(({ data }) => {
            this.rating = data.data
            this.activeCheckId = this.rating.checks[0] ? this.rating.checks[0].id : null
            this.overlay = false
          })
          .catch(() => {
            this.$store.commit('errorMessage', 'Рейтинг не найден')
            this.closeDialog()
          })
      },
      closeDialog () {
        this.$emit('close-dialog')
      },
      formatCreationDate (date, format = 'MMMM YYYY') {
        return moment(date).locale(this.$i18n.locale).format(format)
      },
    },
  }
</script>

<style lang="scss">
.rating-info {
  .v-list-item__title {
    font-size: 1.2rem;
    white-space: normal;
  }
  .v-list-item {
    font-size: 1.1rem;
    min-height: 50px !important;
  }
  .main-content {
    padding: 20px;
  }
  .active-check {
    .v-list-item__title {
      font-size: 1.2rem;
      line-height: 1.4rem;
    }
    .v-list {
      margin-top: 10px;
    }
  }
}
</style>
