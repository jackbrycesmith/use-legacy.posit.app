<template>
  <div class="flex flex-col items-center h-screen">
    <span class="inline-flex rounded-md shadow-sm mt-10">
      <inertia-link :href="$route('use.posit.new')" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-primary-yellow-500 hover:bg-primary-yellow-400 focus:outline-none focus:border-primary-yellow-600 focus:shadow-outline-primary-yellow active:bg-primary-yellow-400 transition ease-in-out duration-150">
        New Proposal
      </inertia-link>
    </span>

    <h2 class="text-gray-500 text-xs font-medium uppercase tracking-wide mt-10">{{ orgName }}</h2>
    <h2 class="text-gray-500 text-xs font-medium uppercase tracking-wide mt-2">{{ orgMemberCount }} members</h2>

    <div class="w-full max-w-3xl mx-auto">
      <StripeConnectSetup
        :stripe-account="stripeAccount"
        :org-uuid="orgUuid"
        class="mt-10"
        @disconnected="handleStripeAccountDisconnected"/>
    </div>

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
