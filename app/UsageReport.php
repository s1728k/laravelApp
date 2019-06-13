<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsageReport extends Model
{
    public $table = 'usage_report';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'user_id', 'app_id', 'api_calls', 'emails_sent', 'push_sent', 'chat_messages',
    ];

}
