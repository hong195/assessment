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

      <v-data-table :headers="headers" :items="criteria" show-expand :single-expand="true">
        <template v-slot:expanded-item="{ headers, item }">
          <td colspan="7" style="padding: 0">
            переместить в попап
            <v-container style="padding: 20px 0;">
              <v-data-table :headers="optionsHeaders" :items="item.options" class="options-list" />

              <v-row justify="center">
                <v-btn color="primary" @click="openCreateOptionForm(item)">
                  Добавить Опцию
                </v-btn>
              </v-row>
            </v-container>
          </td>
        </template>
      </v-data-table>

      <v-divider class="mt-3" />
    </base-material-card>
    <create-criterion-popup ref="createPopup" />
    <create-option-popup :id="activeCriterionId" ref="crateOptionPopup" />
  </v-container>
</template>

<script>
  import { mapActions } from 'vuex'
  import CreateCriterionPopup from './Create'
  import CreateOptionPopup from './CreateOption'
  export default {
    name: 'CriteriaList',
    components: { CreateCriterionPopup, CreateOptionPopup },
    data () {
      return {
        criteria: [],
        activeCriterionId: '',
        headers: [
          {
            text: 'Название критерия',
            value: 'name',
          },
          {
            text: 'Порядок',
            value: 'order',
          },
          {
            text: 'Дата создания',
            value: 'created_at',
          },
        ],
        optionsHeaders: [
          {
            text: 'Название опции',
            value: 'name',
          },
          {
            text: 'Значение',
            value: 'value',
          },
        ],
      }
    },
    mounted () {
      this.fetchAll()
        .then(({ data }) => {
          this.criteria = data.data
        })
    },
    methods: {
      ...mapActions('criterion', ['fetchAll']),
      openCreateForm (criterion) {
        this.$refs.createPopup.openPopupForm()
      },
      openCreateOptionForm (criterion) {
        this.activeCriterionId = criterion.id
        this.$refs.crateOptionPopup.openPopupForm()
      },
    },
  }
</script>
