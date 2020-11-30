<?php

namespace App\Actions\Mail;

use Illuminate\Mail\Events\MessageSending;
use Lorisleiva\Actions\Action;

class AddSesConfigurationSet extends Action
{
    public function getAttributesFromEvent(MessageSending $event)
    {
        return [
            'message' => $event->message,
            'data' => $event->data,
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        // TODO: would be nice to be able to detect the driver and then only add the header if sending mailer transport is 'ses'

        $driver = config('mail.default');

        if ('ses' !== config("mail.mailers.{$driver}.transport")) {
            return;
        }

        if (! $configuration_set = config("mail.mailers.{$driver}.options.ConfigurationSetName")) {
            return;
        }

        if ($this->message->getHeaders()->has('x-ses-configuration-set')) {
            return;
        }

        $this->message->getHeaders()->removeAll('X-SES-CONFIGURATION-SET');
        $this->message->getHeaders()->addTextHeader('X-SES-CONFIGURATION-SET', $configuration_set);
    }
}
