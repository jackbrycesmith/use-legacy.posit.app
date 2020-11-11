<template>
  <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 sm:mt-10">
    <div class="max-w-4xl mx-auto">
      <dl class="rounded-lg grid grid-cols-2">
        <div class="flex flex-col border-gray-100 p-6 text-center border-0 border-r">
          <dt class="order-2 mt-2 text-md leading-6 font-medium text-gray-500 uppercase">
            Made
          </dt>
          <dd class="order-1">
            <div class="h-11">
              <ProposalCreatorAvatar :proposal="proposal" />
            </div>
          </dd>
        </div>

        <div class="flex flex-col border-gray-100 p-6 text-center border-0 border-l">
          <dt class="order-2 mt-2 text-md leading-6 font-medium text-gray-500 uppercase">
            For
          </dt>
          <dd class="order-1">
            <ProposalRecipientSelector
              :can-bounce="false"
              :editable="editable"
              :proposal.sync="proposal"
              :options="proposal.recipient_options"
              fragment-item-class="mx-2 h-11"
            />
          </dd>
        </div>
      </dl>
    </div>
  </div>

</template>

<script>
import ProposalRecipientSelector from '@/Components/ProposalRecipientSelector'
import ProposalCreatorAvatar from '@/Components/ProposalCreatorAvatar'

export default {
  name: 'ProposalCreatorRecipientMeta',
  components: {
    ProposalRecipientSelector,
    ProposalCreatorAvatar
  },
  props: {
    proposal: { type: Object },
    editable: {
      type: Boolean,
      default: true
    }
  },
  data () {
    return {

    }
  }
}
</script>
