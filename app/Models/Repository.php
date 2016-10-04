<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Repository
 * @package App\Models
 * @version October 4, 2016, 6:00 am UTC
 */
class Repository extends Model
{
    use SoftDeletes;

    public $table = 'repositories';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'link'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'link' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
