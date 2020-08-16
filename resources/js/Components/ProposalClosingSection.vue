<template>
  <div class="relative bg-cool-gray-300 flex flex-col items-center" style="height: 65vh; border-top-left-radius: 50% 20%; border-top-right-radius: 50% 20%;">

    <!-- Reiterate proposal name... -->
    <span class="max-w-xs px-5 -mt-7">
      <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium leading-5 bg-yellow-100 text-yellow-800 mt-15 max-w-full">
        <span class="truncate">{{ proposalTitleBadgeText }}</span>
      </span>
    </span>

    <ContentEditable
      class="outline-none max-w-screen-xl text-center text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:leading-none mt-2"
      tag="h1"
      :model.sync="closingTitle"
      @edit-done="handleEditTitleDone" />

  </div>
</template>

<script>
import Proposal from '@/models/Proposal'
import ContentEditable from '@/Components/ContentEditable'
import { isNil } from 'lodash-es'

export default {
  props: {
    proposal: { type: Object }
  },
  components: {
    ContentEditable
  },
  data () {
    return {
      closingTitle: 'Next Steps'
    }
  },
  computed: {
    proposalTitleBadgeText () {
      return this.proposal?.name ?? 'Proposal'
    }
  },
  watch: {
    proposal: {
      immediate: true,
      handler (value) {
        if (isNil(value.closing_title)) return

        if (value.closing_title !== this.closingTitle) {
          this.closingTitle = value.closing_title
        }
      }
    },
    title: {
      closingTitle (value) {
        if (value !== this.proposal.closing_title) {
          this.proposal.closing_title = value
          this.$emit('update:proposal', this.proposal)
        }
      }
    }
  },
  methods: {
    handleEditTitleDone (value) {
      this.$emit('edit-title-done', value)
    }
  }
}
</script>
