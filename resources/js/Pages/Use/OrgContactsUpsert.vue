<template>
  <!-- 3 column wrapper -->
  <div class="flex-grow w-full max-w-7xl mx-auto xl:px-8 lg:flex">
    <!-- Left sidebar & main wrapper -->
    <div class="flex-1 min-w-0 xl:flex">
      <!-- Account profile -->
      <div class="xl:flex-shrink-0 xl:w-64 xl:border-r xl:border-gray-200">
        <div class="pl-4 pr-6 py-6 sm:pl-6 lg:pl-8 xl:pl-0">
          <div class="flex items-center justify-between">
            <div class="flex-1 space-y-8">
              <div class="space-y-8 sm:space-y-0 sm:flex sm:justify-between sm:items-center xl:block xl:space-y-8">
                <!-- Profile -->
<!--                 <div class="flex items-center space-x-3">
                  <div class="flex-shrink-0 h-12 w-12">
                    <img class="h-12 w-12 rounded-full" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=256&h=256&q=80" alt="">
                  </div>
                  <div class="space-y-1">
                    <div class="text-sm leading-5 font-medium text-gray-900">Debbie Lewis</div>
                    <a href="#" class="group flex items-center space-x-2.5">
                      <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-500" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.99917 0C4.02996 0 0 4.02545 0 8.99143C0 12.9639 2.57853 16.3336 6.15489 17.5225C6.60518 17.6053 6.76927 17.3277 6.76927 17.0892C6.76927 16.8762 6.76153 16.3104 6.75711 15.5603C4.25372 16.1034 3.72553 14.3548 3.72553 14.3548C3.31612 13.316 2.72605 13.0395 2.72605 13.0395C1.9089 12.482 2.78793 12.4931 2.78793 12.4931C3.69127 12.5565 4.16643 13.4198 4.16643 13.4198C4.96921 14.7936 6.27312 14.3968 6.78584 14.1666C6.86761 13.5859 7.10022 13.1896 7.35713 12.965C5.35873 12.7381 3.25756 11.9665 3.25756 8.52116C3.25756 7.53978 3.6084 6.73667 4.18411 6.10854C4.09129 5.88114 3.78244 4.96654 4.27251 3.72904C4.27251 3.72904 5.02778 3.48728 6.74717 4.65082C7.46487 4.45101 8.23506 4.35165 9.00028 4.34779C9.76494 4.35165 10.5346 4.45101 11.2534 4.65082C12.9717 3.48728 13.7258 3.72904 13.7258 3.72904C14.217 4.96654 13.9082 5.88114 13.8159 6.10854C14.3927 6.73667 14.7408 7.53978 14.7408 8.52116C14.7408 11.9753 12.6363 12.7354 10.6318 12.9578C10.9545 13.2355 11.2423 13.7841 11.2423 14.6231C11.2423 15.8247 11.2313 16.7945 11.2313 17.0892C11.2313 17.3299 11.3937 17.6097 11.8501 17.522C15.4237 16.3303 18 12.9628 18 8.99143C18 4.02545 13.97 0 8.99917 0Z" fill="currentcolor" />
                      </svg>
                      <div class="text-sm leading-5 text-gray-500 group-hover:text-gray-900 font-medium">debbielewis</div>
                    </a>
                  </div>
                </div> -->
                <OrgDropdown :org="org__" />


                <!-- Action buttons -->
                <div class="flex flex-col space-y-3 sm:space-y-0 sm:space-x-3 sm:flex-row xl:flex-col xl:space-x-0 xl:space-y-3">
                  <NewProposalButton />
                </div>
              </div>

              <!-- Org Navigation... -->
              <OrgNavigation :org="org__" />

            </div>
          </div>
        </div>
      </div>

      <!-- Settings List -->
      <div class="lg:min-w-0 lg:flex-1">
        <div class="pl-4 pr-6 pt-4 pb-4 border-b border-t border-gray-200 sm:pl-6 lg:pl-8 xl:pl-6 xl:pt-6 xl:border-t-0">
          <div class="flex items-center">
            <h1 class="flex-1 text-3xl leading-7 font-bold">
              <inertia-link :href="org__.route_contacts">
                Contacts
              </inertia-link>
              <svg viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline"><path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>

              <span class="font-normal">{{ isAdd ? 'Add' : 'Edit' }}</span>
            </h1>
          </div>
        </div>

        <div>
          <OrgContactUpsertForm
            :org="org__"
            :contact="contact__"
          />
        </div>

      </div>
    </div>

  </div>
</template>

<script>
import Dashboard from '@/layouts/Dashboard'
import Organisation from '@/models/Organisation'
import OrganisationContact from '@/models/OrganisationContact'
import OrgNavigation from '@/Components/OrgNavigation'
import OrgDropdown from '@/Components/OrgDropdown'
import OrgContactListItem from '@/Lists/OrgContactListItem'
import OrgContactUpsertForm from '@/Forms/OrgContactUpsertForm'
import NewProposalButton from '@/Components/NewProposalButton'
import { isNil } from 'lodash-es'

export default {
  components: { OrgDropdown, OrgNavigation, OrgContactUpsertForm, OrgContactListItem, NewProposalButton },
  props: {
    org: { type: Object },
    contact: { type: Object }
  },
  layout: Dashboard,
  data () {
    return {
      org__: Organisation.make(),
      contact__: null,
    }
  },
  computed: {
    isAdd () {
      return isNil(this.contact)
    },
  },
  watch: {
    org: {
      immediate: true,
      handler (value) {
        this.org__ = Organisation.make(value)
      }
    },
    contact: {
      immediate: true,
      handler (value) {
        if (value) {
          this.contact__ = OrganisationContact.make(value)
        }
      }
    }
  },
  methods: {

  }
}
</script>
