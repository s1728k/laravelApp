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
        'app_id', 'title', 'body', 'icon', 'image', 'badge', 'sound', 'vibrate', 'dir', 'tag', 'data', 
        'requireInteraction', 'renotify', 'silent', 'actions', 'timestamp', 'lang', 
    ];

}
