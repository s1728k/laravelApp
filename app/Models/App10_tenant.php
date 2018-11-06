<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App10_tenant extends Authenticatable
{
use Notifiable;
public $table = 'app10_tenant';
protected $fillable = ['name', 'email', 'password', 'img_path', 'attach_images', 'rent_history', 'rent_rate_history', 'join_date', 'total_rent_agreed', 'rent_effective_from', 'previous_dues', 'advance_received', 'srid', 'email_verification', 'blocked'];
protected $hidden = ['password', 'remember_token',];
}
