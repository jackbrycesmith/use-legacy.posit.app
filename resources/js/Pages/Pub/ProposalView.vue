<template>
  <fragment>
    <ProposalOpeningSection
      class="mb-36"
      :editable="false"
      :proposal="proposal__" />

    <ProposalContentSectionPublic
      ref="content" />

    <ProposalClosingSection
      class="mt-36"
      :proposal="proposal__"/>

    <!-- Modals -->
    <portal-target name="proposal-view-portal" />
  </fragment>
</template>

<script>
import { loadStripe } from '@stripe/stripe-js/pure'
import Proposal from '@/models/Proposal'
import ProposalOpeningSection from '@/Components/ProposalOpeningSection'
import ProposalContentSectionPublic from '@/Components/ProposalContentSectionPublic'
import ProposalClosingSection from '@/Components/ProposalClosingSection'

export default {
  components: {
    ProposalOpeningSection,
    ProposalContentSectionPublic,
    ProposalClosingSection
  },
  props: {
    status: { type: String },
    proposal: { type: Object },
    is_draft: { type: Boolean, default: false },
    stripe_pub_key: {}
  },
  data () {
    return {
      stripe: null,
      proposal__: Proposal.make()
    }
  },
  metaInfo () {
    return {
      htmlAttrs: {
        class: ['h-full', this.htmlBgColorClass]
      },
      title: this.proposal__.name
    }
  },
  computed: {
    htmlBgColorClass () {
      return 'bg-gray-50'
    }
  },
  watch: {
    proposal: {
      immediate: true,
      handler (value) {
        this.proposal__ = Proposal.make(value)

        this.$nextTick(() => {
          this.$refs.content.editor.setContent(
            this.proposal__.content?.content
          )
        })
      }
    }
  },
  mounted () {
    loadStripe.setLoadParameters({ advancedFraudSignals: false })
    // setTimeout(async () => {
    //   let stripe = await loadStripe(this.stripe_pub_key, {
    //     stripeAccount: this.proposal.data.stripe_account_id
    //   })
    //   this.stripe = stripe
    //   // (async function(thing =) {
    //   // })()
    //   console.log(this.proposal, this.stripe_pub_key)
    // }, 1000)
  },
  methods: {
    handlePaymentClick () {
      this.stripe.redirectToCheckout({
        // Make the id field from the Checkout Session creation API response
        // available to this file, so you can provide it as argument here
        // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
        sessionId: this.proposal.data.stripe_checkout_session_id
      })
    }
  }
}
</script>
