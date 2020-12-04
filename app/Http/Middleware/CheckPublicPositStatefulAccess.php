<?php

namespace App\Http\Middleware;

use App\Http\PublicPositAccessCookie;
use App\Models\Posit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CheckPublicPositStatefulAccess
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
        $posit = $this->resolvePosit($request);

        if ($param !== 'ignore-status-check') {
            if (in_array($posit->status, Posit::PUBLIC_ACCESS_AUTH_BYPASS_STATUSES)) {
                return $next($request);
            }
        }

        if ($this->hasValidAccessCookie($request, $posit)) {
            return $next($request);
        }

        return Redirect::route('pub.posit.view.auth', $posit);
    }

    /**
     * Finds the proposal for the given request
     *
     * @param \Illuminate\Http\Request $request The request
     *
     * @return Posit the resolved proposal
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function resolvePosit(Request $request): Posit
    {
        $posit = app(Posit::class)->resolveRouteBinding(
            $request->route('posit'),
            $request->route()->bindingFieldFor('posit')
        );

        if (is_null($posit) || empty($posit->status)) {
            abort(404);
        }

        return $posit;
    }

    /**
     * Determines if valid access cookie for viewing this public proposal.
     *
     * @param \Illuminate\Http\Request $request The request
     * @param \App\Models\Posit $posit The posit
     *
     * @return boolean True if valid access cookie, False otherwise.
     */
    protected function hasValidAccessCookie(Request $request, Posit $posit): bool
    {
        $cookie = $request->cookie(PublicPositAccessCookie::cookieName(
            $posit->team
        ));

        if (is_null($cookie)) return false;

        return PublicPositAccessCookie::isValid($posit, $cookie);
    }
}
