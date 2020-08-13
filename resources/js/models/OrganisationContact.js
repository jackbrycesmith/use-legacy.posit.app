import Model from './Model'

export default class OrganisationContact extends Model {

  routeEdit (orgUuid) {
    return route('use.org.contacts.edit', { contact: this.id, org: orgUuid }).url()
  }

  routeUpdate (orgUuid) {
    return route('use.org.contacts.update', { contact: this.id, org: orgUuid }).url()
  }

  formPayload () {
    return {
      name: this.name
    }
  }
}
