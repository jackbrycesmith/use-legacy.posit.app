<template>
  <span :title="name">
    {{ emoji }}
  </span>
</template>

<script>
import { flag, name } from 'country-emoji'

export default {
  props: {
    code: { type: String }
  },
  computed: {
    emoji () {
      return flag(this.code)
    },
    name () {
      return name(this.code)
    }
  }
}
</script>
