<template>
  <v-container id="data-tables" tag="section">
    <base-v-component heading="Рейтинг сотрудников" />
    <base-material-card
      color="indigo"
      icon="mdi-vuetify"
      inline
      class="px-5 py-3"
    >
      <template v-slot:after-heading>
        <div class="display-2 font-weight-light">
          Итоговые оценки сотрдуников
        </div>
      </template>

      <v-row justify="end">
        <v-col>
          <v-btn color="primary" @click="openCreateForm()">
            Добавить
          </v-btn>
        </v-col>
      </v-row>

      <v-data-table :headers="headers" :items="criteria" :single-expand="true">
        <template v-slot:item.actions="{ item }">
          <v-btn small :icon="true"
                 color="info"
                 @click="view(item)"
          >
            <v-icon>mdi-eye</v-icon>
          </v-btn>
          <v-btn small :icon="true"
                 color="success"
                 @click="edit(item)"
          >
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-btn small :icon="true"
                 color="red"
                 :disabled="(item.status === 'completed')"
                 @click="remove(item)"
          >
            <v-icon>mdi-delete</v-icon>
          </v-btn>
        </template>
      </v-data-table>

      <v-divider class="mt-3" />
    </base-material-card>
    <create-criterion-popup ref="createPopup" @added-criterion="fetchCriteria" />
    <update-criterion-popup ref="updatePopup"
                            :criterion="activeCriterion"
                            @updated-criterion="fetchCriteria"
    />
    <detail-view ref="detailView" :criterion="activeCriterion" />
  </v-container>
</template>

<script>
  import { mapActions } from 'vuex'
  import CreateCriterionPopup from './Create'
  import DetailView from './DetailView'
  import UpdateCriterionPopup from './Update'
  export default {
    name: 'CriteriaList',
    components: { UpdateCriterionPopup, CreateCriterionPopup, DetailView },
    data () {
      return {
        criteria: [],
        activeCriterionId: '',
        activeCriterion: null,
        headers: [
          {
            text: 'Название критерия',
            value: 'label',
          },
          {
            text: 'Слаг',
            value: 'name',
          },
          {
            text: 'Порядок',
            value: 'order',
          },
          {
            text: 'Действия',
            value: 'actions',
          },
        ],
      }
    },
    mounted () {
      this.fetchCriteria()
    },
    methods: {
      ...mapActions('criterion', ['fetchAll', 'deleteCriterion', 'updateCriterion']),
      openCreateForm () {
        this.$refs.createPopup.openPopupForm()
      },
      fetchCriteria () {
        this.fetchAll()
          .then(({ data }) => {
            this.criteria = data.data
          })
      },
      openCreateOptionForm (criterion) {
        this.activeCriterionId = criterion.id
        this.$refs.crateOptionPopup.openPopupForm()
      },
      view (criterion) {
        this.activeCriterion = criterion
        this.$refs.detailView.openModal()
      },
      edit (criterion) {
        this.activeCriterion = criterion
        this.$refs.updatePopup.openPopupForm()
      },
      remove (criterion) {
        this.deleteCriterion({ criterionId: criterion.id })
          .then(() => {
            this.$store.commit('successMessage', 'Критерий удален')
            this.fetchCriteria()
          })
          .catch(() => {
            this.$store.commit('errorMessage', 'Ошибка удаления')
          })
      },
    },
  }
</script>
