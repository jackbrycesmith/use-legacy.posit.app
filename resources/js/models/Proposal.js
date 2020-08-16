import Model from './Model'
import Http from '@/services/Http'
import User from './User'
import ProposalContent from './ProposalContent'
import Organisation from './Organisation'
import { get } from 'lodash-es'

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

  get route_proposal_view () {
    return route('use.proposal.view', { proposal: this.uuid }).url()
  }

  getRelationships() {
    return {
      users: User,
      org: Organisation,
      content: ProposalContent,
    }
  }
}
