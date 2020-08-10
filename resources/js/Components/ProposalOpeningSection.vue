<template>
  <div class="relative bg-cool-gray-300 flex flex-col justify-center items-center" style="height: 50vh; border-bottom-left-radius: 50% 20%; border-bottom-right-radius: 50% 20%;">

    <ContentEditable
      class="outline-none max-w-screen-xl text-center text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl"
      tag="h1"
      :model.sync="title"
      @edit-done="handleEditTitleDone" />

  </div>
</template>

<script>
import ContentEditable from '@/Components/ContentEditable'
import Proposal from '@/models/Proposal'

export default {
  props: {
    proposal: { type: Object }
  },
  components: {
    ContentEditable
  },
  data () {
    return {
      title: 'Proposal'
    }
  },
  watch: {
    proposal: {
      immediate: true,
      handler (value) {
        if (value.name !== this.title) {
          this.title = value.name
        }
      }
    },
    title: {
      handler (value) {
        if (value !== this.proposal.name) {
          this.proposal.name = value
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
