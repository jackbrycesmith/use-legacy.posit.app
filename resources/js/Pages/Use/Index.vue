<template>
  <div class="flex flex-col items-center h-screen">
    <span class="inline-flex rounded-md shadow-sm mt-10">
      <inertia-link :href="$route('use.proposal.new')" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base leading-6 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
        New Proposal
      </inertia-link>
    </span>

    <ProposalList :proposals="proposals.data" class="mt-10"/>
  </div>
</template>

<script>
import ProposalList from '@/Lists/ProposalList'

export default {
  components: { ProposalList },
  props: {
    proposals: { type: Object, default: () => {} }
  },
  data () {
    return {

    }
  }
}
</script>
