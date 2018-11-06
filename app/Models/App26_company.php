<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class App26_company extends Authenticatable
{
use Notifiable;
public $table = 'app26_company';
protected $fillable = ['company_name', 'full_name', 'tel', 'skype', 'email', 'password', 'have_referral', 'referral_code'];
protected $hidden = ['password', 'remember_token',];
}
