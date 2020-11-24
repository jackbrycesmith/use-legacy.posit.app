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
     * @param array $productConfig The feature configuration
     *
     * @return PaddleProduct
     */
    public static function product(array $productConfig)
    {
        return tap(new PaddleProduct($productConfig), function ($product) {
            static::$products[$product->product_id] = $product;
        });
    }

    /**
     * Get the credit amount for the given paddle product id.
     *
     * @param integer $productId The product identifier
     *
     * @return int
     */
    public static function creditsForProduct(int $productId): int
    {
        return (int) optional(static::$products[$productId], function ($product) {
            return $product->credits;
        });
    }
}
