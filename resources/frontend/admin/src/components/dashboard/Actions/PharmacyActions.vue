<template>
  <div>
    <v-btn
      v-for="(action, i) in actions"
      :key="i"
      v-can="action.can"
      dark
      class="px-2 ml-1"
      :color="action.color"
      min-width="0"
      small
      @click="actionMethod(action.method)"
    >
      <v-icon small v-text="action.icon" />
    </v-btn>
    <pharmacy-detail ref="pharmacyDetail" :item="item" />
  </div>
</template>

<script>
  import can from '@/plugins/directives/v-can'
  import PharmacyDetail from '@/views/dashboard/pharmacies/Detail'

  export default {
    name: 'Actions',
    components: { PharmacyDetail },
    directives: {
      can: can,
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
            color: 'info',
            icon: 'mdi-eye',
            can: 'read',
            method: 'viewItem',
          },
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
      actionMethod (funcName, item) {
        this[funcName](item)
      },
      viewItem () {
        this.activeItem = this.item
        this.$refs.pharmacyDetail.dialog = true
        this.$refs.pharmacyDetail.getUsers()
      },
      editItem () {
        this.$router.push({
          name: 'update-pharmacy',
          params: { id: this.item.id },
        })
      },
      deleteItem () {
        this.$http
          .delete(`pharmacies/${this.item.id}`)
          .then((response) => {
            this.$emit('actionDeletedResponse', this.item.id)
            this.$store.commit('successMessage', response.data.message)
          })
          .catch(error => {
            console.error(error)
            this.$store.commit('errorMessage', error)
          })
      },
    },
  }
</script>
