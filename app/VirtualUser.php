<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VirtualUser extends Model
{
    public $table = 'virtual_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'domain_id', 'email', 'password', 'mailbox', 
    ];

}
