<template>
  <fragment>
    <template v-if="is_limited_view">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center h-screen">
        <div class="max-w-3xl mx-auto my-auto">
          <ApplicationLogo class="h-10 w-40 mx-auto" />

          <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
            Please ask the creator to publish
          </h2>
        </div>
      </div>
    </template>

    <template v-if="!is_limited_view">
      <PositOpeningSectionPublic
        class="mb-36"
        :editable="false"
        :proposal="proposal__" />

      <PositContentSectionPublic
        ref="content" />

      <PositClosingSectionPublic
        class="mt-36"
        :public-proposal-machine-state="publicPositMachineCurrentState"
        :proposal="proposal__"
        @accept-with-payment="handleAcceptWithPayment"/>
    </template>

    <!-- Modals -->
    <portal-target name="proposal-view-portal" />
  </fragment>
</template>

<script>
import { interpret } from 'xstate'
import { loadStripe } from '@stripe/stripe-js/pure'
import { publicPositMachine } from '@/machines/publicPositMachine'
import { isEmpty } from 'lodash-es'
import Http from '@/services/Http'
import Posit from '@/models/Posit'
import ApplicationLogo from '@/Jetstream/ApplicationLogo'
import PositOpeningSectionPublic from '@/Components/PositOpeningSectionPublic'
import PositContentSectionPublic from '@/Components/PositContentSectionPublic'
import PositClosingSectionPublic from '@/Components/PositClosingSectionPublic'

export default {
  components: {
    PositOpeningSectionPublic,
    PositContentSectionPublic,
    PositClosingSectionPublic,
    ApplicationLogo
  },
  props: {
    status: { type: String },
    proposal: { type: Object },
    is_limited_view: { type: Boolean, default: true },
    stripe_pub_key: {}
  },
  created () {
    this.publicPositMachineService
      .onTransition(state => {
        this.publicPositMachineCurrentState = state
        this.publicPositMachineContext = state.context
      })
      .start()

    this.setupInitialMachineContext()
  },
  data () {
    const status = this.proposal?.data?.status

    const machine = publicPositMachine.withContext({
      ...publicPositMachine.context,
      status
    }).withConfig({
      services: {
        'acceptWithPaymentAction': this.acceptWithPaymentAction
      }
    })

    return {
      publicPositMachineService: interpret(machine, { devTools: true }),
      publicPositMachineCurrentState: machine.initialState,
      publicPositMachineContext: machine.context,
      stripe: null,
      proposal__: Posit.make()
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
        this.proposal__ = Posit.make(value)

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
      this.publicPositMachineService.send('ACCEPT_WITH_PAYMENT')
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
