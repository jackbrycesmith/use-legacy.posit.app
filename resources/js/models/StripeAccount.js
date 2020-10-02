import Model from './Model'
import Http from '@/services/Http'

export default class StripeAccount extends Model {
    exists () {
      return !(this.id == null)
    }

    get is_stripe_integration_ok () {
      return this.charges_enabled
    }

    get stripe_charges_payouts_state_text () {
      if (this.is_stripe_integration_ok) {
        return 'Ready to receive payments into your Stripe account'
      }

      return 'Unable to receive payments, please update the connection'
    }

    async disconnect (teamUuid = '') {
      await Http.put(route('use.submit.disconnect-stripe-account', { team: teamUuid }))
    }

}
