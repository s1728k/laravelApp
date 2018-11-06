<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attach extends Model
{

    public $table = "attach";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pivot_table', 'pivot_field', 'pivot_id', 'attach_type_id', 'attach_name', 'path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pivot_table', 'pivot_field', 'pivot_id', 'attach_type_id', 'attach_name'
    ];
}
