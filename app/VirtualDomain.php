<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VirtualDomain extends Model
{
    public $table = 'virtual_domains';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'verified', 'expiry_date', 'app_id'
    ];

}
