<template>
  <div>
    <v-data-table :headers="headers" :items="items" class="assessment-list">
      <template v-slot:item.index="{ item }">
        {{ getElementIndex(item) }}
      </template>
      <template v-slot:item.actions="{ item }">
        <v-btn small :icon="true"
               color="red"
               :disabled="(assessmentsCount === 10)"
               @click="remove(item)"
        >
          <v-icon>mdi-delete</v-icon>
        </v-btn>
        <v-btn small :icon="true"
               color="primary"
               :disabled="(assessmentsCount === 10)"
               @click="update(item)"
        >
          <v-icon>mdi-pencil</v-icon>
        </v-btn>
      </template>
    </v-data-table>
    <v-container>
      <v-row justify="center" style="padding: 20px 0;">
        <v-btn v-show="assessmentsCount < 10" color="primary"
               :to="{
                 name: 'final-grades-assessments',
                 params: {
                   id: item.id,
                 }
               }"
        >
          Добавить Оценку Сотрудника
        </v-btn>
      </v-row>
    </v-container>
  </div>
</template>

<script>
  export default {
    name: 'AssessmentTable',
    props: {
      items: {
        type: Array,
        default: () => ([]),
      },
      item: {
        type: Object,
        required: true,
      },
    },
    data () {
      return {
        headers: [
          {
            text: '№',
            value: 'index',
          },
          {
            text: 'Сумма Обслуживания',
            value: 'check.amount',
          },
          {
            text: 'Конверсия Обслуживания',
            value: 'check.conversion',
          },
          {
            text: 'Дата обсулуживания',
            value: 'check.service_date',
          },
          {
            text: 'Набранный бал',
            value: 'scored',
          },
          {
            text: '',
            value: 'actions',
          },
        ],

      }
    },
    computed: {
      assessmentsCount () {
        return this.items.length
      },
    },
    methods: {
      update (assessment) {
        this.$router.push({
          name: 'final-grades-update-assessment',
          params: {
            finalGradeId: this.item.id,
            assessmentId: assessment.id,
          },
        })
      },
      getElementIndex (element) {
        if (!this.items.length) {
          return
        }
        return this.items.findIndex(item => element.id === item.id) + 1
      },
      remove (assessment) {
        console.log(assessment)
      },
    },
  }
</script>

<style scoped lang="scss">
.assessment-list {
  .v-data-footer {
  &__select, &__pagination, &__icons-before, &__icons-after {
        display: none;
      }
    }
}
</style>
