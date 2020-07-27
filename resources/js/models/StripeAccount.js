import Model from './Model'
import Http from '@/services/Http'

export default class StripeAccount extends Model {
    exists () {
      return !(this.id == null)
    }

    get is_stripe_integration_ok () {
      return this.charges_enabled && this.payouts_enabled
    }

    get stripe_charges_payouts_state_text () {
      if (this.is_stripe_integration_ok) {
        return 'Ready to take payments & receive payouts'
      }

      return 'Integration is not ready'
    }

    async disconnect (orgUuid = '') {
      await Http.put(route('use.submit.disconnect-stripe-account', { org: orgUuid }))
    }

}
