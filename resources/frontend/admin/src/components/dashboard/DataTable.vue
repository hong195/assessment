<template>
  <v-data-table
    :items="items"
    :headers="headers"
    :loading="loading"
    :server-items-length="total"
    @update:options="setOptions"
  >
    <template
      v-slot:body="data"
    >
      <tbody v-if="items.length">
        <template
          v-for="(item, index) in items"
        >
          <tr :key="`values-${index}`">
            <td
              v-for="(header,index2) in data.headers"
              :key="`${header.value}-${index2}`"
              :class="getHeaderClass(header)"
            >
              <slot
                v-if="header.value.includes('.')"
                :name="`item.${header.value}`"
                :item="item"
              >
                {{ getFromObject(item, header.value) }}
              </slot>
              <slot
                v-else-if="header.value !== 'data-table-expand'"
                :name="`item.${header.value}`"
                :item="item"
              >
                {{ item[header.value] }}
              </slot>
              <button
                v-if="header.value === 'data-table-expand'"
                type="button"
                :class="rowExpandedClass(item)"
                class="v-icon notranslate v-icon--link mdi mdi-chevron-down theme--light"
                @click="expandRow(item)"
              />
            </td>
          </tr>
          <tr
            v-if="item.expanded"
            :key="`expanded-${index}`"
          >
            <slot
              name="opened-item"
              :item="item"
              :headers="data.headers"
            />
          </tr>
        </template>
      </tbody>
      <tbody v-else>
        <tr class="v-data-table__empty-wrapper">
          <td :colspan="headers.length">
            <template v-if="loading">
              {{ $t('admin_panel.loading') }}
            </template>
            <template v-else>
              {{ $t('admin_panel.noDataText') }}
            </template>
          </td>
        </tr>
      </tbody>
    </template>
  </v-data-table>
</template>
<script>
  export default {
    name: 'DataTable',
    props: {
      fetchUrl: {
        type: String,
        required: true,
      },
      headers: {
        type: Array,
        default: () => ([]),
      },
      searchOptions: {
        type: [Object, FormData],
        default: () => ({}),
      },
    },
    data: () => ({
      items: [],
      loading: true,
      meta: [],
      options: [],
    }),
    computed: {
      total () {
        if (!this.meta) {
          return 0
        }
        return this.meta.total ? this.meta.total : 0
      },
    },
    mounted () {
      this.$watch('searchOptions', this.fetchPosts, {
        deep: true,
        immediate: true,
      })
      this.$watch('options', this.fetchPosts, {
        deep: true,
        immediate: false,
      })
    },
    methods: {
      fetchPosts () {
        this.loading = true
        const {
          page = 1,
          itemsPerPage = 10,
          sortBy = null,
        } = this.options

        const sortDesc = this.options.sortDesc.includes(false) ? 'ASC' : 'DESC'

        this.$http.get(this.fetchUrl, {
          params: {
            perPage: itemsPerPage === -1 ? 10000000 : itemsPerPage,
            page: page,
            orderBy: sortBy ? sortBy[0] : null,
            direction: sortDesc,
            ...this.searchOptions,
          },
        })
          .then(({ data }) => {
            this.items = []
            data.data.forEach(el => {
              el.expanded = false
              this.items.push(el)
            })
            this.meta = data.meta
            this.loading = false
          })
          .catch((response) => {
            this.loading = false
            console.error(response)
          })
      },
      setOptions (options) {
        this.options = options
      },
      rowExpandedClass (item) {
        return item.expanded ? 'v-data-table__expand-icon--active' : 'v-data-table__expand-icon'
      },
      expandRow (item) {
        item.expanded = !item.expanded
        const itemIndex = this.items.indexOf(item)

        this.items.forEach((el, index) => {
          if (itemIndex !== index) {
            el.expanded = false
          }
        })
      },
      getFromObject (item, header) {
        let value = ''
        const keys = header.split('.')
        value = item[keys[0]]

        if (!value) {
          return value
        }

        const lastKeyIndex = keys.length - 1
        for (let i = 1; i <= lastKeyIndex; ++i) {
          value = value[keys[i]]
        }
        return value
      },
      getHeaderClass (header) {
        const defaultClass = header.value.split('.').join(' ')

        if (header.value === 'data-table-expand') {
          return `text-right ${defaultClass}`
        }
        if (header.align) {
          return `text-${header.align} ${defaultClass}`
        }
        return `text-start ${defaultClass}`
      },
    },
  }
</script>
