<template>
  <BaseForm
    :retrieve-form-payload="retrieveFormPayload"
    :request-url="upsertRoute"
    :request-method="requestMethod">
    <template #contents="{ isSubmitting }">
      <div class="shadow sm:rounded-md sm:overflow-hidden mx-7 mt-7">
        <div class="px-4 py-5 bg-white sm:p-6">
          <div class="grid grid-cols-3 gap-6">

            <div class="col-span-3 sm:col-span-2">
              <label for="name" class="block text-sm font-medium leading-5 text-gray-700">Name</label>
              <input
                id="name"
                type="text"
                v-model="contact__.name"
                :disabled="isSubmitting"
                required
                class="mt-1 max-w-lg block w-full shadow-sm focus:ring-primary-yellow-500 focus:border-primary-yellow-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md">
            </div>

          </div>

        </div>

        <!-- Submit button -->
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
          <span class="inline-flex rounded-md shadow-sm">
            <button
              type="submit"
              :disabled="isSubmitting"
              :class="{ 'cursor-wait': isSubmitting }"
              class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
              <span v-if="isSubmitting" class="left-0 inset-y-0 flex items-center pr-3">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>

              Save Contact
            </button>
          </span>
        </div>
      </div>

    </template>
  </BaseForm>
</template>

<script>
import BaseForm from '@/Forms/BaseForm'
import TeamContact from '@/models/TeamContact'
import { isNil } from 'lodash-es'

export default {
  components: {
    BaseForm
  },
  props: {
    org: { type: Object },
    contact: { type: Object },
  },
  data () {
    return {
      contact__: TeamContact.make()
    }
  },
  computed: {
    isAdd () {
      return isNil(this.contact)
    },
    requestMethod ()  {
      return this.isAdd ? 'post' : 'put'
    },
    upsertRoute () {
      if (this.isAdd) {
        return this.org.route_contacts_add
      }

      return this.contact__.routeUpdate(this.org.uuid)
    }
  },
  watch: {
    contact: {
      immediate: true,
      handler (value) {
        this.contact__ = TeamContact.make(value)
      }
    }
  },
  methods: {
    retrieveFormPayload () {
      return this.contact__.formPayload()
    }
  }
}
</script>
