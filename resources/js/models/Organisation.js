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

  get route_settings () {
    return route('use.org.settings', { org: this.uuid }).url()
  }

  get route_members () {
    return route('use.org.members', { org: this.uuid }).url()
  }

  getRelationships() {
    return {
      users: User,
      proposals: Proposal,
      stripeAccount: StripeAccount
    }
  }
}
