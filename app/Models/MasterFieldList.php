<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterFieldList extends Model
{
    public $table = "master_field_list";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sr', 'field_name', 'data_type'
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
