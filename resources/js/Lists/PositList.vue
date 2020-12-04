<template>
  <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <PositListItem
      v-for="proposal in proposals"
      :key="proposal.uuid"
      :proposal="proposal"
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
    proposals: { type: Array, default: () => [] }
  },
  data () {
    return {

    }
  }
}
</script>
