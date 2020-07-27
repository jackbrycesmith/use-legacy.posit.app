<template>
  <!-- This component requires Tailwind CSS >= 1.5.1 and @tailwindcss/ui >= 0.4.0 -->
  <div class="relative py-16 overflow-hidden">
    <div class="relative px-4 sm:px-6 lg:px-8">
      <div class="text-lg max-w-prose mx-auto mb-6">
        <p class="text-base text-center leading-6 text-indigo-600 font-semibold tracking-wide uppercase">New proposal</p>
        <h1 class="mt-2 mb-8 text-3xl text-center leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl sm:leading-10">Please choose the organisation <br> for this new proposal <br>
          <svg class="h-5 w-5 inline-block" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7"></path></svg>
        </h1>
      </div>
      <ul class="mx-auto max-w-sm">
        <li
          v-for="org in orgs__"
          :key="org.uuid"
          :org="org"
          @click="handleOrgChoice(org)"
          class="col-span-1 flex items-center bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden mb-5 cursor-pointer">
          <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 text-white text-center text-sm leading-5 font-medium bg-pink-600">
            <!-- TODO something here -->
            ORG
          </div>
          <div class="flex-1 px-4 py-2 truncate">
            <span class="text-gray-900 text-sm leading-5 font-medium hover:text-gray-600 transition ease-in-out duration-150">{{ org.name }}</span>
            <p class="text-sm leading-5 text-gray-500">X Members</p>
          </div>
          <div class="flex-shrink-0 pr-2">
            <span class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
              <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 5l7 7-7 7"></path></svg>
            </span>
          </div>
        </li>
      </ul>
    </div>
  </div>

</template>

<script>
import Organisation from '@/models/Organisation'
import OrganisationListItem from '@/Lists/OrganisationListItem'

export default {
  components: {
    OrganisationListItem
  },
  props: {
    orgs: {}
  },
  data () {
    return {
      orgs__: []
    }
  },
  watch: {
    orgs: {
      immediate: true,
      handler (value) {
        this.orgs__ = Organisation.make(value)
      }
    }
  },
  methods: {
    handleOrgChoice (org) {
      this.$inertia.post(
        this.$route('org.create-draft-proposal', { organisation: org.uuid })
      )
    }
  }
}
</script>
