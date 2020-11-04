<?php

namespace App\Http\Middleware;

use App\Http\PublicProposalAccessCookie;
use App\Models\Proposal;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CheckPublicProposalStatefulAccess
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $param = null)
    {
        $proposal = $this->resolveProposal($request);

        if ($param !== 'ignore-status-check') {
            if (! in_array($proposal->status, Proposal::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES)) {
                return $next($request);
            }
        }

        if ($this->hasValidAccessCookie($request, $proposal)) {
            return $next($request);
        }

        return Redirect::route('pub.proposal.view.auth', $proposal);
    }

    /**
     * Finds the proposal for the given request
     *
     * @param \Illuminate\Http\Request $request The request
     *
     * @return Proposal the resolved proposal
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function resolveProposal(Request $request): Proposal
    {
        $proposal = app(Proposal::class)->resolveRouteBinding(
            $request->route('proposal'),
            $request->route()->bindingFieldFor('proposal')
        );

        if (is_null($proposal)) {
            abort(404);
        }

        return $proposal;
    }

    /**
     * Determines if valid access cookie for viewing this public proposal.
     *
     * @param \Illuminate\Http\Request $request The request
     * @param \App\Models\Proposal $proposal The proposal
     *
     * @return boolean True if valid access cookie, False otherwise.
     */
    protected function hasValidAccessCookie(Request $request, Proposal $proposal): bool
    {
        $cookie = $request->cookie(PublicProposalAccessCookie::cookieName(
            $proposal->team
        ));

        if (is_null($cookie)) return false;

        return PublicProposalAccessCookie::isValid($proposal, $cookie);
    }
}
