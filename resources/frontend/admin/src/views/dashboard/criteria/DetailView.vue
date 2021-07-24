<template>
  <div v-if="criterion" class="text-center">
    <v-dialog
      v-model="dialog"
      max-width="600"
    >
      <v-card>
        <v-card-title>
          <span class="display-2">Информация о критерии</span>
          <v-spacer />
          <v-icon @click="dialog=false">
            mdi-close
          </v-icon>
        </v-card-title>

        <v-card-text>
          <table class="table-detail">
            <tr>
              <td>
                Название Критерия
              </td>
              <td>
                {{ criterion.name }}
              </td>
            </tr>
          </table>
          <h4 class="my-4">
            Опции
          </h4>
          <v-data-table :headers="optionHeaders" :items="options">
            <template v-slot:item.actions="{ item }">
              <v-btn small :icon="true"
                     color="success"
                     dark
                     @click="update(item)"
              >
                <v-icon small>
                  mdi-pencil
                </v-icon>
              </v-btn>
              <v-btn small :icon="true"
                     color="red"
                     :disabled="(item.status === 'completed')"
                     @click="remove(item)"
              >
                <v-icon small>
                  mdi-delete
                </v-icon>
              </v-btn>
            </template>
          </v-data-table>
        </v-card-text>

        <v-divider />

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="primary"
            text
            @click="openOptionModalDialog"
          >
            Добавить опцию
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <create-option-popup :id="criterion.id"
                         ref="optionFormDialog"
                         @option-added="reFetchOptions"
    />
    <update-option-popup
      v-if="activeOption"
      ref="optionUpdateFormDialog"
      :criteria-id="criterion.id"
      :option-id="activeOption.id"
      @option-updated="reFetchOptions"
    />
  </div>
</template>

<script>
  import CreateOptionPopup from './CreateOption'
  import UpdateOptionPopup from './UpdateOption'
  import { mapActions } from 'vuex'
  export default {
    name: 'DetailView',
    components: { CreateOptionPopup, UpdateOptionPopup },
    props: {
      criterion: {
        type: Object,
        default: () => {},
      },
    },
    data () {
      return {
        dialog: false,
        optionHeaders: [
          {
            text: 'Название',
            value: 'name',
          },
          {
            text: 'Значение',
            value: 'value',
          },
          {
            text: 'Действия',
            value: 'actions',
          },
        ],
        options: [],
        activeOption: {},
      }
    },
    watch: {
      criterion (newVal) {
        this.fetchOptions(newVal)
      },
    },
    methods: {
      ...mapActions('criterion', ['fetchCriterionOption', 'deleteOption']),
      openModal () {
        this.dialog = true
      },
      openOptionModalDialog () {
        this.$refs.optionFormDialog.openPopupForm()
      },
      fetchOptions (criterion) {
        this.fetchCriterionOption({ criterionId: criterion.id })
          .then(({ data }) => {
            this.options = data
          })
      },
      reFetchOptions () {
        this.fetchOptions(this.criterion)
      },
      update (option) {
        this.activeOption = option
        this.$refs.optionUpdateFormDialog.openPopupForm()
      },
      remove (option) {
        this.deleteOption({
          criterionId: this.criterion.id,
          optionId: option.id,
        })
          .then(({ data }) => {
            this.$store.commit('successMessage', data.message)
            this.fetchOptions(this.criterion)
          })
      },
    },
  }
</script>
<style lang="scss">
.table-detail{
  width: 100%;
  td{
    padding: 10px 0;
    border-bottom: 1px solid #c5c5c5;
    span.meta{
      color: rgba(0, 0, 0, 0.6);
    }
  }
  td:nth-child(2){
    color: #1a1a1a;
    font-size: 16px;
  }
  tr:last-child{
    td{
      border-bottom: none;
    }
  }
}
</style>
