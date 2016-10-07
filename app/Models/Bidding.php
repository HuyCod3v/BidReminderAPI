<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Bidding
 * @package App\Models
 * @version October 4, 2016, 5:57 am UTC
 */
class Bidding extends Model
{
    use SoftDeletes;

    public $table = 'biddings';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'product_id',
        'bid_price',
        'last_price',
        'is_buy_automatically',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'bid_price' => 'double',
        'last_price' => 'double',
        'is_buy_automatically' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
