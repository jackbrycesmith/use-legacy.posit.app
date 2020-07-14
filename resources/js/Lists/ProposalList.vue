<template>
  <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <ProposalListItem
      v-for="proposal in proposals"
      :key="proposal.uuid"
      :proposal="proposal"
    />
  </ul>
</template>

<script>
import ProposalListItem from '@/Lists/ProposalListItem'

export default {
  components: {
    ProposalListItem
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
