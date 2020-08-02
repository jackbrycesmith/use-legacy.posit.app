<template>
  <nav class="flex-shrink-0">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
      <div class="relative flex items-center justify-between h-16">
        <!-- Logo section -->
        <div class="flex items-center px-2 lg:px-0 xl:w-64">
          <inertia-link :href="$route('use.index')" class="flex-shrink-0" title="Home">
            <img class="h-8 w-auto" src="/posit-icon.png" alt="Posit logo">
          </inertia-link>
        </div>

        <!-- Links section -->
        <div class="block w-80">
          <div class="flex items-center justify-end">
            <div class="flex">
<!--                   <a href="#" class="px-3 py-2 rounded-md text-sm leading-5 font-medium text-gray-300 hover:text-white focus:outline-none focus:text-gray-400 focus:bg-orange-100 transition duration-150 ease-in-out">Documentation</a>
              <a href="#" class="px-3 py-2 rounded-md text-sm leading-5 font-medium text-gray-300 hover:text-white focus:outline-none focus:text-gray-400 focus:bg-orange-100 transition duration-150 ease-in-out">Support</a> -->
            </div>

            <UserAccountDropdown class="ml-3 flex-shrink-0" />
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import UserAccountDropdown from '@/Components/UserAccountDropdown'

export default {
  components: {
    UserAccountDropdown
  },
  data () {
    return {

    }
  }
}
</script>
