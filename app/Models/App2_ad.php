<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App2_ad extends Authenticatable
{
use Notifiable;
public $table = 'app2_ad';
protected $fillable = [''];
protected $hidden = ['password', 'remember_token',];
}
