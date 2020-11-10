<template>
  <li class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow">
    <div class="flex-1 flex flex-col p-8">
      <div class="flex flex-col justify-center items-center">
        <div class="w-10 h-10 flex-shrink-0 bg-yellow-300 text-black rounded-full flex items-center justify-center">
          {{ orgContact.name | initials }}
        </div>
        <h3 class="mt-3 text-gray-900 text-lg truncate leading-5 font-medium">{{ orgContact.name }}</h3>
      </div>

      <AccessCodeDisplayField
        :code="orgContact.access_code"
        label-class="block text-xs font-medium leading-5 text-gray-600 truncate"
        class="mt-1"
      />

    </div>
    <div class="border-t border-gray-200">
      <div class="-mt-px flex">
        <div class="-ml-px w-0 flex-1 flex">
          <inertia-link :href="orgContact.routeEdit(orgUuid)" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm leading-5 text-gray-700 font-medium border border-transparent rounded-br-lg hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 transition ease-in-out duration-150">
            <svg viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path></svg>
            <span class="ml-3">Details</span>
          </inertia-link>
        </div>
      </div>
    </div>
  </li>
</template>

<script>
import AccessCodeDisplayField from '@/Components/AccessCodeDisplayField'

export default {
  components: {
    AccessCodeDisplayField
  },
  props: {
    orgUuid: { type: String },
    orgContact: { type: Object, default: () => {} }
  },
  data () {
    return {

    }
  }
}
</script>
