<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentHouse extends Model
{
    public $table = "rent_house";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenants_id', 'house_images_id', 'house_address', 'size', 'solo_rent_price', 'rent_price_shared', 'advance', 'advance_shared', 'persons_allowed', 
        'allow_sharing', 'availability', 'rent_mode'
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
