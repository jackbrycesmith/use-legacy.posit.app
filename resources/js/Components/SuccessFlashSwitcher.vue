<template>
  <fragment>

    <slot
      v-if="!isSuccess"
      name="normal"/>

    <slot
      v-if="isSuccess"
      name="success"/>

  </fragment>
</template>

<script>
export default {
  props: {
    animationLengthMs: { type: Number, default: 1000 },
    toBlurAfterSuccessEl: { }
  },
  data () {
    return {
      isSuccess: false
    }
  },
  methods: {
    success () {
      if (this.isSuccess) return
      this.isSuccess = true

      setTimeout(() => {
        this.isSuccess = false
        this.toBlurAfterSuccessEl?.blur()
      }, this.animationLengthMs)
    }
  }
}
</script>
