<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyApp extends Model
{
    public $table = "my_apps";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'privacy', 'forks', 'created_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];
}
