<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    public $table = 'auth';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cat', 'subcat',  'value1',  'value2',  'value3',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'secret', 'remember_token',
    // ];

}
