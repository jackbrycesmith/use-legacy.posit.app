<template>
  <input
    :disabled="!editable"
    type="text"
    @keyup="updateValue">
</template>

<script>
import Cleave from 'cleave.js'

export default {
  props: {
    value: {},
    options: {},
    editable: {
      type: Boolean,
      default: true
    },
  },
  mounted() {
   this.cleave = new Cleave(this.$el, this.options)
   this.cleave.setRawValue(this.value)
  },
  destroyed() {
    this.cleave.destroy()
  },
  watch: {
    value: 'updateInput'
  },
  methods: {
    updateValue() {
      if (!this.editable) return

      var val = this.cleave.getRawValue()

      if (val !== this.value) {
        this.$emit('input', val)
      }
    },
    updateInput() {
      this.cleave.setRawValue(this.value)
    }
  }
}
</script>
