<template>
  <validation-provider
    v-slot="{ errors, required, ariaInput, ariaMsg }"
    tag="div"
    :rules="validationRule"
    :name="label"
    class="treeselect-container"
    :vid="name"
  >
    <label
      class="treeselect-label"
      v-text="label"
    />
    <treeselect
      ref="treeSelect"
      v-model="innerValue"
      class="treeselect-component"
      :options="options"
      :normalizer="normalizer"
      v-bind="attributes"
    />
  </validation-provider>
</template>

<script>
  import Treeselect from '@riophae/vue-treeselect'
  import '@riophae/vue-treeselect/dist/vue-treeselect.css'
  import FieldMixin from '@/components/Form/Mixins/FieldMixin'
  export default {
    name: 'TreeselectField',
    components: {
      Treeselect,
    },
    mixins: [FieldMixin],
    props: {
      normalizer: {
        type: Function,
        default: (node) => {
          return {
            id: node.id,
            label: node.name,
            children: node.children,
          }
        },
      },
      options: {
        type: Array,
        default: () => [],
      },
    },
  }
</script>

<style>
.treeselect-component {
  margin-bottom: 40px;
}
.treeselect-component .vue-treeselect__control {
  padding-top: 15px;
  padding-bottom: 15px;
  border-radius: 4px;
}
.treeselect-component .vue-treeselect__placeholder,
.vue-treeselect__single-value {
  line-height: 22px;
}
.treeselect-container {
  position: relative;
}
.treeselect-container .treeselect-label{
    position: absolute;
    top: -10px;
    z-index: 1;
    background: white;
    left: 8px;
    color: rgba(0, 0, 0, 0.6);
    font-size: 13px;
    padding-right: 3px;
    padding-left: 2px;
  }
.treeselect-component .vue-treeselect__control {
  border-color: rgba(0, 0, 0, 0.42);
}
.treeselect-component.vue-treeselect--open-below:not(.vue-treeselect--append-to-body) .vue-treeselect__menu-container {
  /* position: relative; */
  top: -20%;

}
.treeselect-component .vue-treeselect__menu{
  border-radius: 4px;
  box-shadow: 0px 5px 5px -3px rgba(0, 0, 0, 0.2), 0px 8px 10px 1px rgba(0, 0, 0, 0.14), 0px 3px 14px 2px rgba(0, 0, 0, 0.12);

}
.treeselect-component .vue-treeselect__menu > div > div > div{
 padding: 10px;
}
.treeselect-component.vue-treeselect--focused .vue-treeselect__control{
  border-color: var(--v-primary-base)!important;
  box-shadow: none;
}
.treeselect-component .vue-treeselect__option--selected{
  background: var(--v-primary-lighten5)!important;
  color: var(--v-primary-base)!important;
}
</style>
