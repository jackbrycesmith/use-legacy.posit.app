import Model from './Model'
import { omitBy, isNil } from 'lodash-es'

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

      return omitBy([
        {
          icon: 'check',
          text: 'Ready to receive payments into your Stripe Account',
        },
        this.has_card_payments_capability ? {
          icon: 'check',
          text: 'Card related payments enabled'
        } : null,
      ], isNil)
    }

    get account_link_text () {
      if (this.details_submitted) {
        return 'Update Stripe Account'
      }

      return 'Complete Stripe Setup'
    }
}
