<template>
  <!-- 3 column wrapper -->
  <div class="flex-grow w-full max-w-7xl mx-auto xl:px-8 lg:flex">
    <!-- Left sidebar & main wrapper -->
    <div class="flex-1 min-w-0 xl:flex">
      <TeamDashboardSidebar :team="org__" />

      <!-- Settings List -->
      <div class="lg:min-w-0 lg:flex-1">
        <div class="pl-4 pr-6 pt-4 pb-4 border-b border-t border-gray-200 sm:pl-6 lg:pl-8 xl:pl-6 xl:pt-6 xl:border-t-0">
          <div class="flex items-center">
            <h1 class="flex-1 text-3xl leading-7 font-bold">Activity Logs</h1>
          </div>
        </div>


<!--         <ul class="relative z-0 divide-y divide-gray-200 border-b border-gray-200">
          <ProposalDashboardListItem
            v-for="proposal in org__.proposals"
            :key="proposal.id"
            :proposal="proposal"
          />
        </ul> -->
      </div>
    </div>

  </div>
</template>

<script>
import Dashboard from '@/layouts/Dashboard'
import Organisation from '@/models/Organisation'
import TeamDashboardSidebar from '@/Components/TeamDashboardSidebar'

export default {
  components: {
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
  methods: {

  }
}
</script>
