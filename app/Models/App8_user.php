<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App8_user extends Authenticatable
{
use Notifiable;
public $table = 'app8_users';
protected $fillable = [
'name', 'email', 'password', 'session', 'srid', 'email_varification', 'blocked', ];
protected $hidden = ['password', 'remember_token',];
}
