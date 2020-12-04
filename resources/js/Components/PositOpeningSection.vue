<template>
  <div class="relative bg-blue-gray-200 flex flex-col justify-center items-center" style="height: 50vh; border-bottom-left-radius: 50% 20%; border-bottom-right-radius: 50% 20%;">

    <ContentEditable
      class="outline-none max-w-screen-xl text-center text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl"
      tag="h1"
      :model.sync="title"
      :is-content-editable="editable"
      @live-edit="handleLiveEditedName"
      @edit-done="handleEditTitleDone" />

    <PositCreatorRecipientMeta :proposal.sync="proposal" :editable="editable" />

    <PositIntroVideoRecording :proposal.sync="proposal" />

  </div>
</template>

<script>
import Posit from '@/models/Posit'
import ContentEditable from '@/Components/ContentEditable'
import PositIntroVideoRecording from '@/Components/PositIntroVideoRecording'
import PositCreatorRecipientMeta from '@/Components/PositCreatorRecipientMeta'

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
    PositIntroVideoRecording,
    PositCreatorRecipientMeta
  },
  data () {
    return {
      title: 'Posit'
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
