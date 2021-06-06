<template>
  <v-dialog
    v-model="dialog"
    max-width="700px"
  >
    <v-card v-if="item">
      <v-card-title>
        <span class="display-2">Подробная информация</span>
        <v-spacer />
        <v-icon @click="dialog=false">
          mdi-close
        </v-icon>
      </v-card-title>
      <v-card-text>
        <table class="table-detail">
          <tr>
            <td>
              Название
            </td>
            <td>
              {{ item.label }}
            </td>
          </tr>
          <tr>
            <td>
              Слаг
            </td>
            <td>
              {{ item.name }}
            </td>
          </tr>
          <tr>
            <td>
              Тип
            </td>
            <td>
              {{ item.type === 'radio' ? 'Радиокнопка' : 'Текстовое поле' }}
            </td>
          </tr>
          <tr>
            <td>
              Учитывается ли при создании рейтинга
            </td>
            <td>
              {{ item.use_in_rating }}
            </td>
          </tr>
          <tr>
            <td>
              Порядок
            </td>
            <td>
              {{ item.order }}
            </td>
          </tr>
          <tr v-if="item.options && item.options.length">
            <td>
              Опции
            </td>
            <td>
              <div v-for="option in item.options" :key="option.name">
                <span class="meta">{{ option.label }} :</span> {{ option.value }}
                {{ option.value > 1 ? 'баллов' : 'балл' }}
              </div>
            </td>
          </tr>
        </table>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>
<script>
  export default {
    name: 'AttributeDetail',
    props: {
      item: {
        type: Object,
        default: () => ({}),
      },
    },
    data () {
      return {
        dialog: false,
      }
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
  .option-action {
    display:flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 5px;
  }
}
</style>
