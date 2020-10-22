import Model from './Model'
import { omitBy, isNil, toArray } from 'lodash-es'

export default class StripeAccount extends Model {
    exists () {
      return !(this.id == null)
    }

    get integration_info_points () {
      if (!this.details_submitted || !this.charges_enabled) {
        return [
          {
            icon: 'warning',
            text: 'Not ready to receive payments, please complete setup with Stripe',
          }
        ]
      }

      if (!this.charges_enabled) {
        return [
          {
            icon: 'warning',
            text: 'Not ready to receive payments, please complete setup with Stripe',
          }
        ]
      }

      return toArray(omitBy([
        {
          icon: 'check',
          text: 'Ready to receive payments into your Stripe Account',
        },
        this.has_card_payments_capability ? {
          icon: 'check',
          text: 'Card related payments enabled'
        } : null,
        this.has_bacs_debit_payments_capability ? {
          icon: 'check',
          text: 'BACS Direct Debit payments enabled'
        } : {
          icon: 'info',
          text: `
            <a class="text-blue-500 font-medium" href="https://dashboard.stripe.com/settings/payments" target=”_blank” rel=”noopener noreferrer”>
              Enable other payment methods in Stripe (e.g. BACS Direct Debit) »
            </a>
          `
        },
      ], isNil))
    }

    get account_link_text () {
      if (this.details_submitted) {
        return 'Update Stripe Account'
      }

      return 'Complete Stripe Setup'
    }
}
