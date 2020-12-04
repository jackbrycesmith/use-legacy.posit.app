<template>
  <div class="xl:flex-shrink-0 xl:w-64 xl:border-r xl:border-gray-200">
    <div class="pl-4 pr-6 py-6 sm:pl-6 lg:pl-8 lg:sticky lg:top-16 xl:pl-0">
      <div class="flex items-center justify-between">
        <div class="flex-1 space-y-8 w-full">
          <div class="space-y-8 sm:space-y-0 sm:flex sm:justify-between sm:items-center xl:block xl:space-y-8">
            <TeamDropdown :org="team" />

            <!-- Action buttons -->
            <div class="flex-shrink-0 flex flex-col space-y-3 sm:space-y-0 sm:space-x-3 sm:flex-row xl:flex-col xl:space-x-0 xl:space-y-3">
              <NewPositButton />
            </div>
          </div>

          <DashboardTeamNavigation :org="team" />

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TeamDropdown from '@/Components/TeamDropdown'
import NewPositButton from '@/Components/NewPositButton'
import DashboardTeamNavigation from '@/Components/DashboardTeamNavigation'

export default {
  components: {
    DashboardTeamNavigation,
    NewPositButton,
    TeamDropdown,
  },
  props: {
    team: { type: Object, required: true },
  },
  data () {
    return {

    }
  }
}
</script>
