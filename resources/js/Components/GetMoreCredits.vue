<template>
  <div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
      <h2 class="text-2xl font-semibold text-gray-800 text-center">
        <ApplicationLogo class="h-10 w-40 mx-auto" />
      </h2>

      <Tabs
        :selected-index.sync="tabIndex"
        :show-tab-text-when-inactive="true"
        active-tab-class="border-b-2 border-primary-yellow-500 text-primary-yellow-600 focus:outline-none focus:text-primary-yellow-800 focus:border-primary-yellow-700"
        active-tab-icon-class="text-primary-yellow-500 group-focus:text-primary-yellow-600"
        tabs-class="mt-4">

<!--         <TabPane title="10 Credits">
          <div class="">


          </div>
        </TabPane> -->
<!--
        <TabPane title="25 Credits">
          <div class="">


          </div>
        </TabPane> -->

        <TabPane title="50 Credits">
          <div class="">


          </div>
        </TabPane>
      </Tabs>

      <!-- TODO Localised Price amount -->

      <!-- What the credits purchase will allow you to do -->
      <div class="mt-6 w-full inline-flex justify-center">
        <ul class="space-y-4">
          <li class="flex space-x-3">
            <IconHeroiconsMediumCheck class="flex-shrink-0 h-5 w-5 text-green-500" />
            <!-- TODO custom amount -->
            <span class="text-sm text-gray-500">Publish X proposals</span>
          </li>

          <li class="flex space-x-3">
            <IconHeroiconsMediumCheck class="flex-shrink-0 h-5 w-5 text-green-500" />
            <span class="text-sm text-gray-500">No expiry</span>
          </li>

          <li class="flex space-x-3">
            <IconHeroiconsMediumCheck class="flex-shrink-0 h-5 w-5 text-green-500" />
            <span class="text-sm text-gray-500">Zero payment processing fees</span>
          </li>
        </ul>
      </div>

      <div class="mt-8 flex justify-center items-center">
        <button type="button" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          Get X Credits
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import Tabs from '@/Components/Tabs'
import TabPane from '@/Components/TabPane'
import IconCredits from '@/Icons/IconCredits'
import IconHeroiconsMediumCheck from '@/Icons/IconHeroiconsMediumCheck'
import ApplicationLogo from '@/Jetstream/ApplicationLogo'

export default {
  name: 'GetMoreCredits',
  components: {
    Tabs,
    TabPane,
    IconCredits,
    IconHeroiconsMediumCheck,
    ApplicationLogo
  },
  data () {
    return {
      tabIndex: 0
    }
  }
}
</script>
