<template>
  <div>
    <v-btn
      v-for="(action, i) in actions"
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
    <staff-detail ref="staffDetail" :item="item" />
  </div>
</template>

<script>
  import can from '@/plugins/directives/v-can'

  import { mapActions } from 'vuex'
  export default {
    name: 'EmployeeActions',
    directives: {
      can,
    },
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
          name: 'update-staff',
          params: { id: this.item.id },
        })
      },
      viewItem () {
        this.activeItem = this.item
        this.$refs.staffDetail.dialog = true
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
