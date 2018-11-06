<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App10_house_owner extends Authenticatable
{
use Notifiable;
public $table = 'app10_house_owner';
protected $fillable = ['name', 'email', 'password', 'srid', 'email_verification', 'blocked'];
protected $hidden = ['password', 'remember_token',];
}
