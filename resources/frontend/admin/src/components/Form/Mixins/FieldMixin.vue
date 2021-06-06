<script>
  export default {
    name: 'FieldMixin',
    props: {
      value: {
        type: [String, Number, Object, Array],
        required: false,
      },
      name: {
        type: String,
        required: true,
      },
      label: {
        type: String,
        required: true,
      },
      scope: {
        type: String,
        default: '',
      },
      validationRule: {
        type: [String, Object, Array],
        default: () => ([]),
      },
      attributes: {
        type: [Array, Object],
        default: () => { },
      },
      cols: {
        type: Number,
        validator: (value) => {
          return value <= 12
        },
      },
    },
    data: () => ({
      innerValue: null,
    }),
    watch: {
      innerValue (newVal) {
        this.$emit('input', {
          name: this.name,
          value: newVal,
        })
      },
      value (newVal) {
        this.innerValue = newVal
      },
    },
    created () {
      this.setComponentValue()
    },
    methods: {
      setComponentValue () {
        if (this.value !== undefined) {
          this.innerValue = this.value
        }
      },
    },
  }
</script>
