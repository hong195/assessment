<template>
  <v-row justify="center">
    <v-dialog
      v-model="dialog"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
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
            <v-col v-if="activeAssessment" md="12" xl="2" offset-md="1" class="mr-auto">
              <h3>Список чеков</h3>
              <v-list v-if="assessments.length">
                <v-select v-model="activeAssessmentId" :items="assessments" item-text="check.service_date"
                          item-value="id"
                />
                <div class="row">
                  <div class="col col-12">
                    <strong>Дата чека:</strong> {{ formatCreationDate(activeAssessment.check.service_date, 'LL') }}
                  </div>
                  <div class="col col-12">
                    <strong>Сумма:</strong> {{ formatPrice(activeAssessment.check.amount) }} сум.
                  </div>
                </div>
                <v-row v-if="canManage" v-show="assessmentsCount < 10" class="my-3" justify="space-between">
                  <v-btn small :to="updateAssessmentRouteParam">
                    Редактировать проверку
                  </v-btn>
                  <v-btn small :to="addAssessmentRouteParams" color="success">
                    Добавить проверку
                  </v-btn>
                </v-row>
              </v-list>
              <div v-else class="mt-5">
                Нет добавленных проверок
              </div>
            </v-col>
            <v-col md="12" xl="5">
              <h3>Информация о чеке</h3>
              <v-list v-if="assessments.length">
                <template v-if="activeAssessment && activeAssessment.criteria.length">
                  <v-list-item v-for="(criteria, index) in activeAssessment.criteria"
                               :key="activeAssessment.id + index"
                  >
                    <v-list-item-content>
                      <v-list-item-title style="white-space: pre-wrap">
                        {{ criteria }}
                        {{ index + 1 }}. {{ criteria.label }}
                      </v-list-item-title>
                      <v-row>
                        <v-col v-for="(option) in criteria.options"
                               :key="`option-${criteria.name}-${option.name}-${option.value}`"
                        >
                          <v-radio-group
                            :value="criteria.selectedValue"
                            column
                          >
                            <v-radio :value="option.value" :label="option.name" disabled />
                          </v-radio-group>
                        </v-col>
                        <v-col v-show="criteria.description" cols="12">
                          <v-text-field :value="criteria.description" readonly disabled outlined
                                        label="Примечание"
                          />
                        </v-col>
                      </v-row>
                    </v-list-item-content>
                  </v-list-item>
                </template>
              </v-list>
              <div v-else class="mt-5">
                Нет добавленных проверок
              </div>
            </v-col>
            <v-col md="12" xl="3">
              <h3>Итоговый рейтинг</h3>
              <final-grade-total-info :final-grade="finalGrade" />
            </v-col>
          </v-row>
        </v-list>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
  import moment from 'moment'
  import FinalGradeTotalInfo from './FinalGradeTotalInfo'
  import canManage from '@/mixins/RoleMixin'
  import CurrencyFormat from '@/components/dashboard/mixins/CurrencyFormat'

  export default {
    name: 'SingleUserRating',
    components: {
      FinalGradeTotalInfo,
    },
    mixins: [canManage, CurrencyFormat],
    props: {
      finalGrade: {
        type: Object,
        default: () => {
        },
      },
    },
    data: () => ({
      dialog: false,
      activeAssessmentId: null,
    }),
    computed: {
      updateAssessmentRouteParam () {
        return {
          name: 'final-grades-update-assessment',
          params: {
            finalGradeId: this.finalGrade.id,
            assessmentId: this.activeAssessment.id,
          },
        }
      },
      addAssessmentRouteParams () {
        return {
          name: 'final-grades-create-assessments',
          params: {
            finalGradeId: this.finalGrade.id,
          },
        }
      },
      assessments () {
        if (!this.finalGrade) {
          return []
        }
        return this.finalGrade.assessments
      },
      assessmentsCount () {
        return this.assessments.length
      },
      activeAssessment () {
        if (!this.assessments.length) {
          return {}
        }
        if (!this.activeAssessmentId) {
          return this.assessments[0]
        }
        return this.assessments.find(el => {
          return el.id === this.activeAssessmentId
        })
      },
    },
    watch: {
      finalGrade () {
        this.activeAssessmentId = this.activeAssessment?.id
      },
    },
    methods: {
      openModal () {
        this.dialog = true
      },
      closeDialog () {
        this.dialog = false
      },
      formatCreationDate (date, format = 'MMMM YYYY') {
        return moment(date).locale(this.$i18n.locale).format(format)
      },
      setActiveAssessment (check) {
        this.activeAssessment = check
      },
    },
  }
</script>

<style lang="scss">
.rating-info {
  .v-list-item__title {
    font-size: 1.2rem;
  }

  .v-list-item {
    font-size: 1.1rem;
    min-height: 50px !important;
  }

  .main-content {
    padding: 20px;
  }
}
</style>
