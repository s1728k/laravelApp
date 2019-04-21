<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $table = 'chat';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id', 'cid', 'message', 'fid', 'fap', 'fname', 'tid', 'tap', 'tname', 'style', 'status',
    ];

}
