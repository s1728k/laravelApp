<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $table = 'files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mime', 'size', 'pivot_table', 'pivot_field', 'pivot_id', 'sr_no', 'path',
    ];

}
