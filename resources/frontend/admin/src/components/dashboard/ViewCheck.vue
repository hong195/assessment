<template>
  <div>
    <v-list v-if="activeCheck" dense>
      <v-list-item v-for="(criterion, index) in criteriaList" :key="index">
        <v-list-item-content v-if="criterion.use_in_rating === 1">
          <v-list-item-title>
            {{ lastIndex(index) }}.
            {{ criterion.label }}
          </v-list-item-title>
          <v-row no-gutters>
            <v-col v-for="(option) in criterion.options" :key="`option-${option.id}`">
              <v-radio-group
                :value="option.selected ? option.label : '1'"
                column
                hide-details
              >
                <v-radio :value="option.label" :label="option.label" readonly disabled />
              </v-radio-group>
              <div v-if="option.description" class="grey--text mb-2 caption ml-2">
                {{ option.description }}
              </div>
            </v-col>
            <v-col v-if="criterion.notice" cols="12" class="mt-3 mb-3">
              <v-text-field label="Примечание:" :value="criterion.notice" outlined :disabled="true" />
            </v-col>
          </v-row>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </div>
</template>
<script>
  export default {
    name: 'ViewCheck',
    props: {
      activeCheck: {
        type: [Object, Array],
        default: () => ({}),
      },
    },
    data () {
      return {
        criteriaIndex: 0,
      }
    },
    computed: {
      criteriaList () {
        let { criteria = [] } = this.activeCheck

        criteria = Object.values(criteria)
          .filter(key => {
            return key.use_in_rating
          })

        return Object.values(criteria).sort((a, b) => (a.order > b.order) ? 1 : ((b.order > a.order) ? -1 : 0))
      },
    },
    methods: {
      lastIndex (current) {
        return parseInt(current) + 1
      },
    },
  }
</script>
