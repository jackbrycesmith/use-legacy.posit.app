<template>
  <fragment>
    <button @click="isBaseSlideOverVisible = !isBaseSlideOverVisible">toggle slide over</button>

    <!-- TODO move to something app level, portal -->

    <BaseSlideOver ref="baseSlideOver" :is-visible.sync="isBaseSlideOverVisible" :is-rounded="true" />
    <FirstWelcomeModal ref="firstWelcomeModal"/>
  </fragment>
</template>

<script>
import BaseSlideOver from '@/SlideOvers/BaseSlideOver'
import FirstWelcomeModal from '@/Modals/FirstWelcomeModal'

export default {
  components: { BaseSlideOver, FirstWelcomeModal },
  data () {
    return {
      isBaseSlideOverVisible: true
    }
  },
  mounted () {
    // setTimeout(() => {
    //   this.$refs.firstWelcomeModal.show()
    // }, 1000)
  }
}
</script>
