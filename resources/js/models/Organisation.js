import Model from './Model'
import User from './User'
import Proposal from './Proposal'
import StripeAccount from './StripeAccount'
import Http from '@/services/Http'

export default class Organisation extends Model {
  exists() {
    return this.uuid !== null
  }

  get avatar_letter_initial () {
    return this.name?.charAt(0)?.toUpperCase()
  }

  getRelationships() {
    return {
      users: User,
      proposals: Proposal,
      stripeAccount: StripeAccount
    }
  }
}
