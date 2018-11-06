<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableGroup extends Model
{
    public $table = "table_groups";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'database_name', 'private', 'table_size', 'created_by'
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
