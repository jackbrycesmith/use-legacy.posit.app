<?php

namespace App\Utils;

use CloudCreativity\LaravelStripe\Contracts\Connect\StateProviderInterface;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class StripeOauthSessionState implements StateProviderInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Request
     */
    private $request;

    /**
     * SessionState constructor.
     *
     * @param Session $session
     * @param Request $request
     */
    public function __construct(Session $session, Request $request)
    {
        $this->session = $session;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function get()
    {
        $sessionToken = $this->session->token();

        if ($this->request->isStripeConnectOauthStart()) {
            $teamUuid = $this->request->segment(2);
            // TODO? check $teamUuid correctness; uuid or exists?
            return "{$sessionToken}.{$teamUuid}";
        }

        return $sessionToken;
    }

    /**
     * @inheritDoc
     */
    public function check($value)
    {
        return $this->get() === $value;
    }

    /**
     * @inheritDoc
     */
    public function user()
    {
        return $this->request->user();
    }

}
