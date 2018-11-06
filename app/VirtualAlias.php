<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VirtualAlias extends Model
{
    public $table = 'virtual_aliases';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain_id', 'email', 'password', 'app_id',
    ];

}
