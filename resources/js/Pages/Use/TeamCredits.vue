<template>
  <!-- 3 column wrapper -->
  <div class="flex-grow w-full max-w-7xl mx-auto xl:px-8 lg:flex">
    <!-- Left sidebar & main wrapper -->
    <div class="flex-1 min-w-0 xl:flex">

      <TeamDashboardSidebar :team="team__" />

      <!-- Credits Page -->
      <div class="lg:min-w-0 lg:flex-1">
        <div class="pl-4 pr-6 pt-4 pb-4 border-b border-t border-gray-200 sm:pl-6 lg:sticky lg:bg-primary-yellow-50 lg:top-16 lg:z-10 lg:pl-8 xl:pl-6 xl:pt-6 xl:border-t-0">
          <div class="flex items-center">
            <h1 class="flex-1 text-3xl leading-7 font-bold">Credits</h1>

            <DisplayCreditBalance
              :amount="team__.in_app_credit_balance"/>
          </div>
        </div>

        <GetMoreCredits
          :paddle-pay-link-route="team__.route_credits_paddle_pay_link"
          :paddle-products="paddle_products"
          class="m-10" />

      </div>
    </div>

    <!-- Credit History/Transactions -->
    <div class="pr-4 sm:pr-6 lg:pr-8 lg:flex-shrink-0 lg:border-l lg:border-gray-200 xl:pr-0">
      <div class="pl-6 mb-3 lg:w-80 lg:sticky lg:top-24">

        <!-- <CreditsHistorySnippet class="mt-6" /> -->

        <GettingStartedWelcome
          :team="team__"
          class="mt-6" />

      </div>
    </div>

  </div>
</template>

<script>
import TeamDashboardSidebar from '@/Components/TeamDashboardSidebar'
import CreditsHistorySnippet from '@/Components/CreditsHistorySnippet'
import GettingStartedWelcome from '@/Components/GettingStartedWelcome'
import DisplayCreditBalance from '@/Components/DisplayCreditBalance'
import GetMoreCredits from '@/Components/GetMoreCredits'
import Team from '@/models/Team'
import Dashboard from '@/Layouts/Dashboard'

export default {
  name: 'TeamCredits',
  components: {
    TeamDashboardSidebar,
    GettingStartedWelcome,
    DisplayCreditBalance,
    GetMoreCredits
  },
  props: {
    team: { type: Object },
    paddle_vendor_id: { type: Number },
    paddle_products: { },
  },
  layout: Dashboard,
  metaInfo () {
    return {
      script: [
        {
          src: 'https://cdn.paddle.com/paddle/paddle.js',
          'async': true,
          defer: true,
          callback: this.handlePaddleJsLoaded
        }
      ]
    }
  },
  data () {
    return {
      team__: Team.make(),
      paddleLoaded: false
    }
  },
  watch: {
    team: {
      immediate: true,
      handler (value) {
        this.team__ = Team.make(value)
      }
    },
  },
  methods: {
    handlePaddleJsLoaded () {
      window.Paddle.Setup({
        vendor: this.paddle_vendor_id
      })

      this.paddleLoaded = true
    }
  }
}
</script>
