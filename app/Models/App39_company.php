<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App39_company extends Authenticatable
{
use Notifiable;
public $table = 'app39_company';
protected $connection = 'apps_db';
protected $fillable = ['company_name', 'full_name', 'tel', 'skype', 'email', 'password', 'have_referral', 'referral_code'];
protected $hidden = ['password', 'remember_token',];
}
