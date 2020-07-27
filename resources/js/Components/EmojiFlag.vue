<template>
  <span :title="flag(code).name">
    {{ flag(code).emoji }}
  </span>
</template>

<script>
import countryFlagEmoji from "country-flag-emoji"

export default {
  props: {
    code: { type: String }
  },
  methods: {
    flag (code) {
      return countryFlagEmoji.get(code) ?? {}
    }
  }
}
</script>
