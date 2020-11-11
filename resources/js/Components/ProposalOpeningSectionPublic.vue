<template>
  <div class="relative bg-cool-gray-300 flex flex-col justify-center items-center" style="height: 50vh; border-bottom-left-radius: 50% 20%; border-bottom-right-radius: 50% 20%;">

    <ContentEditable
      class="outline-none max-w-screen-xl text-center text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl"
      tag="h1"
      :model="title"
      :is-content-editable="false"/>

    <ProposalCreatorRecipientMeta :proposal="proposal" :editable="false" />

    <VideoExpandablePlaybackOnly v-if="hasReadyIntroVideo" :proposal="proposal" />

  </div>
</template>

<script>
import Proposal from '@/models/Proposal'
import ContentEditable from '@/Components/ContentEditable'
import VideoExpandablePlaybackOnly from '@/Components/VideoExpandablePlaybackOnly'
import ProposalCreatorRecipientMeta from '@/Components/ProposalCreatorRecipientMeta'

export default {
  props: {
    proposal: { type: Object },
  },
  components: {
    ContentEditable,
    VideoExpandablePlaybackOnly,
    ProposalCreatorRecipientMeta
  },
  data () {
    return {
      title: 'Proposal'
    }
  },
  computed: {
    hasReadyIntroVideo () {
      return this.proposal?.intro_video?.has_converted_video ?? false
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
  },
  methods: {

  }
}
</script>
