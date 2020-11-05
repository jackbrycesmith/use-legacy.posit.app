<template>
  <fragment>
    <ProposalOpeningSection
      class="mb-36"
      :editable="false"
      :proposal="proposal__" />

    <ProposalContentSectionPublic
      ref="content" />

    <ProposalClosingSectionPublic
      class="mt-36"
      :public-proposal-machine-state="publicProposalMachineCurrentState"
      :proposal="proposal__"
      @accept-with-payment="handleAcceptWithPayment"/>

    <!-- Modals -->
    <portal-target name="proposal-view-portal" />
  </fragment>
</template>

<script>
import { interpret } from 'xstate'
import { loadStripe } from '@stripe/stripe-js/pure'
import { publicProposalMachine } from '@/machines/publicProposalMachine'
import { isEmpty } from 'lodash-es'
import Http from '@/services/Http'
import Proposal from '@/models/Proposal'
import ProposalOpeningSection from '@/Components/ProposalOpeningSection'
import ProposalContentSectionPublic from '@/Components/ProposalContentSectionPublic'
import ProposalClosingSectionPublic from '@/Components/ProposalClosingSectionPublic'

export default {
  components: {
    ProposalOpeningSection,
    ProposalContentSectionPublic,
    ProposalClosingSectionPublic
  },
  props: {
    status: { type: String },
    proposal: { type: Object },
    is_draft: { type: Boolean, default: false },
    stripe_pub_key: {}
  },
  created () {
    this.publicProposalMachineService
      .onTransition(state => {
        this.publicProposalMachineCurrentState = state
        this.publicProposalMachineContext = state.context
      })
      .start()

    this.setupInitialMachineContext()
  },
  data () {
    const status = this.proposal?.data?.status

    const machine = publicProposalMachine.withContext({
      ...publicProposalMachine.context,
      status
    }).withConfig({
      services: {
        'acceptWithPaymentAction': this.acceptWithPaymentAction
      }
    })

    return {
      publicProposalMachineService: interpret(machine, { devTools: true }),
      publicProposalMachineCurrentState: machine.initialState,
      publicProposalMachineContext: machine.context,
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
    async acceptWithPaymentAction () {
      try {
        let stripeAccountId = this.proposal__?.deposit_payment?.stripe_account_id
        let stripeCheckoutSessionId = this.proposal__?.deposit_payment?.stripe_checkout_session_id

        if (isEmpty(stripeAccountId) || isEmpty(stripeCheckoutSessionId)) {
          // Do accept
          const acceptWithPaymentResponse = await Http.put(this.proposal__.route_pub_accept_with_payment)
          stripeAccountId = acceptWithPaymentResponse?.stripe_account_id
          stripeCheckoutSessionId = acceptWithPaymentResponse?.stripe_checkout_session_id
        }

        let stripe = await loadStripe(this.stripe_pub_key, {
          stripeAccount: stripeAccountId,
        })

        stripe.redirectToCheckout({
          sessionId: stripeCheckoutSessionId
        })

        // await new Promise(resolve => setTimeout(resolve, 1000))

      } catch (e) {
        console.log('acceptWithPaymentAction error: ', e)
      }
    },
    handleAcceptWithPayment () {
      this.publicProposalMachineService.send('ACCEPT_WITH_PAYMENT')
    },
    setupInitialMachineContext () {

    },
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
