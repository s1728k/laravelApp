<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushMessage extends Model
{
    public $table = 'push_messages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id', 'push_message',
    ];

}
