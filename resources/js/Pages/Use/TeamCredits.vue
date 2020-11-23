<template>
  <!-- 3 column wrapper -->
  <div class="flex-grow w-full max-w-7xl mx-auto xl:px-8 lg:flex">
    <!-- Left sidebar & main wrapper -->
    <div class="flex-1 min-w-0 xl:flex">

      <TeamDashboardSidebar :team="team__" />

      <!-- Credits Page -->
      <div class="lg:min-w-0 lg:flex-1">
        <div class="pl-4 pr-6 pt-4 pb-4 border-b border-t border-gray-200 sm:pl-6 lg:sticky lg:bg-primary-yellow-50 lg:top-16 lg:z-10 lg:pl-8 xl:pl-6 xl:pt-6 xl:border-t-0">
          <div class="flex items-center">
            <h1 class="flex-1 text-3xl leading-7 font-bold">Credits</h1>
          </div>
        </div>

        <!-- Show current remaining credit balance -->
        <div class="h-32">
          <!-- TODO -->
        </div>

        <!-- Purchase/get more credits -->

      </div>
    </div>

    <!-- Credit History/Transactions -->
    <div class="pr-4 sm:pr-6 lg:pr-8 lg:flex-shrink-0 lg:border-l lg:border-gray-200 xl:pr-0">
      <div class="pl-6 mb-3 lg:w-80 lg:sticky lg:top-24">

        <CreditsHistorySnippet
          class="mt-6" />

      </div>
    </div>

  </div>
</template>

<script>
import TeamDashboardSidebar from '@/Components/TeamDashboardSidebar'
import CreditsHistorySnippet from '@/Components/CreditsHistorySnippet'
import Organisation from '@/models/Organisation'
import Dashboard from '@/Layouts/Dashboard'

export default {
  name: 'TeamCredits',
  components: {
    TeamDashboardSidebar,
    CreditsHistorySnippet
  },
  props: {
    team: { type: Object },
  },
  layout: Dashboard,
  data () {
    return {
      team__: Organisation.make(),
    }
  },
  watch: {
    team: {
      immediate: true,
      handler (value) {
        this.team__ = Organisation.make(value)
      }
    },
  },
}
</script>
