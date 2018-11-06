<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App14_user extends Model
{
public $table = 'app14_users';
protected $fillable = ['name', 'email', 'password', 'session', 'srid', 'email_varification', 'blocked'];
protected $hidden = [''];
}
