<?php

use App\Utils\Paddle;
use App\Utils\PaddleProduct;

test('adds product to static property', function () {
    $product = Paddle::product(['product_id' => 1]);

    assertInstanceOf(PaddleProduct::class, $product);
    assertContains(1, array_keys(Paddle::$products));
    assertEquals($product, Paddle::$products[1]);
});

test('sets credit to product & returns correct credit amount', function () {
    $product = Paddle::product(['product_id' => 1])->credits(30);

    assertInstanceOf(PaddleProduct::class, $product);
    assertContains(1, array_keys(Paddle::$products));
    assertEquals($product, Paddle::$products[1]);
    assertEquals(30, Paddle::creditsForProduct(1));
});
