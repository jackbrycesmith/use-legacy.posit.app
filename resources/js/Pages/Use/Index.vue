<template>
  <!-- 3 column wrapper -->
  <div class="flex-grow w-full max-w-7xl mx-auto xl:px-8 lg:flex">
    <!-- Left sidebar & main wrapper -->
    <div class="flex-1 min-w-0 xl:flex">
      <TeamDashboardSidebar :team="org__" />

      <!-- Projects List -->
      <div class="lg:min-w-0 lg:flex-1">
        <div class="pl-4 pr-6 pt-4 pb-4 border-b border-t border-gray-200 sm:pl-6 lg:pl-8 xl:pl-6 xl:pt-6 xl:border-t-0">
          <div class="flex items-center">
            <h1 class="flex-1 text-3xl leading-7 font-bold">Proposals</h1>
          </div>
        </div>
        <ul class="relative z-0 divide-y divide-gray-200 border-b border-gray-200 bg-white">
          <ProposalDashboardListItem
            v-for="proposal in org__.proposals"
            :key="proposal.id"
            :proposal="proposal"
          />
        </ul>
      </div>
    </div>

    <!-- Activity feed -->
    <div class="pr-4 sm:pr-6 lg:pr-8 lg:flex-shrink-0 lg:border-l lg:border-gray-200 xl:pr-0">
      <div class="pl-6 lg:w-80">
        <div class="pt-6 pb-2">
          <h2 class="text-sm leading-5 font-semibold">Activity</h2>
        </div>
        <div>
          <ul class="divide-y divide-gray-200">
<!--             <li class="py-4">
              <div class="flex space-x-3">
                <img class="h-6 w-6 rounded-full" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=256&h=256&q=80" alt="">
                <div class="flex-1 space-y-1">
                  <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium leading-5">You</h3>
                    <p class="text-sm leading-5 text-gray-500">1h</p>
                  </div>
                  <p class="text-sm leading-5 text-gray-500">Deployed Workcation (2d89f0c8 in master) to production</p>
                </div>
              </div>
            </li> -->

            <!-- More items... -->
          </ul>
          <div class="py-4 text-sm leading-5 border-t border-gray-200">
            <inertia-link :href="org__.route_activity_logs" class="text-yellow-400 font-semibold hover:text-yellow-300 cursor-pointer">View all activity &rarr;</inertia-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TeamDashboardSidebar from '@/Components/TeamDashboardSidebar'
import ProposalList from '@/Lists/ProposalList'
import OrganisationList from '@/Lists/OrganisationList'
import Dashboard from '@/layouts/Dashboard'
import Organisation from '@/models/Organisation'
import ProposalDashboardListItem from '@/Lists/ProposalDashboardListItem'

export default {
  components: {
    OrganisationList,
    ProposalList,
    ProposalDashboardListItem,
    TeamDashboardSidebar
  },
  props: {
    org: { type: Object, default: () => {} }
  },
  layout: Dashboard,
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
}
</script>
