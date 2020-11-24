<?php

namespace App\Utils;

use App\Utils\PaddleProduct;

class Paddle
{
    /**
     * The paddle products available for this app.
     *
     * @var array
     */
    public static $products = [];

    /**
     * Define a feature flag.
     *
     * @param array $featureConfig The feature configuration
     *
     * @return \Featica\Feature
     */
    public static function product(array $productConfig)
    {
        return tap(new PaddleProduct($productConfig), function ($product) {
            static::$products[$product->product_id] = $product;
        });
    }

}
