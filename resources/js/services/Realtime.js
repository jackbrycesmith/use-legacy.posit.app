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
    enabledTransports: ['ws', 'wss'],
    wsHost: window.location.hostname,
    wsPort: 443,
    wsPath: '/ws',
    wssHost: window.location.hostname,
    wssPort: 443,
    forceTLS: true,
    // namespace: '',
})

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
