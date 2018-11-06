<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    public $table = "tenants";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rent_house_id', 'name', 'Phone', 'attach_images_id', 'rent_history_id', 'rent_rate_history_id', 'join_date', 'total_rent_agreed', 
        'rent_effective_from', 'previous_dues', 'advance_received'
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
