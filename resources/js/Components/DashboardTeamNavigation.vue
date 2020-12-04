<template>
  <nav class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-8 xl:flex-col xl:space-x-0 xl:space-y-2">

    <!-- Credits -->
    <inertia-link
      :href="org.route_credits"
      :class="routeIs('use.team.credits') ? activeNavItemClass : inactiveNavItemClass"
      class="group flex items-center rounded-md px-2 py-2 space-x-2">
      <IconCredits class="h-5 w-5 text-gray-400" />
      <span class="text-sm text-gray-500 leading-5 font-medium">{{ org.in_app_credit_balance }} Credits</span>
    </inertia-link>

    <!-- Posits -->
    <inertia-link
      :href="$route('use.index')"
      :class="routeIs('use.index') ? activeNavItemClass : inactiveNavItemClass"
      class="group flex items-center rounded-md px-2 py-2 space-x-2 focus:outline-none focus:bg-primary-yellow-100 transition ease-in-out duration-150">
      <IconHeroiconsSmallLightBulb class="h-5 w-5 text-gray-400" />
      <span class="text-sm text-gray-500 leading-5 font-medium">Posits</span>
    </inertia-link>

    <!-- Contacts/Recipients -->
    <!-- TODO: handle active when matches one of multiple routes -->
    <inertia-link
      :href="org.route_contacts"
      :class="routeIs('use.team.contacts') ? activeNavItemClass : inactiveNavItemClass"
      class="group flex items-center rounded-md px-2 py-2 space-x-2 focus:outline-none focus:bg-primary-yellow-100 transition ease-in-out duration-150">
      <IconContact class="h-5 w-5 text-gray-400" />
      <span class="text-sm text-gray-500 leading-5 font-medium">Contacts</span>
    </inertia-link>

    <!-- Settings -->
    <inertia-link
      :href="org.route_settings"
      :class="routeIs('use.team.settings') ? activeNavItemClass : inactiveNavItemClass"
      class="group flex items-center rounded-md px-2 py-2 space-x-2 focus:outline-none focus:bg-primary-yellow-100 transition ease-in-out duration-150">
      <IconHeroiconsSmallCog class="h-5 w-5 text-gray-400" />
      <span class="text-sm text-gray-500 leading-5 font-medium">Settings</span>
    </inertia-link>
  </nav>
</template>

<script>
import IconCredits from '@/Icons/IconCredits'
import IconContact from '@/Icons/IconContact'
import IconHeroiconsSmallCog from '@/Icons/IconHeroiconsSmallCog'
import IconHeroiconsSmallLightBulb from '@/Icons/IconHeroiconsSmallLightBulb'

export default {
  components: {
    IconCredits,
    IconContact,
    IconHeroiconsSmallCog,
    IconHeroiconsSmallLightBulb
  },
  props: {
    org: { type: Object },
    url: { type: String }
  },
  data () {
    return {

    }
  },
  computed: {
    activeNavItemClass () {
      return 'bg-primary-yellow-200 focus:bg-primary-yellow-100'
    },
    inactiveNavItemClass () {
      return 'hover:bg-primary-yellow-100'
    },
  },
  methods: {
    routeIs (key) {
      return route().current(key)
    }
  }
}
</script>
