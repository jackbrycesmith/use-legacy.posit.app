<template>
  <fragment>

    <!-- TODO move to something app level, portal -->
    <FirstWelcomeModal ref="firstWelcomeModal"/>
  </fragment>
</template>

<script>
import FirstWelcomeModal from '@/Modals/FirstWelcomeModal'

export default {
  components: { FirstWelcomeModal },
  data () {
    return {

    }
  },
  mounted () {
    setTimeout(() => {
      this.$refs.firstWelcomeModal.show()
    }, 1000)
  }
}
</script>
