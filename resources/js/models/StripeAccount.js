import Model from './Model'
import Http from '@/services/Http'

export default class StripeAccount extends Model {
    exists () {
      return !(this.id == null)
    }

    async disconnect (orgUuid = '') {
      await Http.put(route('use.submit.disconnect-stripe-account', { org: orgUuid }))
    }

}
