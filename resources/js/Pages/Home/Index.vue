<template>
  <fragment>
    <button @click="toggleProposalSlideOver">toggle slide over</button>

    <!-- TODO move to something app level, portal -->

    <ProposalSlideOver ref="proposalSlideOver" />
    <FirstWelcomeModal ref="firstWelcomeModal"/>
  </fragment>
</template>

<script>
import ProposalSlideOver from '@/SlideOvers/ProposalSlideOver'
import FirstWelcomeModal from '@/Modals/FirstWelcomeModal'

export default {
  components: { ProposalSlideOver, FirstWelcomeModal },
  data () {
    return {

    }
  },
  mounted () {
    // TODO maybe do component dynamic import loads inside created () or something so we can do stuff when its loaded
    // setTimeout(() => {
    //   this.$refs.firstWelcomeModal.show()
    // }, 2000)
  },
  methods: {
    toggleProposalSlideOver () {
      const proposalSlideOver = this.$refs.proposalSlideOver
      if (!proposalSlideOver) { return }

      proposalSlideOver.isVisible ? proposalSlideOver.hide() : proposalSlideOver.show()
    }
  }
}
</script>
