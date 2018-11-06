<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App10_rent_history extends Model
{
public $table = 'app10_rent_history';
protected $fillable = ['received_date', 'amount_paid'];
protected $hidden = [''];
}
