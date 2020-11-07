<template>
  <div class="min-h-screen flex items-center justify-center bg-primary-yellow-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div>
        <img class="mx-auto h-12 w-auto" src="/posit-icon-2-grey-gold.svg" alt="Posit.app logo">
        <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
          Enter Access Code
        </h2>
        <p class="mt-2 text-center text-sm leading-5 text-gray-600">
          for: <span class="italic"></span>
        </p>
      </div>

      <ProposalAuthForm
        :proposal-uuid="proposal__.uuid"
        class="mt-8"/>

    </div>
  </div>
</template>

<script>
import Proposal from '@/models/Proposal'
import ProposalAuthForm from '@/Forms/ProposalAuthForm'

export default {
  components: { ProposalAuthForm },
  props: {
    proposal: { type: Object }
  },
  data () {
    return {
      proposal__: Proposal.make()
    }
  },
  watch: {
    proposal: {
      immediate: true,
      handler (value) {
        this.proposal__ = Proposal.make(value)
      }
    }
  }
}
</script>
