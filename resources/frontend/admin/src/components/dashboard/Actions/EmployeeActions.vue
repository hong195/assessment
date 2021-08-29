<template>
  <div>
    <template v-for="(action, i) in actions">
      <v-btn
        v-if="canManage"
        :key="i"
        dark
        class="px-2 ml-1"
        :color="action.color"
        min-width="0"
        small
        @click="actionMethod(action.method)"
      >
        <v-icon small v-text="action.icon" />
      </v-btn>
    </template>
  </div>
</template>

<script>
  import canManage from '@/mixins/RoleMixin'

  import { mapActions } from 'vuex'
  export default {
    name: 'EmployeeActions',
    mixins: [canManage],
    props: {
      item: {
        type: Object,
        default: () => ({}),
      },
    },
    data () {
      return {
        activeItem: {},
        actions: [
          {
            color: 'success',
            icon: 'mdi-pencil',
            can: 'update',
            method: 'editItem',
          },
          {
            color: 'error',
            icon: 'mdi-close',
            can: 'delete',
            method: 'deleteItem',
          },
        ],
      }
    },
    methods: {
      ...mapActions('employee', ['removeEmployee']),
      actionMethod (funcName, item) {
        this[funcName](item)
      },
      editItem () {
        this.$router.push({
          name: 'update-employee',
          params: {
            employeeId: this.item.id,
            pharmacyId: this.item.pharmacy_id,
          },
        })
      },
      deleteItem () {
        this.removeEmployee(this.item.id)
          .then((response) => {
            this.$emit('actionDeletedResponse', this.item.id)
            this.$store.commit('successMessage', response.data.message)
          })
          .catch(error => {
            this.$store.commit('errorMessage', error)
          })
      },
    },
  }
</script>
