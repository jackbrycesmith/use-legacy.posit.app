import Model from './Model'
import Http from '@/services/Http'

export default class Organisation extends Model {
    exists () {
      return this.uuid !== null
    }
}
