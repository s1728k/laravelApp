<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class F extends Authenticatable
{
use Notifiable;
public $table = 'fs';
protected $fillable = ['fsd', 'fsdf'];
protected $hidden = ['password', 'remember_token',];
}
