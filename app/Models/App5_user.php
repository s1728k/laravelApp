<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App5_user extends Model
{
public $table = 'app5_users';
protected $fillable = ['name', 'email', 'password', 'session', 'srid', 'email_varification', 'blocked'];
protected $hidden = [''];
}
