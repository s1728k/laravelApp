<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App15_house_hunting extends Model
{
public $table = 'app15_house_hunting';
protected $connection = 'apps_db';
protected $fillable = ['images', 'attachments', 'tenant_name', 'size', 'looking_for', 'no_of_people_to_stay', 'price_range', 'locality', 'house_description', 'contact_number', 'spin'];
protected $hidden = [''];
}
