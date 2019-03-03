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
        'app_id', 'name', 'mime', 'size', 'path',
    ];

}
