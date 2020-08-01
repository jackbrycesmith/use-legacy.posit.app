<?php

namespace App\Actions\User;

use App\Models\User;
use Lorisleiva\Actions\Action;

class CreatePersonalOrg extends Action
{
    protected $getAttributesFromConstructor = true;

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
     * @return mixed
     */
    public function handle(User $user)
    {
        // TODO put in transaction, try catch report error?
        $user->organisations()->create(['name' => strstr($user->email, '@', true)]);
    }
}
