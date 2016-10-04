<?php

namespace App\Repositories;

use App\Models\Bidding;
use InfyOm\Generator\Common\BaseRepository;

class BiddingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'repository_id',
        'bid_price',
        'last_price',
        'is_buy_automatically',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Bidding::class;
    }
}
