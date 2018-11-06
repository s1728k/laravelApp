<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
	public $table = "tables";
    
    protected $fillable = ['name', 'app_id', 'fillable', 'hidden', 'uses', 'fields'];

    protected $hidden = [
        
    ];
}
