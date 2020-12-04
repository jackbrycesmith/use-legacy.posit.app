<?php

namespace App\Actions\PubPositApp;

use App\Http\Resources\PositLiteResource;
use App\Http\Resources\PositResource;
use App\Models\Posit;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Inertia\Inertia;
use Lorisleiva\Actions\Action;

class PubPositView extends Action
{
    /**
     * Specify routes for this action.
     *
     * @param \Illuminate\Routing\Router $router The router
     *
     * @return void
     */
    public static function routes(Router $router)
    {
        $router->domain(pub_posit_domain())
            ->middleware(['web', 'public.posit.access'])
            ->get('/posit/{posit:uuid}', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('pub.posit.view');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Execute the action and return a result.
     *
     * @param \App\Models\Posit $posit The posit
     *
     * @return mixed
     */
    public function handle(Posit $posit)
    {
        return response()->inertiable('Pub/PositView', [
            'proposal' => fn() => $this->positResource($posit),
            'is_limited_view' => fn() => $posit->requiresLiteResource(),
            'stripe_pub_key' => fn() => config('services.stripe.key')
        ]);
    }

    /**
     * Returns the posit resource
     *
     * @param \App\Models\Posit $posit The posit
     *
     * @return PositLiteResource|PositResource
     */
    protected function positResource(Posit $posit)
    {
        if ($posit->requiresLiteResource()) {
            return new PositLiteResource($posit);
        }

        $posit->loadMissing([
            'positContent',
            'creator',
            'video',
            'depositPayment',
            'depositPayment.stripeCheckoutSession',
            'recipient'
        ]);

        return new PositResource($posit);
    }
}
