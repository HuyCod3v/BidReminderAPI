<?php

namespace App\Repositories;

use App\Models\Cart;
use InfyOm\Generator\Common\BaseRepository;

class CartRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'buy_price',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Cart::class;
    }
}
