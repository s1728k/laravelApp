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
        'user_id', 'name', 'secret', 'origins', 'permissions', 'auth_providers', 'table_filters', 'blocked',
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
