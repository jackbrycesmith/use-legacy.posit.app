<?php

use App\Notifications\TelegramMeNow;
use Illuminate\Support\Facades\Notification;

if (! function_exists('app_url_clean')) {

    /**
     * The app url (without http://); e.g. posit.app
     *
     * @return string
     */
    function app_url_clean()
    {
        return parse_url(config('app.url'), PHP_URL_HOST);
    }
}

if (! function_exists('use_posit_domain')) {

    /**
     * E.g. use.posit.app
     *
     * @return string
     */
    function use_posit_domain()
    {
        return 'use.' . app_url_clean();
    }
}

if (! function_exists('pub_posit_domain')) {

    /**
     * E.g. pub.posit.app
     *
     * @return string
     */
    function pub_posit_domain()
    {
        return 'pub.' . app_url_clean();
    }
}

if (! function_exists('telegram_me_now')) {

    /**
     * Send a notification to me via telegram (site owner)
     *
     * @param string $message
     *
     * @return void
     */
    function telegram_me_now(string $message)
    {
        rescue(function () use ($message) {
            Notification::route('telegram', '')->notify(new TelegramMeNow($message));
        });
    }
}

