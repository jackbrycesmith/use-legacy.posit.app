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

            <!-- Proposal Theme -->
            <div class="space-y-1">
              <label for="proposal_theme" class="block text-sm font-medium leading-5 text-gray-600">
                Theme
              </label>

              <!-- Theme list options -->
              <div class="flex gap-2 items-center">
                <ProposalThemeBlock name="Cool Grey" class="flex-shrink-0" />
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

              <!-- Project Value -->
              <InputWithCurrency
                :currency-model.sync="proposal.value_currency_code"
                :amount-model.sync="proposal.value_amount"
                :editable="proposalEditorMachineState.context.canEdit"
                :max="999999999"
                label="Project Value"
                @changed="handleUpdateProjectValue"
                class="space-y-1" />

              <!-- Deposit -->
              <ProposalDepositConfigure
                :proposal="proposal"
                class="space-y-1"
              />

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
import ProposalDepositConfigure from '@/Components/ProposalDepositConfigure'
import IconHeroiconsSmallBriefcase from '@/Icons/IconHeroiconsSmallBriefcase'
import IconHeroiconsSmallAdjustments from '@/Icons/IconHeroiconsSmallAdjustments'
import IconHeroiconsMediumInformationCircle from '@/Icons/IconHeroiconsMediumInformationCircle'
import ProposalThemeBlock from '@/Components/ProposalThemeBlock'

export default {
  name: 'ProposalTweakView',
  components: {
    IconHeroiconsSmallBriefcase,
    IconHeroiconsSmallAdjustments,
    IconHeroiconsMediumInformationCircle,
    InputWithCurrency,
    ProposalDepositConfigure,
    ProposalThemeBlock,
    Tabs,
    TabPane
  },
  props: {
    proposal: { type: Object },
    proposalEditorMachineState: {}
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
  created () {
    console.log('created ProposalTweakView')
  },
  methods: {
    async handleUpdateProjectValue () {
      try {
        await this.proposal.updateProjectValue()
      } catch (e) {
        // TODO handle update project value error...
      }
    }
  }
}
</script>
