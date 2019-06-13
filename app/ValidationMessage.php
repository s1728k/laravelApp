<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValidationMessage extends Model
{   
    public $table = 'validation_messages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id', 'rule', 'error_message',
    ];

}
