<template>
  <!-- 3 column wrapper -->
  <div class="flex-grow w-full max-w-7xl mx-auto xl:px-8 lg:flex">
    <!-- Left sidebar & main wrapper -->
    <div class="flex-1 min-w-0 xl:flex">
      <TeamDashboardSidebar :team="team__" />

      <!-- Settings List -->
      <div class="lg:min-w-0 lg:flex-1">
        <div class="pl-4 pr-6 pt-4 pb-4 border-b border-t border-gray-200 sm:pl-6 lg:pl-8 xl:pl-6 xl:pt-6 xl:border-t-0">
          <div class="flex items-center">
            <h1 class="flex-1 text-3xl leading-7 font-bold">
              <inertia-link :href="team__.route_contacts">
                Contacts
              </inertia-link>
              <svg viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline"><path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>

              <span class="font-normal">{{ isAdd ? 'Add' : 'Edit' }}</span>
            </h1>
          </div>
        </div>

        <div>
          <OrgContactUpsertForm
            :team="team__"
            :contact="contact__"
          />
        </div>

      </div>
    </div>

  </div>
</template>

<script>
import Dashboard from '@/Layouts/Dashboard'
import Team from '@/models/Team'
import TeamContact from '@/models/TeamContact'
import TeamContactListItem from '@/Lists/TeamContactListItem'
import OrgContactUpsertForm from '@/Forms/OrgContactUpsertForm'
import TeamDashboardSidebar from '@/Components/TeamDashboardSidebar'
import { isNil } from 'lodash-es'

export default {
  components: {
    OrgContactUpsertForm,
    TeamContactListItem,
    TeamDashboardSidebar
  },
  props: {
    team: { type: Object },
    contact: { type: Object }
  },
  layout: Dashboard,
  data () {
    return {
      team__: Team.make(),
      contact__: null,
    }
  },
  computed: {
    isAdd () {
      return isNil(this.contact)
    },
  },
  watch: {
    team: {
      immediate: true,
      handler (value) {
        this.team__ = Team.make(value)
      }
    },
    contact: {
      immediate: true,
      handler (value) {
        if (value) {
          this.contact__ = TeamContact.make(value)
        }
      }
    }
  },
  methods: {

  }
}
</script>
