<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentHistory extends Model
{
    public $table = "rent_history";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id', 'received_date', 'amount_paid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'content',
    // ];
}
