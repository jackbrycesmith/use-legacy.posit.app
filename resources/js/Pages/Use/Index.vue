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
          <PositDashboardListItem
            v-for="posit in posits__"
            :key="posit.id"
            :posit="posit"
          />
        </ul>

        <tailable-pagination
          class="lg:sticky lg:bottom-0 bg-primary-yellow-50"
          :data="posits"
          :show-numbers="true"
          @page-changed="handlePositPageChanged"/>
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
import PositList from '@/Lists/PositList'
import Dashboard from '@/Layouts/Dashboard'
import Team from '@/models/Team'
import Posit from '@/models/Posit'
import PositDashboardListItem from '@/Lists/PositDashboardListItem'
import GettingStartedWelcome from '@/Components/GettingStartedWelcome'
import { find } from 'lodash-es'
import { getPayloadData } from '@/utils/data'

export default {
  components: {
    PositList,
    PositDashboardListItem,
    TeamDashboardSidebar,
    GettingStartedWelcome
  },
  props: {
    team: { type: Object, default: () => {} },
    posits: {},
  },
  layout: Dashboard,
  data () {
    return {
      team__: Team.make(),
      posits__: []
    }
  },
  methods: {
    handlePositPageChanged (value) {
      if (value === this.posits.meta.current_page) return
      const link = find(this.posits?.meta?.links ?? [], { 'label': value })
      if (!link) return

      this.$inertia.visit(link.url, {
        only: ['posits'],
        preserveState: true,
        preserveScroll: true
      })
    }
  },
  watch: {
    team: {
      immediate: true,
      handler (value) {
        this.team__ = Team.make(value)
      }
    },
    posits: {
      immediate: true,
      handler (value) {
        this.posits__ = Posit.make(getPayloadData(value))
      }
    },
  },
}
</script>
