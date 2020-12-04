import Model from './Model'
import User from './User'
import Posit from './Posit'
import StripeAccount from './StripeAccount'
import TeamContact from './TeamContact'
import { initials } from '@/utils/strings'

export default class Team extends Model {
  exists() {
    return this.uuid !== null
  }

  get avatar_letter_initial () {
    return initials(this.name)
  }

  get has_setup_payment_provider () {
    // TODO don't hardcode stripe check...
    return this.stripeAccount?.has_card_payments_capability ?? false
  }

  get has_sent_first_proposal () {
    return this.published_posits_count >= 1
  }

  get route_settings () {
    return route('use.team.settings', { team: this.uuid })
  }

  get route_credits_paddle_pay_link () {
    return route('use.team.credits-paddle-pay-link', { team: this.uuid })
  }

  get route_contacts () {
    return route('use.team.contacts', { team: this.uuid })
  }

  get route_credits () {
    return route('use.team.credits', { team: this.uuid })
  }

  get route_contacts_add () {
    return route('use.team.contacts.add', { team: this.uuid })
  }

  get route_contacts_add_submit () {
    return route('use.team.contacts.add-submit', { team: this.uuid })
  }

  get route_activity_logs () {
    return route('use.org.activity-logs', { org: this.uuid })
  }

  getRelationships() {
    return {
      users: User,
      posits: Posit,
      stripeAccount: StripeAccount,
      contacts: TeamContact
    }
  }
}
