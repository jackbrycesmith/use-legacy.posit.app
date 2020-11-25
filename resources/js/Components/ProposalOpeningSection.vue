<template>
  <div class="relative bg-blue-gray-200 flex flex-col justify-center items-center" style="height: 50vh; border-bottom-left-radius: 50% 20%; border-bottom-right-radius: 50% 20%;">

    <ContentEditable
      class="outline-none max-w-screen-xl text-center text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl"
      tag="h1"
      :model.sync="title"
      :is-content-editable="editable"
      @live-edit="handleLiveEditedName"
      @edit-done="handleEditTitleDone" />

    <ProposalCreatorRecipientMeta :proposal.sync="proposal" :editable="editable" />

    <ProposalIntroVideoRecording :proposal.sync="proposal" />

  </div>
</template>

<script>
import Proposal from '@/models/Proposal'
import ContentEditable from '@/Components/ContentEditable'
import ProposalIntroVideoRecording from '@/Components/ProposalIntroVideoRecording'
import ProposalCreatorRecipientMeta from '@/Components/ProposalCreatorRecipientMeta'

export default {
  props: {
    proposal: { type: Object },
    editable: {
      type: Boolean,
      default: true
    }
  },
  components: {
    ContentEditable,
    ProposalIntroVideoRecording,
    ProposalCreatorRecipientMeta
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
    },
    handleLiveEditedName (value) {
      this.$emit('live-edit-name', value)
    },
  }
}
</script>
