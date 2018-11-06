<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App10_NewRentHouse extends Model
{
public $table = 'app10_NewRentHouse';
protected $fillable = ['house_images', 'tenants', 'house_address', 'size', 'solo_rent_price', 'rent_price_shared', 'advance', 'advance_shared', 'persons_allowed', 'allow_sharing', 'availability', 'rent_mode'];
protected $hidden = [''];
}
