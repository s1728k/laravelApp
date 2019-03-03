<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    public $table = 'queries';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id', 'name', 'auth_providers', 'tables', 'commands', 'fillables', 'hiddens', 'mandatory', 'joins', 'filters', 'specials',
    ];

}
