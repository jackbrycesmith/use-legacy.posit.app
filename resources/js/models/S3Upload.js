import Model from './Model'
import Http from '@/services/Http'

export default class S3Upload extends Model {

  async store(options = {}) {
    const response = await Http.post(route('use.submit.signed-storage-url'), {
      'bucket': options.bucket || '',
      'content_type': options.contentType || this.file.type,
      'expires': options.expires || '',
      'visibility': options.visibility || ''
    })

    console.log(response)
    let headers = response.headers

    if ('Host' in headers) {
        delete headers.Host
    }

    if (typeof options.progress === 'undefined') {
        options.progress = () => {}
    }

    const cancelToken = options.cancelToken || ''

    await Http.put(response.url, this.file, {
        cancelToken: cancelToken,
        headers: headers,
        onUploadProgress: (progressEvent) => {
            options.progress(progressEvent.loaded / progressEvent.total)
        }
    })

    // response.data.extension = file.name.split('.').pop()

    return response
  }
}
