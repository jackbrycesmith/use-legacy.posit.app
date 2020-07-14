<template>
  <li class="col-span-1 bg-white rounded-lg shadow">
    <div class="w-full flex items-center justify-between p-6 space-x-6">
      <div class="flex-1 truncate">
        <div class="flex items-center space-x-3">
          <h3 class="text-gray-900 text-sm leading-5 font-medium truncate">Proposal</h3>
          <span class="flex-shrink-0 inline-block px-2 py-0.5 text-teal-800 text-xs leading-4 font-medium bg-teal-100 rounded-full">Admin</span>
        </div>
        <p class="mt-1 text-gray-500 text-sm leading-5 truncate">{{ proposalUuid }}</p>
      </div>
      <div class="w-10 h-10 bg-green-300 rounded-full flex-shrink-0"/>
    </div>
    <div class="border-t border-gray-200">
      <div class="-mt-px flex">
        <div class="w-0 flex-1 flex border-r border-gray-200">
          <a href="#" class="relative -mr-px w-0 flex-1 inline-flex items-center justify-center py-4 text-sm leading-5 text-gray-700 font-medium border border-transparent rounded-bl-lg hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 transition ease-in-out duration-150">
            <span class="ml-3"></span>
          </a>
        </div>
        <div class="-ml-px w-0 flex-1 flex">
          <inertia-link :href="proposalViewRoute" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm leading-5 text-gray-700 font-medium border border-transparent rounded-br-lg hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 transition ease-in-out duration-150">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
            <span class="ml-3">View</span>
          </inertia-link>
        </div>
      </div>
    </div>
  </li>
</template>

<script>
export default {
  props: {
    proposal: { type: Object }
  },
  computed: {
    proposalUuid () {
      return this.proposal.uuid
    },
    proposalViewRoute () {
      return route('use.proposal.view', { proposal: this.proposalUuid ?? '' })
    }
  },
  data () {
    return {

    }
  }
}
</script>
