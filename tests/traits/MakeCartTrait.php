<?php

use Faker\Factory as Faker;
use App\Models\Cart;
use App\Repositories\CartRepository;

trait MakeCartTrait
{
    /**
     * Create fake instance of Cart and save it in database
     *
     * @param array $cartFields
     * @return Cart
     */
    public function makeCart($cartFields = [])
    {
        /** @var CartRepository $cartRepo */
        $cartRepo = App::make(CartRepository::class);
        $theme = $this->fakeCartData($cartFields);
        return $cartRepo->create($theme);
    }

    /**
     * Get fake instance of Cart
     *
     * @param array $cartFields
     * @return Cart
     */
    public function fakeCart($cartFields = [])
    {
        return new Cart($this->fakeCartData($cartFields));
    }

    /**
     * Get fake data of Cart
     *
     * @param array $postFields
     * @return array
     */
    public function fakeCartData($cartFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'product_id' => $fake->word,
            'buy_price' => $fake->word,
            'user_id' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $cartFields);
    }
}
