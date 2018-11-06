<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentRateHistory extends Model
{
    public $table = "rent_rate_history";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id', 'rent_effective_from', 'total_rent_agreed'
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
