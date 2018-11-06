<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App22_admin extends Authenticatable
{
use Notifiable;
public $table = 'app22_admins';
protected $fillable = ['name', 'email', 'session', 'srid', 'email_verification', 'blocked'];
protected $hidden = ['password', 'remember_token',];
}
