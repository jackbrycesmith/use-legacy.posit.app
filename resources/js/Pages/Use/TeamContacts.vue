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
            <h1 class="flex-1 text-3xl leading-7 font-bold">Contacts</h1>
            <span class="inline-flex rounded-md shadow-sm">
              <inertia-link :href="team__.route_contacts_add" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-6 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                <svg viewBox="0 0 20 20" fill="currentColor" class="-ml-0.5 mr-2 h-4 w-4"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path></svg>
                Add<span class="hidden sm:inline">&nbsp;Contact</span>
              </inertia-link>
            </span>
          </div>
        </div>

        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 mt-5 mx-5">
          <TeamContactListItem
            v-for="orgContact in contacts__"
            :key="orgContact.id"
            :org-uuid="team__.uuid"
            :org-contact="orgContact" />
        </ul>

        <tailable-pagination
          class="lg:sticky lg:bottom-0 bg-primary-yellow-50 mt-1"
          :data="contacts"
          :show-numbers="true"
          @page-changed="handleTeamContactsPageChanged"/>


<!--         <ul class="relative z-0 divide-y divide-gray-200 border-b border-gray-200">
          <PositDashboardListItem
            v-for="posit in team__.posits"
            :key="posit.id"
            :posit="posit"
          />
        </ul> -->
      </div>
    </div>

  </div>
</template>

<script>
import Dashboard from '@/Layouts/Dashboard'
import Team from '@/models/Team'
import TeamContact from '@/models/TeamContact'
import TeamContactListItem from '@/Lists/TeamContactListItem'
import TeamDashboardSidebar from '@/Components/TeamDashboardSidebar'
import { getPayloadData } from '@/utils/data'
import { find } from 'lodash-es'

export default {
  components: {
    TeamDashboardSidebar,
    TeamContactListItem,
  },
  props: {
    team: { type: Object },
    contacts: {},
  },
  layout: Dashboard,
  data () {
    return {
      team__: Team.make(),
      contacts__: []
    }
  },
  computed: {

  },
  watch: {
    team: {
      immediate: true,
      handler (value) {
        this.team__ = Team.make(value)
      }
    },
    contacts: {
      immediate: true,
      handler (value) {
        this.contacts__ = TeamContact.make(getPayloadData(value))
      }
    },
  },
  methods: {
    handleTeamContactsPageChanged (value) {
      if (value === this.contacts.meta.current_page) return
      const link = find(this.contacts?.meta?.links ?? [], { 'label': value })
      if (!link) return

      this.$inertia.visit(link.url, {
        only: ['contacts'],
        preserveState: true,
        preserveScroll: true
      })
    }
  },
}
</script>
