<template>
  <!-- 3 column wrapper -->
  <div class="flex-grow w-full max-w-7xl mx-auto xl:px-8 lg:flex">
    <!-- Left sidebar & main wrapper -->
    <div class="flex-1 min-w-0 xl:flex">

      <TeamDashboardSidebar :team="team__" />

      <!-- Settings List -->
      <div class="lg:min-w-0 lg:flex-1">
        <div class="pl-4 pr-6 pt-4 pb-4 border-b border-t border-gray-200 sm:pl-6 lg:sticky lg:bg-primary-yellow-50 lg:top-16 lg:z-10 lg:pl-8 xl:pl-6 xl:pt-6 xl:border-t-0">
          <div class="flex items-center">
            <h1 class="flex-1 text-3xl leading-7 font-bold">Team Settings</h1>
          </div>
        </div>

        <!-- Team name settings -->
        <div class="md:grid md:grid-cols-3 md:gap-6 lg:pr-1">

          <div class="md:col-span-1">
            <div class="pl-4 sm:pl-6 lg:pl-8 xl:pl-6 mt-7">
              <h3 class="text-xl font-medium text-gray-900">
                Team Name
              </h3>
              <p class="mt-1 text-sm text-gray-600">
                The team's name and owner information.
              </p>
            </div>
          </div>

          <div class="mt-5 md:col-span-2">
            <UpdateTeamInformationForm :team="team__" :permissions="permissions" />
          </div>

        </div>

        <JetSectionBorder />

        <!-- Payment Settings -->
        <div class="md:grid md:grid-cols-3 md:gap-6 lg:pr-1 mb-5">

          <div class="md:col-span-1">
            <div class="pl-4 sm:pl-6 lg:pl-8 xl:pl-6">
              <h3 class="text-xl font-medium text-gray-900">
                Receive Payments
              </h3>
              <p class="mt-1 text-sm text-gray-600">
                Setup integrations so you can start receiving payments from your proposals.
              </p>
            </div>
          </div>

          <div class="mt-5 md:mt-0 md:col-span-2">
            <!-- Payments... -->
            <StripeConnectSetup
              :stripe-account="team__.stripeAccount"
              :team-uuid="team__.uuid"
              :permissions="permissions"
              @disconnected="handleStripeAccountDisconnected"/>

            <CoinbaseCommerceSetup
              :coinbase-commerce-account="team__.coinbaseCommerceAccount"
              :org-uuid="team__.uuid"
              class="mt-10"/>
          </div>

        </div>



      </div>
    </div>

  </div>
</template>

<script>
import Dashboard from '@/Layouts/Dashboard'
import Team from '@/models/Team'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import StripeConnectSetup from '@/Components/StripeConnectSetup'
import TeamDashboardSidebar from '@/Components/TeamDashboardSidebar'
import CoinbaseCommerceSetup from '@/Components/CoinbaseCommerceSetup'
import UpdateTeamInformationForm from '@/Forms/UpdateTeamInformationForm'

export default {
  components: {
    StripeConnectSetup,
    CoinbaseCommerceSetup,
    JetSectionBorder,
    UpdateTeamInformationForm,
    TeamDashboardSidebar
  },
  props: {
    team: { type: Object, default: () => {} },
    availableRoles: {},
    permissions: {}
  },
  layout: Dashboard,
  data () {
    return {
      team__: Team.make()
    }
  },
  watch: {
    team: {
      immediate: true,
      handler (value) {
        this.team__ = Team.make(value)
      }
    }
  },
  methods: {
    handleStripeAccountDisconnected () {
      this.team__.stripeAccount = null
    }
  }
}
</script>
