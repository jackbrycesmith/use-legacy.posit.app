import Model from './Model'
import Http from '@/services/Http'
import User from './User'
import ProposalContent from './ProposalContent'
import Organisation from './Organisation'

export default class Proposal extends Model {
  exists () {
    return this.uuid !== null
  }

  getRelationships() {
    return {
      users: User,
      org: Organisation,
      content: ProposalContent,
    }
  }
}
