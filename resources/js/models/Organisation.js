import Model from './Model'
import User from './User'
import Proposal from './Proposal'
import StripeAccount from './StripeAccount'
import OrganisationContact from './OrganisationContact'
import Http from '@/services/Http'
import { initials } from '@/utils/strings'

export default class Organisation extends Model {
  exists() {
    return this.uuid !== null
  }

  get avatar_letter_initial () {
    return initials(this.name)
  }

  get route_settings () {
    return route('use.team.settings', { team: this.uuid }).url()
  }

  get route_members () {
    return route('use.org.members', { org: this.uuid }).url()
  }

  get route_contacts () {
    return route('use.team.contacts', { team: this.uuid }).url()
  }

  get route_contacts_add () {
    return route('use.org.contacts.add', { org: this.uuid }).url()
  }

  get route_contacts_add_submit () {
    return route('use.org.contacts.add-submit', { org: this.uuid }).url()
  }

  get route_activity_logs () {
    return route('use.org.activity-logs', { org: this.uuid }).url()
  }

  getRelationships() {
    return {
      users: User,
      proposals: Proposal,
      stripeAccount: StripeAccount,
      contacts: OrganisationContact
    }
  }
}
