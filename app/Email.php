<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{   
    public $table = 'emails';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id', 'email',
    ];

}
