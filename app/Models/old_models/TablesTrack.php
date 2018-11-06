<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TablesTrack extends Model
{
    public $table = "tables_track";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_name', 'table_group_name', 'db_name', 'table_description', 'keywords', 'private', 
        'field_indexes', 'fillable', 'hidden', 'created_by'
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
