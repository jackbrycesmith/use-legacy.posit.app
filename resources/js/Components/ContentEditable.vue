<template>
  <!-- TODO prop & logic to strip tags / disable pasting html into here... -->
  <!-- Actually using v-text may protect me from this... -->
  <component
    ref="contentEditable"
    :is="tag"
    :contenteditable="isContentEditable"
    @blur="onEdit"
    @input="onLiveEdit"
    @keydown.enter="endEdit"
  />
</template>

<script>
export default {
  props: {
    tag: { type: String, required: true },
    isContentEditable: { type: Boolean, default: true },
    model: { type: String },
    liveUpdateModel: { type: Boolean, default: true }
  },
  data () {
    return {
      initialModel: ''
    }
  },
  watch: {
    model: {
      immediate: true,
      handler (value) {
        this.$nextTick(() => {
          if (this.$refs.contentEditable.innerText !== value) {
            this.$refs.contentEditable.innerText = value
          }
        })
      }
    }
  },
  methods: {
    onEdit (evt) {
      if (!this.isContentEditable) return

      this.$emit('update:model', evt.target.innerText)
      this.$emit('edit-done', evt.target.innerText)
    },
    onLiveEdit (evt) {
      if (!this.liveUpdateModel) return
      if (!this.isContentEditable) return

      this.$emit('update:model', evt.target.innerText)
      this.$emit('live-edit', evt.target.innerText)
    },
    endEdit () {
      this.$refs.contentEditable.blur()
    },
  }
}
</script>
