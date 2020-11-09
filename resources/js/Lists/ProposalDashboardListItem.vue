<template>
  <li class="relative pl-4 pr-6 py-5 hover:bg-gray-50 sm:py-6 sm:pl-6 lg:pl-8 xl:pl-6">
    <inertia-link :href="proposal.route_proposal_view" class="flex items-center justify-between space-x-4">

      <div class="min-w-0 space-y-1.5">
        <div class="flex items-center space-x-3">

          <span class="block">
            <h2 class="text-sm font-medium leading-5">
              <span>
                <span class="absolute inset-0"></span>
                » {{ proposal.name }}
              </span>
            </h2>
          </span>
        </div>
        <span class="relative group flex items-center space-x-2.5">
          <BadgeWithDot class="" :show-dot="true" :color="statusBadgeColor" custom-badge-class="">
            {{ proposal.status_name | titleCase }}
          </BadgeWithDot>
          <BadgeWithDot class="ml-1" :show-dot="false" color="gray">
            {{ proposal.total_amount_display_format }}
          </BadgeWithDot>
        </span>
      </div>


      <!-- Proposal meta info -->
      <div class="hidden sm:flex flex-col flex-shrink-0 items-end space-y-3">
        <p class="flex text-gray-500 text-sm leading-5 space-x-2">

<!--                   <span>Laravel</span>
          <span>&middot;</span>
          <span>Last deploy 3h ago</span>
          <span>&middot;</span>
          <span>United states</span> -->
        </p>
        <p class="flex items-center space-x-4">

<!--                   <a href="#" class="relative text-sm leading-5 text-gray-500 hover:text-gray-900 font-medium">
            Visit site
          </a>
          <button class="relative" type="button">
            <svg class="h-5 w-5 text-yellow-300 hover:text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </button> -->
        </p>
      </div>

      <div>
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
    proposal: { type: Object }
  },
  computed: {
    statusBadgeColor () {
      return this.proposal?.status_color ?? 'gray'
    }
  },
  data () {
    return {

    }
  }
}
</script>
