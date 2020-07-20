<template>
  <li class="col-span-1 flex items-center bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden">
    <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 text-white text-center text-sm leading-5 font-medium bg-pink-600">
      <!-- TODO something here -->
      ORG
    </div>
    <div class="flex-1 px-4 py-2 truncate">
      <inertia-link :href="orgViewRoute" class="text-gray-900 text-sm leading-5 font-medium hover:text-gray-600 transition ease-in-out duration-150">{{ orgName }}</inertia-link>
      <p class="text-sm leading-5 text-gray-500">X Members</p>
    </div>
    <div class="flex-shrink-0 pr-2">
      <inertia-link :href="orgViewRoute" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
<!--         <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
        </svg> -->
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
      </inertia-link>

    </div>
  </li>
</template>

<script>
export default {
  props: {
    org: { type: Object }
  },
  computed: {
    orgUuid () {
      return this.org.uuid
    },
    orgName () {
      return this.org?.name
    },
    orgViewRoute () {
      return route('use.org.view', { org: this.orgUuid ?? '' })
    }
  },
  data () {
    return {

    }
  }
}
</script>
