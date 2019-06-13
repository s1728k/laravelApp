<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App1_admin extends Authenticatable
{
use Notifiable;
public $table = 'app1_admins';
protected $connection = 'apps_db';
protected $fillable = ['name', 'email', 'password', 'online_status', 'chat_resource_id', 'email_verification'];
protected $hidden = ['password', 'remember_token',];
}
