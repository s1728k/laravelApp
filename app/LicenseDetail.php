<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LicenseDetail extends Model
{
	public $table = "license_detail";
	
    protected $fillable = ['license_id', 'hardware_code', 'computer_name', 'computer_user'];

}
