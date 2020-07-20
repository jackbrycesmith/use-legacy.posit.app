<template>
  <div class="flex flex-col items-center h-screen">
    <span class="inline-flex rounded-md shadow-sm mt-10">
      <inertia-link :href="$route('use.proposal.new')" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base leading-6 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
        New Proposal
      </inertia-link>
    </span>

    <h2 class="text-gray-500 text-xs font-medium uppercase tracking-wide mt-10">{{ orgName }}</h2>
    <h2 class="text-gray-500 text-xs font-medium uppercase tracking-wide mt-2">{{ orgMemberCount }} members</h2>

    <span class="inline-flex rounded-md shadow-sm mt-10">
      <inertia-link v-if="!stripeConnected" :href="orgStripeConnectOauthStartRoute" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base leading-6 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
        Connect Stripe Account
      </inertia-link>

      <button v-else disabled class="inline-flex items-center px-6 py-3 border border-gray-300 text-base leading-6 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
        Stripe Account Connected ✔️
      </button>
    </span>

    <!-- <ProposalList :proposals="proposals.data" class="mt-10"/> -->
  </div>
</template>

<script>
export default {
  props: {
    org: { type: Object, default: () => {} }
  },
  computed: {
    stripeConnected () {
      return this.org?.data?.stripe_connected
    },
    orgUuid () {
      return this.org?.data?.uuid
    },
    orgName () {
      return this.org?.data?.name
    },
    orgStripeConnectOauthStartRoute () {
      return route('use.org.stripe-connect-oauth-start', { org: this.orgUuid ?? '' })
    },
    orgMemberCount () {
      return this.org?.data?.users.length
    }
  },
  data () {
    return {

    }
  }
}
</script>
