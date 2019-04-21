<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ccac extends Model
{   
    public $table = 'ccac';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id', 'signup', 'login', 'sevc', 've', 'ap',
    ];

}
