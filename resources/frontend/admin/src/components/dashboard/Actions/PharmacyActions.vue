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
    <pharmacy-detail ref="pharmacyDetail" :pharmacy="activePharmacy" />
  </div>
</template>

<script>
  import can from '@/plugins/directives/v-can'
  import PharmacyDetail from '@/views/dashboard/pharmacies/Detail'
  import { mapActions } from 'vuex'
  import canManage from '@/mixins/RoleMixin'
  export default {
    name: 'Actions',
    components: { PharmacyDetail },
    mixins: [canManage],
    props: {
      pharmacy: {
        type: Object,
        default: () => ({}),
      },
    },
    data () {
      return {
        activePharmacy: {},
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
            method: 'deletePharmacy',
          },
        ],
      }
    },
    methods: {
      ...mapActions('pharmacy', ['removePharmacy']),
      actionMethod (funcName, item) {
        this[funcName](item)
      },
      viewItem () {
        this.activePharmacy = this.pharmacy
        this.$refs.pharmacyDetail.dialog = true
      },
      editItem () {
        this.$router.push({
          name: 'update-pharmacy',
          params: { id: this.pharmacy.id },
        })
      },
      deletePharmacy () {
        this.removePharmacy(this.pharmacy.id)
          .then((response) => {
            this.$emit('deleted-pharmacy', this.pharmacy.id)
            this.$store.commit('successMessage', 'Аптека удаленна')
          })
          .catch(error => {
            this.$store.commit('errorMessage', error)
          })
      },
    },
  }
</script>
