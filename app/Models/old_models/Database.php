<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
    public $table = "databases";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'private', 'table_size', 'created_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
