import Echo from 'laravel-echo'
import getCookieNamed from '@/utils/cookie'
import { isDevEnv } from '@/utils/is'

// Make Pusher globally accessible for Laravel Echo.
window.Pusher = require('pusher-js')

const echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    // cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    auth: {
        headers: {
            'X-XSRF-TOKEN': getCookieNamed('XSRF-TOKEN'),
        }
    },
    enabledTransports: isDevEnv ? ['ws'] : ['wss'],
    wsHost: isDevEnv ? '127.0.0.1' : window.location.hostname,
    wsPort: isDevEnv ? 6001 : 443,
    forceTLS: !isDevEnv,
    // namespace: '',
})

console.log('echo: ', echo)

export default {
    subscribe (channel, listeners, publicChannel = false) {
        channel = publicChannel
            ? echo.channel(channel)
            : echo.private(channel)

        Object.keys(listeners).forEach(function (event) {
            channel.listen(event, listeners[event])
        })
    },

    unsubscribe (channel) {
        echo.leave(channel)
    },
}
