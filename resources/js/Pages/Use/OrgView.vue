<template>
  <div class="flex flex-col items-center h-screen">
    <span class="inline-flex rounded-md shadow-sm mt-10">
      <inertia-link :href="$route('use.proposal.new')" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base leading-6 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
        New Proposal
      </inertia-link>
    </span>

    <h2 class="text-gray-500 text-xs font-medium uppercase tracking-wide mt-10">{{ orgName }}</h2>
    <h2 class="text-gray-500 text-xs font-medium uppercase tracking-wide mt-2">{{ orgMemberCount }} members</h2>

    <StripeConnectSetup
      :stripe-account="stripeAccount"
      :org-uuid="orgUuid"
      class="mt-10"
      @disconnected="handleStripeAccountDisconnected"/>

    <!-- <ProposalList :proposals="proposals.data" class="mt-10"/> -->
  </div>
</template>

<script>
import StripeConnectSetup from '@/Components/StripeConnectSetup'
import Organisation from '@/models/Organisation'

export default {
  components: { StripeConnectSetup },
  props: {
    org: { type: Object, default: () => {} }
  },
  computed: {
    orgUuid () {
      return this.org__?.uuid
    },
    orgName () {
      return this.org?.data?.name
    },
    orgMemberCount () {
      return this.org?.data?.users.length
    },
    stripeAccount () {
      return this.org__?.stripeAccount
    }
  },
  data () {
    return {
      org__: Organisation.make()
    }
  },
  watch: {
    org: {
      immediate: true,
      handler (value) {
        this.org__ = Organisation.make(value)
      }
    }
  },
  methods: {
    handleStripeAccountDisconnected () {
      this.org__.stripeAccount = null
    }
  }
}
</script>
