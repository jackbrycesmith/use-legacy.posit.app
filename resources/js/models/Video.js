import Model from './Model'
import Http from '@/services/Http'

export default class Video extends Model {
    exists () {
      return this.uuid !== null
    }

    get video_js_src_data () {
      return {
        src: this.url,
        type: 'video/mp4'
      }
    }
}
