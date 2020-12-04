<template>
  <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <PositListItem
      v-for="posit in posits"
      :key="posit.uuid"
      :posit="posit"
    />
  </ul>
</template>

<script>
import PositListItem from '@/Lists/PositListItem'

export default {
  components: {
    PositListItem
  },
  props: {
    posits: { type: Array, default: () => [] }
  },
  data () {
    return {

    }
  }
}
</script>
