<template>
  <div class="flex-1 flex flex-col justify-between">
    <div class="px-4 sm:px-6">
      <Tabs
        :selected-index.sync="tabIndex"
        :show-tab-text-when-inactive="false"
        active-tab-class="border-b-2 border-primary-yellow-500 text-primary-yellow-600 focus:outline-none focus:text-primary-yellow-800 focus:border-primary-yellow-700"
        active-tab-icon-class="text-primary-yellow-500 group-focus:text-primary-yellow-600"
        tabs-class="mt-8">

        <TabPane title="Design" :icon="designTabIcon">
          <div class="space-y-6 pt-6 pb-5">

            <!-- Posit Theme -->
            <div class="space-y-1">
              <label for="posit_theme" class="block text-sm font-medium leading-5 text-gray-600">
                Theme
              </label>

              <!-- Theme list options -->
              <div class="flex gap-2 items-center">
                <PositThemeBlock name="Cool Grey" class="flex-shrink-0" />
                <p class="flex-1 text-center text-gray-400 text-xs">
                  <IconHeroiconsMediumInformationCircle class="inline w-5 h-5 align-bottom" />
                  More theme choices coming soon!
                </p>
              </div>
            </div>

          </div>
        </TabPane>

        <TabPane title="Details" :icon="detailsTabIcon">

            <div class="space-y-6 pt-6 pb-5">

              <PositTypeToggle
                :editable="positEditorMachineState.context.canEdit"
                :posit="posit" />

              <div class="relative">

                <!-- Project Value -->
                <InputWithCurrency
                  :currency-model.sync="posit.value_currency_code"
                  :amount-model.sync="posit.value_amount"
                  :editable="positEditorMachineState.context.canEdit"
                  :max="999999999"
                  label="Project Value"
                  @changed="handleUpdateProjectValue"
                  class="space-y-1"/>

                <!-- Deposit -->
                <PositDepositConfigure
                  :posit="posit"
                  :editable="positEditorMachineState.context.canEdit"
                  class="space-y-1 mt-6"
                />

                <transition enter-active-class="ease-out duration-300"
                        enter-class="opacity-0"
                        enter-to-class="opacity-100"
                        leave-active-class="ease-in duration-200"
                        leave-class="opacity-100"
                        leave-to-class="opacity-0">

                  <div
                    v-if="!posit.includes_pricing"
                    class="absolute h-full w-full inset-0 z-20"
                    style="background-color: rgba(255, 255, 255, 0.5); backdrop-filter: blur(2px);">
                  </div>
                </transition>

              </div>

            </div>
        </TabPane>
      </Tabs>
    </div>
  </div>
</template>

<script>
import Tabs from '@/Components/Tabs'
import TabPane from '@/Components/TabPane'
import InputWithCurrency from '@/Components/TailwindUI/InputWithCurrency'
import PositDepositConfigure from '@/Components/PositDepositConfigure'
import IconHeroiconsSmallBriefcase from '@/Icons/IconHeroiconsSmallBriefcase'
import IconHeroiconsSmallAdjustments from '@/Icons/IconHeroiconsSmallAdjustments'
import IconHeroiconsMediumInformationCircle from '@/Icons/IconHeroiconsMediumInformationCircle'
import PositThemeBlock from '@/Components/PositThemeBlock'
import PositTypeToggle from '@/Components/PositTypeToggle'

export default {
  name: 'PositTweakView',
  components: {
    IconHeroiconsSmallBriefcase,
    IconHeroiconsSmallAdjustments,
    IconHeroiconsMediumInformationCircle,
    InputWithCurrency,
    PositDepositConfigure,
    PositThemeBlock,
    PositTypeToggle,
    Tabs,
    TabPane
  },
  props: {
    posit: { type: Object },
    positEditorMachineState: {}
  },
  data () {
    return {
      tabIndex: 1
    }
  },
  computed: {
    detailsTabIcon () {
      return IconHeroiconsSmallBriefcase
    },
    designTabIcon () {
      return IconHeroiconsSmallAdjustments
    }
  },
  methods: {
    async handleUpdateProjectValue () {
      if (!this.positEditorMachineState.context.canEdit) return

      try {
        await this.posit.updateProjectValue()
      } catch (e) {
        // TODO handle update project value error...
      }
    }
  }
}
</script>
