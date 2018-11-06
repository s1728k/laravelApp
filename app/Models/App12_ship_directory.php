<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App12_ship_directory extends Model
{
public $table = 'app12_ship_directory';
protected $fillable = ['VESSEL NAME', 'FLAG', 'TYPE', 'SYSTEM', 'CALL SIGN', 'IMO NUMBER', 'MMSI NUMBER', 'Numbers'];
protected $hidden = [''];
}
