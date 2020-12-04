<?php

namespace App\Http;

use App\Models\Posit;
use App\Models\Team;
use App\Models\TeamContact;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Cookie;

class PublicPositAccessCookie
{
    /**
     * Create a new proposal access cookie.
     *
     * @param \App\Models\TeamContact $contact The contact
     *
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public static function create(TeamContact $contact)
    {
        $expiresAt = Carbon::now()->addDays(
            config('posit-settings.public_proposal_access_cookie_expiry_days')
        );

        $cookieName = self::cookieName($contact->team);

        return new Cookie($cookieName, base64_encode(json_encode([
            'contact' => encrypt($contact->uuid, false),
            'expires_at' => $expiresAt->getTimestamp(),
            'mac' => hash_hmac('SHA256', $expiresAt->getTimestamp(), $contact->access_code),
        ])), $expiresAt);
    }

    /**
     * Determine if the given proposal access cookie is valid.
     *
     * @param \App\Models\Posit $posit The posit
     * @param string $cookie
     *
     * @return bool
     */
    public static function isValid(Posit $posit, string $cookie)
    {
        $payload = json_decode(base64_decode($cookie), true);
        if (! is_array($payload)) return false;

        // Check for organisation_contact uuid in cookie
        $cookieContactValue = Arr::get($payload, 'contact');
        if (is_null($cookieContactValue)) return false;
        $contactUuid = decrypt($cookieContactValue, false);
        if (! Uuid::isValid($contactUuid)) return false;

        // Check this organisation contact exists in the proposal org
        $contact = TeamContact::where([
            'team_id' => $posit->team_id,
            'uuid' => $contactUuid
        ])->first();
        if (is_null($contact)) return false;

        // Check that it is a proposal recipient...
        $isProposalRecipient = $posit->recipients()
            ->where('team_contact_id', $contact->id)
            ->exists();
        if (! $isProposalRecipient) return false;

        return is_numeric($payload['expires_at'] ?? null) &&
            isset($payload['mac']) &&
            hash_equals(hash_hmac('SHA256', $payload['expires_at'], $contact->access_code), $payload['mac']) &&
            (int) $payload['expires_at'] >= Carbon::now()->getTimestamp();
    }

    /**
     * Returns the name for the team access cookie
     *
     * @param \App\Models\Team $team The team
     *
     * @return string
     */
    public static function cookieName(Team $team): string
    {
        return "team_{$team->uuid}";
    }

}
