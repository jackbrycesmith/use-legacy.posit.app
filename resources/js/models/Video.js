import Model from './Model'
import Http from '@/services/Http'
import { isNil } from 'lodash-es'

export default class Video extends Model {
  exists () {
    return this.uuid !== null
  }

  get has_poster () {
    return !isNil(this.poster_url)
  }

  get video_js_src_data () {
    return {
      src: this.url,
      type: 'video/mp4'
    }
  }
}
