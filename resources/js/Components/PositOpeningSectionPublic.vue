<template>
  <div class="relative bg-blue-gray-200 flex flex-col justify-center items-center" style="height: 50vh; border-bottom-left-radius: 50% 20%; border-bottom-right-radius: 50% 20%;">

    <ContentEditable
      class="outline-none max-w-screen-xl text-center text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl"
      tag="h1"
      :model="title"
      :is-content-editable="false"/>

    <PositCreatorRecipientMeta :posit="posit" :editable="false" />

    <VideoExpandablePlaybackOnly v-if="hasReadyIntroVideo" :posit="posit" />

  </div>
</template>

<script>
import Posit from '@/models/Posit'
import ContentEditable from '@/Components/ContentEditable'
import VideoExpandablePlaybackOnly from '@/Components/VideoExpandablePlaybackOnly'
import PositCreatorRecipientMeta from '@/Components/PositCreatorRecipientMeta'

export default {
  props: {
    posit: { type: Object },
  },
  components: {
    ContentEditable,
    VideoExpandablePlaybackOnly,
    PositCreatorRecipientMeta
  },
  data () {
    return {
      title: 'Posit'
    }
  },
  computed: {
    hasReadyIntroVideo () {
      return this.posit?.intro_video?.has_converted_video ?? false
    }
  },
  watch: {
    posit: {
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
