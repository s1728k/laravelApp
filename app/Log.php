<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{   
    public $table = 'logs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'aid', 'fid', 'fap', 'qid', 'auth_provider', 'query_nick_name', 'table_name', 'command', 'ip',
    ];

}
