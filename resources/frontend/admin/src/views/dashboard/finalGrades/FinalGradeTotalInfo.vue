<template>
  <v-list class="final-grade-info">
    <v-list-item>
      <v-list-item-title>
        <v-row>
          <v-col>Дата создания рейтинга:</v-col>
          <v-col class="font-weight-medium creation_date">
            {{ formattedDate(finalGrade.month) }}
          </v-col>
        </v-row>
      </v-list-item-title>
    </v-list-item>
    <v-list-item>
      <v-list-item-title>
        <v-row>
          <v-col>Набранно баллов:</v-col>
          <v-col class="font-weight-medium">
            {{ finalGrade.scored }}
          </v-col>
        </v-row>
      </v-list-item-title>
    </v-list-item>
    <v-list-item>
      <v-list-item-title>
        <v-row>
          <v-col>Общая сумма обслуживания:</v-col>
          <v-col class="font-weight-medium">
            {{ finalGrade.total_amount }}
          </v-col>
        </v-row>
      </v-list-item-title>
    </v-list-item>
    <v-list-item>
      <v-list-item-title>
        <v-row>
          <v-col>Общая сумма конверсии :</v-col>
          <v-col class="font-weight-medium">
            {{ finalGrade.total_sale_conversion }}
          </v-col>
        </v-row>
      </v-list-item-title>
    </v-list-item>
    <v-list-item>
      <v-list-item-title>
        <v-row>
          <v-col>Максимальное кол-во баллов:</v-col>
          <v-col class="font-weight-medium">
            {{ finalGrade.total }}
          </v-col>
        </v-row>
      </v-list-item-title>
    </v-list-item>
    <v-list-item v-if="reviewersName.length && isAdmin">
      <v-list-item-title>
        <v-row>
          <v-col>
            <span v-if="reviewersName.length > 1">
              Провели оценку
            </span>
            <span v-else>
              Провел(а) оценку
            </span>
          </v-col>
          <v-col class="font-weight-medium">
            {{ reviewersName.join() }}
          </v-col>
        </v-row>
      </v-list-item-title>
    </v-list-item>
  </v-list>
</template>

<script>
  import moment from 'moment'

  export default {
    name: 'RatingTotalInfo',
    props: {
      finalGrade: {
        type: Object,
        default: () => ({}),
      },
      reviewersName: {
        type: Array,
        default: () => [],
      },
    },
    computed: {
      isAdmin () {
        return true
      },
    },
    methods: {
      formattedDate (date) {
        return moment(date).locale(this.$i18n.locale).format('MMMM Y')
      },
    },
  }
</script>

<style lang="scss">
  .creation_date {
    text-transform: capitalize;
  }
  .final-grade-info {
    .col {
      font-size: 15px;
    }
  }
</style>
