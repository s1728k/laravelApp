<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VirtualAlias extends Model
{
    public $table = 'virtual_alias';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'email', 'domain', 'verified', 'expiry_date', 
    ];

}
