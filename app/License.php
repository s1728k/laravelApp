<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
	public $table = "license";
    
    protected $fillable = ['license_key', 'total_licenses', 'activated_licenses', 'created_by', 'expiry_date', 'price_id'];

    protected $hidden = [
        'created_by'
    ];
}
