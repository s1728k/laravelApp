<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    public $table = 'apps';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'secret', 'token_lifetime', 'db_connection', 'auth_providers', 'origins', 'can_chat_with', 
        'ccac', 'blocked',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'secret', 'remember_token',
    ];

}
