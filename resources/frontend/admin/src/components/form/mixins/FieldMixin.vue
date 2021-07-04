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
        required: false,
      },
      placeholder: {
        type: String,
        required: false,
      },
      hint: {
        type: String,
        required: false,
      },
      scope: {
        type: String,
        default: '',
      },
      validationRule: {
        type: [String, Object, Array],
        default: '',
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
    methods: {
      updateValue (e) {
        this.$emit('input', {
          name: this.name,
          value: e,
        })
      },
    },
  }
</script>
