<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App10_rent_rate_history extends Model
{
public $table = 'app10_rent_rate_history';
protected $fillable = ['rent_effective_from', 'total_rent_agreed'];
protected $hidden = [''];
}
