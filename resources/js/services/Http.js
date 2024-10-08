import axios from 'axios'
import createAuthRefreshInterceptor from 'axios-auth-refresh'

createAuthRefreshInterceptor(axios, async (failedRequest) => {
  try {
    await axios.post('/api/refresh-csrf-token')
  } catch (e) {}
}, {
  statusCodes: [419]
})

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.withCredentials = true

export default {
  async get (url, config = {}) {
    return this.send('get', url, {}, config)
  },

  async post (url, data = {}, config = {}) {
    return this.send('post', url, data, config)
  },

  async put (url, data = {}, config = {}) {
    return this.send('put', url, data, config)
  },

  async patch (url, data = {}, config = {}) {
    return this.send('patch', url, data, config)
  },

  async delete (url, data = {}, config = {}) {
    return this.send('delete', url, data, config)
  },

  async send (method, url, data = {}, config = {}) {
    const response = await axios({ method, url, data, ...config })
    return response.data
  },
}
