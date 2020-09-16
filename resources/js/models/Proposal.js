import Model from './Model'
import Http from '@/services/Http'
import User from './User'
import ProposalContent from './ProposalContent'
import Organisation from './Organisation'
import OrganisationContact from './OrganisationContact'
import Video from './Video'
import { appendOrUpdateData } from '@/utils/data'
import { get, set, head, isNil } from 'lodash-es'

export default class Proposal extends Model {

  status_name_maps () {
    return {
      proposal_draft: 'draft',
      proposal_published: 'published',
      proposal_accepted: 'accepted',
      proposal_expired: 'expired',
      proposal_void: 'void',
    }
  }

  exists () {
    return this.uuid !== null
  }

  get status_name () {
    return get(this.status_name_maps(), this.status, 'unknown')
  }

  get creator_name () {
    return get(head(this.users), 'name', '?')
  }

  get route_proposal_view () {
    return route('use.proposal.view', { proposal: this.uuid }).url()
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

  get has_intro_video () {
    return !isNil(this.intro_video)
  }

  get is_intro_video_processing () {
    return this.has_intro_video && this.intro_video.is_processing
  }

  async updateRecipient () {
    const contactId = this.recipient?.id
    if (isNil(contactId)) return

    await Http.put(route('use.proposal.recipients.update', { proposal: this.uuid, recipient: contactId }))
  }

  async addRecipient (payload) {
    const response = await Http.post(
      route('use.proposal.recipients.add-submit', { proposal: this.uuid }),
      payload
    )

    const contact = OrganisationContact.make(response)
    set(this, 'recipient', contact)
    set(this.org, 'contacts', appendOrUpdateData(contact, this.recipient_options))
    return contact
  }

  async videoIntroUpsert (uploadedUuid) {
    const response = await Http.post(
      route('use.proposal.video-intro', { proposal: this.uuid }),
      {
        uuid: uploadedUuid
      }
    )

    return response
  }

  getRelationships() {
    return {
      users: User,
      org: Organisation,
      recipient: OrganisationContact,
      content: ProposalContent,
      intro_video: Video,
    }
  }
}
