<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App1_user extends Authenticatable
{
use Notifiable;
public $table = 'app1_users';
protected $fillable = ['email', 'password'];
protected $hidden = ['password', 'remember_token',];
}
