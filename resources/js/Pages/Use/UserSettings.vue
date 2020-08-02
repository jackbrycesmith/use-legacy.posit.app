<template>
  <div class="py-10">
    <header>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold leading-tight text-gray-900">
          User Settings
        </h1>
      </div>
    </header>
    <main>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Replace with your content -->
        <div class="grid grid-cols-4 gap-4">
          <!-- User profile card / user settings nav -->
          <div class="col-span-4 md:col-span-1">
            <!-- TODO nav -->
          </div>

          <!-- Content/Settings Panels -->
          <div class="col-span-4 md:col-span-3">

            <!-- e.g. delete account panel... -->
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Delete your account
                </h3>
                <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                  <p>
                    Once you delete your account, you will lose all data associated with it.
                  </p>
                </div>
                <div class="mt-5">
                  <button type="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    Delete account
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import Dashboard from '@/layouts/Dashboard'

export default {
  layout: Dashboard,
  data () {
    return {

    }
  }
}
</script>
