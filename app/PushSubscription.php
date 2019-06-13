<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
	use Notifiable;

    public $table = 'push_subscriptions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id', 'auth_provider', 'user_id', 'subscription',
    ];

}
