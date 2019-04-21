<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App1_contact extends Model
{
public $table = 'app1_contact';
protected $connection = 'apps_db';
protected $fillable = ['name', 'email', 'phone', 'message'];
protected $hidden = [''];
}
