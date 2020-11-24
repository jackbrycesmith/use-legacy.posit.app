<?php

namespace App\Utils;

use Illuminate\Support\Arr;
use JsonSerializable;

class PaddleProduct implements JsonSerializable
{
    /**
     * The paddle product_id.
     *
     * @var string
     */
    public $product_id;

    /**
     * The role's credits.
     *
     * @var integer
     */
    public $credits;

    /**
     * Create a new role instance.
     *
     * @param  string  $key
     * @param  string  $name
     * @param  array  $permissions
     * @return void
     */
    public function __construct(array $productConfig)
    {
        $this->product_id = Arr::get($productConfig, 'product_id');
    }

    /**
     * The amount of in-app credits you get with this product.
     *
     * @param integer $credits The amount of credits
     *
     * @return $this
     */
    public function credits(int $credits)
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * Get the JSON serializable representation of the object.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'product_id' => $this->product_id,
            'credits' => $this->credits,
        ];
    }
}
