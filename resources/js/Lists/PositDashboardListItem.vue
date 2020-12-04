<template>
  <li class="relative hover:bg-gray-50">
    <inertia-link
      :href="posit.route_posit_view"
      class="flex items-center justify-between space-x-4 pl-4 pr-6 py-5 sm:py-6 sm:pl-6 lg:pl-8 xl:pl-6">

      <div class="min-w-0 space-y-1.5">
        <div class="flex items-center space-x-3">
          <h2 class="text-sm font-medium leading-5 truncate pr-1">
            » {{ posit.name }}
          </h2>
        </div>
        <span class="relative group flex items-center space-x-2.5">
          <BadgeWithDot class="" :show-dot="true" :color="statusBadgeColor" custom-badge-class="">
            {{ posit.status_name | titleCase }}
          </BadgeWithDot>
          <BadgeWithDot class="ml-1" :show-dot="false" color="gray">
            {{ posit.total_amount_display_format }}
          </BadgeWithDot>
        </span>
      </div>


      <!-- Posit meta info -->
      <div class="flex items-center justify-end">
        <div v-if="posit.has_recipient" class="flex flex-shrink-0 shadow-inner rounded-md mr-2">
          <div class="flex-shrink-0 flex items-center justify-center w-14 h-12 border-t border-l border-b border-white rounded-l-md">
            <div class="rounded-full bg-white shadow text-primary-yellow-500 h-8 w-8 inline-flex items-center justify-center text-sm font-medium">
              {{ posit.recipient_name | initials }}
            </div>
          </div>
          <div class="flex-1 hidden sm:flex items-center justify-between border-t border-r border-b border-white rounded-r-md truncate" style="min-width: 5rem; max-width: 8rem;">
            <div class="flex-1 pr-4 py-2 text-sm leading-5 truncate">
              <span class="text-gray-500 text-sm hover:text-gray-600 transition ease-in-out duration-150">{{ posit.recipient_name }}</span>
            </div>
          </div>
        </div>

        <IconHeroiconsSmallChevronRight class="h-5 w-5 text-gray-400" />
      </div>

    </inertia-link>
  </li>
</template>

<script>
import BadgeWithDot from '@/Components/TailwindUI/BadgeWithDot'
import IconHeroiconsSmallChevronRight from '@/Icons/IconHeroiconsSmallChevronRight'

export default {
  components: {
    BadgeWithDot,
    IconHeroiconsSmallChevronRight
  },
  props: {
    posit: { type: Object }
  },
  computed: {
    statusBadgeColor () {
      return this.posit?.status_color ?? 'gray'
    }
  },
  data () {
    return {

    }
  }
}
</script>
