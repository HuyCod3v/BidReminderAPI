<?php

use Faker\Factory as Faker;
use App\Models\Bidding;
use App\Repositories\BiddingRepository;

trait MakeBiddingTrait
{
    /**
     * Create fake instance of Bidding and save it in database
     *
     * @param array $biddingFields
     * @return Bidding
     */
    public function makeBidding($biddingFields = [])
    {
        /** @var BiddingRepository $biddingRepo */
        $biddingRepo = App::make(BiddingRepository::class);
        $theme = $this->fakeBiddingData($biddingFields);
        return $biddingRepo->create($theme);
    }

    /**
     * Get fake instance of Bidding
     *
     * @param array $biddingFields
     * @return Bidding
     */
    public function fakeBidding($biddingFields = [])
    {
        return new Bidding($this->fakeBiddingData($biddingFields));
    }

    /**
     * Get fake data of Bidding
     *
     * @param array $postFields
     * @return array
     */
    public function fakeBiddingData($biddingFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'product_id' => $fake->word,
            'repository_id' => $fake->word,
            'bid_price' => $fake->word,
            'last_price' => $fake->word,
            'is_buy_automatically' => $fake->word,
            'user_id' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $biddingFields);
    }
}
