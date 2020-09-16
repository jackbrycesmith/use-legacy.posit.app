import Echo from 'laravel-echo'
import getCookieNamed from '@/utils/cookie'

// Make Pusher globally accessible for Laravel Echo.
window.Pusher = require('pusher-js')

const echo = new Echo({
    broadcaster: 'pusher',
    key: 'abc4dkj39aos',
    // cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    auth: {
        headers: {
            'X-XSRF-TOKEN': getCookieNamed('XSRF-TOKEN'),
        }
    },
    enabledTransports: ['ws'],
    wsHost: '127.0.0.1', //window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
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
