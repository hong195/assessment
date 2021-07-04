<template>
  <div>
    <v-simple-table>
      <template v-slot:default>
        <tbody>
          <tr>
            <td>ФИО</td>
            <td>{{ employee.first_name }} {{ employee.middle_name }} {{ employee.last_name }}</td>
          </tr>
          <tr>
            <td>Дата Рождения</td>
            <td>{{ employee.birthdate }}</td>
          </tr>
          <tr>
            <td>Пол</td>
            <td>{{ employee.gender }}</td>
          </tr>
          <tr>
            <td>Аптека</td>
            <td>номер {{ pharmacy.number }}</td>
          </tr>
          <tr>
            <td>Электронная почта аптеки</td>
            <td>{{ pharmacy.email }}</td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
  </div>
</template>

<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'EmployeeInfo',
    props: {
      employee: {
        type: Object,
        required: true,
      },
      pharmacy: {
        type: Object,
        required: true,
      },
    },
  }
</script>

<style scoped>

</style>
