<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValidationRule extends Model
{   
    public $table = 'validation_rules';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id', 'field', 'rule',
    ];

}
