<template>
  <div v-if="excelData.length">
    <v-btn outlined large @click="tableToExcel">
      Скачать <v-icon color="green">
        mdi-file-excel
      </v-icon>
    </v-btn>
    <table ref="table" style="display: none">
      <thead>
        <th style="font-size: 12pt; height: 70px">
          №
        </th>
        <th style="font-size: 12pt;">
          {{ formatted }}
        </th>
        <th style="font-size: 12pt;">
          Аптека
        </th>
        <th style="font-size: 12pt;">
          Балл из {{ max }}
        </th>
      </thead>
      <tbody>
        <tr v-for="item in excelData" :key="item.user" style="font-size: 12pt" :style="item.index === 1 ? 'font-weight: bold': ''">
          <td :style="item.index === item.length ? 'border-bottom: 2px solid black' : ''">
            {{ item.index }}
          </td>
          <td :style="item.index === item.length ? 'border-bottom: 2px solid black' : ''">
            {{ item.user }}
          </td>
          <td :style="item.index === item.length ? 'border-bottom: 2px solid black' : ''">
            {{ item.pharmacy }}
          </td>
          <td :style="item.index === item.length ? 'border-bottom: 2px solid black' : ''">
            {{ item.scored }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
  import moment from 'moment'
  export default {
    props: {
      excelData: {
        type: Array,
      },
      max: {
        type: String,
      },
      date: {
        type: String,
      },
    },
    data () {
      return {
        uri: 'data:application/vnd.ms-excel;base64,',
        template: '<html><head>' +
          '<style> td {border: thin solid black }' + '</style>' +
          '</head><body><table style="border-collapse:collapse;">{table}</table></body></html>',
        format: function (s, c) {
          return s.replace(/{(\w+)}/g, function (m, p) {
            return c[p]
          })
        },
        base64: function (s) {
          return window.btoa(unescape(encodeURIComponent(s)))
        },
      }
    },
    computed: {
      formatted () {
        return moment(this.date).format('MMMM YYYY') + ' года'
      },
    },
    mounted () {
      moment.locale('ru')
    },
    methods: {
      tableToExcel () {
        const table = this.$refs.table
        var ctx = { worksheet: 'Anketa', table: table.innerHTML }
        var link = document.createElement('a')
        link.download = 'Шаблон_отчета_рейтинга_за_месяц.xls'
        link.href = this.uri + this.base64(this.format(this.template, ctx))

        link.click()
      },
    },
  }
</script>
