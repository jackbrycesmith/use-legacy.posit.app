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
            <h1 class="flex-1 text-3xl leading-7 font-bold">Credits</h1>
          </div>
        </div>


        <!-- TODO add here... -->

      </div>
    </div>

  </div>
</template>

<script>
import TeamDashboardSidebar from '@/Components/TeamDashboardSidebar'
import Organisation from '@/models/Organisation'
import Dashboard from '@/Layouts/Dashboard'

export default {
  name: 'TeamCredits',
  components: {
    TeamDashboardSidebar,
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
