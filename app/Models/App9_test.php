<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App9_test extends Authenticatable
{
use Notifiable;
public $table = 'app9_test';
protected $fillable = ['asdf', 'abcd'];
protected $hidden = ['password', 'remember_token',];
}
