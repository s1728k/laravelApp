<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTableList extends Model
{
    public $table = "master_table_list";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_name', 'app_id', 'table_description', 'keywords', 'field_indexes', 'fillable', 'hidden',
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
