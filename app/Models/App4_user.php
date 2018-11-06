<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App4_user extends Authenticatable
{
use Notifiable;
public $table = 'app4_users';
protected $fillable = [
'name', 'email', 'password', 'session', 'srid', 'email_varification', 'blocked', ];
protected $hidden = ['password', '_token', 'remember_token',];
}
