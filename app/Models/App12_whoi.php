<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App12_whoi extends Model
{
public $table = 'app12_whois';
protected $fillable = ['thedate', 'idn', 'length', 'zoneid', 'name', 'domain_name', 'registrant', 'mobile', 'email', 'date', 'state', 'country'];
protected $hidden = [''];
}
