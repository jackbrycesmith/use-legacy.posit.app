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

        <GettingStartedWelcome
          :team="org__"
          class="mt-6" />

      </div>
    </div>
  </div>
</template>

<script>
import TeamDashboardSidebar from '@/Components/TeamDashboardSidebar'
import ProposalList from '@/Lists/ProposalList'
import OrganisationList from '@/Lists/OrganisationList'
import Dashboard from '@/Layouts/Dashboard'
import Organisation from '@/models/Organisation'
import ProposalDashboardListItem from '@/Lists/ProposalDashboardListItem'
import GettingStartedWelcome from '@/Components/GettingStartedWelcome'

export default {
  components: {
    OrganisationList,
    ProposalList,
    ProposalDashboardListItem,
    TeamDashboardSidebar,
    GettingStartedWelcome
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
