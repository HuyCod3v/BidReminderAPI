<?php

use App\Models\Cart;
use App\Repositories\CartRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartRepositoryTest extends TestCase
{
    use MakeCartTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var CartRepository
     */
    protected $cartRepo;

    public function setUp()
    {
        parent::setUp();
        $this->cartRepo = App::make(CartRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateCart()
    {
        $cart = $this->fakeCartData();
        $createdCart = $this->cartRepo->create($cart);
        $createdCart = $createdCart->toArray();
        $this->assertArrayHasKey('id', $createdCart);
        $this->assertNotNull($createdCart['id'], 'Created Cart must have id specified');
        $this->assertNotNull(Cart::find($createdCart['id']), 'Cart with given id must be in DB');
        $this->assertModelData($cart, $createdCart);
    }

    /**
     * @test read
     */
    public function testReadCart()
    {
        $cart = $this->makeCart();
        $dbCart = $this->cartRepo->find($cart->id);
        $dbCart = $dbCart->toArray();
        $this->assertModelData($cart->toArray(), $dbCart);
    }

    /**
     * @test update
     */
    public function testUpdateCart()
    {
        $cart = $this->makeCart();
        $fakeCart = $this->fakeCartData();
        $updatedCart = $this->cartRepo->update($fakeCart, $cart->id);
        $this->assertModelData($fakeCart, $updatedCart->toArray());
        $dbCart = $this->cartRepo->find($cart->id);
        $this->assertModelData($fakeCart, $dbCart->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteCart()
    {
        $cart = $this->makeCart();
        $resp = $this->cartRepo->delete($cart->id);
        $this->assertTrue($resp);
        $this->assertNull(Cart::find($cart->id), 'Cart should not exist in DB');
    }
}
