import Model from './Model'

export default class OrganisationContact extends Model {

  routeEdit (orgUuid) {
    return route('use.team.contacts.edit', { contact: this.id, team: orgUuid })
  }

  routeUpdate (orgUuid) {
    return route('use.team.contacts.update', { contact: this.id, team: orgUuid })
  }

  formPayload () {
    return {
      name: this.name
    }
  }
}
