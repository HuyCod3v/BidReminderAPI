<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Cart
 * @package App\Models
 * @version October 4, 2016, 6:06 am UTC
 */
class Cart extends Model
{
    use SoftDeletes;

    public $table = 'carts';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'product_id',
        'buy_price',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'buy_price' => 'double'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
