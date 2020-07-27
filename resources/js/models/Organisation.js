import Model from './Model'
import User from './User'
import StripeAccount from './StripeAccount'
import Http from '@/services/Http'

export default class Organisation extends Model {
  exists() {
    return this.uuid !== null
  }

  getRelationships() {
    return {
      users: User,
      stripeAccount: StripeAccount
    }
  }
}
