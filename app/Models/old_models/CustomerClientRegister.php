<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerClientRegister extends Model
{

    public $table = "customer_client_register";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'client_id', 'app_id', 'customer_client_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'pivot_table', 'pivot_field', 'pivot_id', 'attach_type_id', 'attach_name'
    ];
}
