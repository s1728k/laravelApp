<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    public $table = 'visitors';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip_address', 'uuid', 'theme',
    ];

}
