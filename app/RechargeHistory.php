<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RechargeHistory extends Model
{
    public $table = 'recharge_history';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'plan', 'status', 'expiry_date', 'recharge_date', 'recharge_amount', 'tax', 'top_up',
    ];

}
