<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartApiTest extends TestCase
{
    use MakeCartTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateCart()
    {
        $cart = $this->fakeCartData();
        $this->json('POST', '/api/v1/carts', $cart);

        $this->assertApiResponse($cart);
    }

    /**
     * @test
     */
    public function testReadCart()
    {
        $cart = $this->makeCart();
        $this->json('GET', '/api/v1/carts/'.$cart->id);

        $this->assertApiResponse($cart->toArray());
    }

    /**
     * @test
     */
    public function testUpdateCart()
    {
        $cart = $this->makeCart();
        $editedCart = $this->fakeCartData();

        $this->json('PUT', '/api/v1/carts/'.$cart->id, $editedCart);

        $this->assertApiResponse($editedCart);
    }

    /**
     * @test
     */
    public function testDeleteCart()
    {
        $cart = $this->makeCart();
        $this->json('DELETE', '/api/v1/carts/'.$cart->iidd);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/carts/'.$cart->id);

        $this->assertResponseStatus(404);
    }
}
