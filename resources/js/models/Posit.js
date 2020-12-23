import Model from './Model'
import Http from '@/services/Http'
import User from './User'
import PositContent from './PositContent'
import PositPayment from './PositPayment'
import Team from './Team'
import TeamContact from './TeamContact'
import Video from './Video'
import { appendOrUpdateData } from '@/utils/data'
import { initials } from '@/utils/strings'
import { defaultCurrencies } from '@/data/currencies'
import { mapProposalStatusHuman, mapProposalStatusColor } from '@/data/proposal'
import { get, set, head, omitBy, find, isNil, isEmpty, toArray, words, trim } from 'lodash-es'

export default class Posit extends Model {

  exists () {
    return this.uuid !== null
  }

  get includes_pricing () {
    return this.type === 'accept_and_pay'
  }

  set includes_pricing (value) {
    console.log('includes_pricing: ', value)
    if (value) {
      this.type = 'accept_and_pay'
    } else {
      this.type = 'accept_only'
    }

    // TODO update on server (debounced)
  }

  get status_name () {
    return get(mapProposalStatusHuman, this.state, 'unknown')
  }

  get status_color () {
    return get(mapProposalStatusColor, this.state)
  }

  get has_been_published () {
    return ['published', 'accepted', 'expired'].includes(this.state)
  }

  get owner_credits_amount_available ()  {
    return this.org?.in_app_credit_balance ?? 0
  }

  get is_editable () {
    return ['draft'].includes(this.state)
  }

  get is_accepted () {
    return this.state === 'accepted'
  }

  get creator_name () {
    return get(this.creator, 'name')
  }

  get creator_initials () {
    const name = this.creator_name
    const email = this.creator?.email

    if (! isEmpty(name)) {
      return initials(name)
    }

    return isEmpty(email) ? '?' : initials(email)
  }

  get creator_profile_photo_url () {
    return this.creator?.profile_photo_url
  }

  get creator_has_profile_photo () {
    return !isEmpty(this.creator_profile_photo_url)
  }

  get route_posit_view () {
    return route('use.posit.view', { posit: this.uuid })
  }

  get route_pub_posit_view_link () {
    return route('pub.posit.view', { posit: this.uuid })
  }

  get route_pub_accept_with_payment () {
     return route('pub.posit.accept-with-payment', { posit: this.uuid })
  }

  get has_recipient () {
    return !isNil(this.recipient)
  }

  get recipient_options () {
    return get(this.org, 'contacts', [])
  }

  get recipient_name () {
    return get(this.recipient, 'name')
  }

  get recipient_access_code () {
    return get(this.recipient, 'access_code')
  }

  get convenient_copyable_recipient_access_message () {
    const name = head(words(this.recipient_name))

    return `Hi ${name},\n\nI've made this proposal for you at:\n\n${this.route_pub_posit_view_link}\n\nYou'll need to use this access code:\n\n${this.recipient_access_code}\n\nLet me know what you think!`
  }

  get has_intro_video () {
    return !isNil(this.intro_video)
  }

  get is_intro_video_processing () {
    return this.has_intro_video && this.intro_video.is_processing
  }

  get deposit_amount () {
    return get(this.deposit_payment, 'amount')
  }

  get is_selected_payment_provider_ready () {
    // TODO don't hardcode stripe check...
    return this.org?.stripeAccount?.has_card_payments_capability ?? false
  }

  get has_set_mininum_deposit () {
    return this.deposit_amount >= 1
  }

  get has_set_mininum_project_value_amount () {
    return this.value_amount >= 1
  }

  get is_deposit_greater_than_project_value () {
    return this.deposit_amount > this.value_amount
  }

  get total_amount_display_format () {
    const locale = isEmpty(navigator.language) ? 'en' : navigator.language

    let formatted

    try {
      formatted = new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: this.value_currency_code,
        maximumFractionDigits: 2,
        minimumFractionDigits: 0,
      }).format(this.value_amount)
    } catch (e) {
      formatted = this.value_amount
    }

    return formatted
  }

  get deposit_amount_display_format () {
    const amount = this.deposit_amount ?? 0

    const locale = isEmpty(navigator.language) ? 'en' : navigator.language
    let formatted

    try {
      formatted = new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: this.value_currency_code,
        maximumFractionDigits: 2,
        minimumFractionDigits: 0,
      }).format(amount)
    } catch (e) {
      formatted = amount
    }

    return formatted
  }

  get to_fix_before_publish () {

    return toArray(omitBy([
      !this.has_recipient ? {
        icon: 'warning',
        text: `You need a recipient!`
      } : null,

      !this.is_selected_payment_provider_ready ? {
        icon: 'warning',
        text: `Payment provider not ready`
      } : null,

      !this.has_set_mininum_project_value_amount ? {
        icon: 'warning',
        text: `Project value must be at least 1 ${this.value_currency_code}`
      } : null,

      !this.has_set_mininum_deposit ? {
        icon: 'warning',
        text: `Deposit must be at least 1 ${this.value_currency_code}`
      } : null,

      this.is_deposit_greater_than_project_value ? {
        icon: 'warning',
        text: 'Deposit cannot exceed the project value'
      } : null,

      // TODO Has at least one block
    ], isNil))
  }

  get isDraft () {
    return this.state === 'draft'
  }

  get has_things_to_fix_before_publish () {
    return this.to_fix_before_publish.length >= 1
  }

  get can_publish () {
    return this.isDraft && !this.has_things_to_fix_before_publish
  }

  set deposit_amount (amount) {
    if (isNil(this.deposit_payment)) {
      this.deposit_payment = PositPayment.make({ amount })
      return
    }

    set(this.deposit_payment, 'amount', amount)
  }

  async updateRecipient () {
    const contactId = this.recipient?.id
    if (isNil(contactId)) return

    await Http.put(route('use.posit.recipients.update', { posit: this.uuid, recipient: contactId }))
  }

  async updateProjectValue () {
    const response = await Http.put(
      route('use.submit.upsert-posit-value', { posit: this.uuid }),
      {
        value_amount: this.value_amount,
        value_currency_code: this.value_currency_code,
      }
    )

    return response
  }

  async updatePositType () {
    const response = await Http.put(
      route('use.submit.update-posit-type', { posit: this.uuid }),
      {
        type: this.type
      }
    )

    return response
  }

  async updateDepositAmount () {
    const response = await Http.put(
      route('use.submit.upsert-posit-deposit', { posit: this.uuid }),
      {
        amount: this.deposit_amount,
      }
    )

    return response
  }

  async publish () {
    const response = await Http.put(
      route('use.submit.publish-posit', { posit: this.uuid })
    )

    this.state = 'published'

    return response
  }

  async addRecipient (payload) {
    const response = await Http.post(
      route('use.posit.recipients.add-submit', { posit: this.uuid }),
      payload
    )

    const contact = TeamContact.make(response)
    set(this, 'recipient', contact)
    set(this.org, 'contacts', appendOrUpdateData(contact, this.recipient_options))
    return contact
  }

  async videoIntroUpsert (uploadedUuid) {
    const response = await Http.post(
      route('use.posit.video-intro', { posit: this.uuid }),
      {
        uuid: uploadedUuid
      }
    )

    return response
  }

  getRelationships() {
    return {
      users: User,
      org: Team,
      creator: User,
      recipient: TeamContact,
      content: PositContent,
      intro_video: Video,
      deposit_payment: PositPayment
    }
  }
}
