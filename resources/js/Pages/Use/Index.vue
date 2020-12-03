<template>
  <!-- 3 column wrapper -->
  <div class="flex-grow w-full max-w-7xl mx-auto xl:px-8 lg:flex">
    <!-- Left sidebar & main wrapper -->
    <div class="flex-1 min-w-0 xl:flex">
      <TeamDashboardSidebar :team="team__" />

      <!-- Projects List -->
      <div class="lg:min-w-0 lg:flex-1">
        <div class="pl-4 pr-6 pt-4 pb-4 border-b border-t border-gray-200 sm:pl-6 lg:sticky lg:bg-primary-yellow-50 lg:top-16 lg:z-10 lg:pl-8 xl:pl-6 xl:pt-6 xl:border-t-0">
          <div class="flex items-center">
            <h1 class="flex-1 text-3xl leading-7 font-bold">Posits</h1>
          </div>
        </div>
        <ul class="relative z-0 divide-y divide-gray-200 border-b border-gray-200 bg-white">
          <ProposalDashboardListItem
            v-for="proposal in proposals__"
            :key="proposal.id"
            :proposal="proposal"
          />
        </ul>

        <tailable-pagination
          class="lg:sticky lg:bottom-0 bg-primary-yellow-50"
          :data="proposals"
          :show-numbers="true"
          @page-changed="handleProposalPageChanged"/>
      </div>
    </div>

    <!-- Activity feed -->
    <div class="pr-4 sm:pr-6 lg:pr-8 lg:flex-shrink-0 lg:border-l lg:border-gray-200 xl:pr-0">
      <div class="pl-6 mb-3 lg:w-80 lg:sticky lg:top-24">

        <GettingStartedWelcome
          :team="team__"
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
import Proposal from '@/models/Proposal'
import ProposalDashboardListItem from '@/Lists/ProposalDashboardListItem'
import GettingStartedWelcome from '@/Components/GettingStartedWelcome'
import { find } from 'lodash-es'
import { getPayloadData } from '@/utils/data'

export default {
  components: {
    OrganisationList,
    ProposalList,
    ProposalDashboardListItem,
    TeamDashboardSidebar,
    GettingStartedWelcome
  },
  props: {
    team: { type: Object, default: () => {} },
    proposals: {},
  },
  layout: Dashboard,
  data () {
    return {
      team__: Organisation.make(),
      proposals__: []
    }
  },
  methods: {
    handleProposalPageChanged (value) {
      if (value === this.proposals.meta.current_page) return
      const link = find(this.proposals?.meta?.links ?? [], { 'label': value })
      if (!link) return

      this.$inertia.visit(link.url, {
        only: ['proposals'],
        preserveState: true,
        preserveScroll: true
      })
    }
  },
  watch: {
    team: {
      immediate: true,
      handler (value) {
        this.team__ = Organisation.make(value)
      }
    },
    proposals: {
      immediate: true,
      handler (value) {
        this.proposals__ = Proposal.make(getPayloadData(value))
      }
    },
  },
}
</script>
