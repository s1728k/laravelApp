<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App13_visitor extends Model
{
public $table = 'app13_visitors';
protected $fillable = ['timestamp', 'IP', 'app_id', 'page_visited', 'no_of_times_visited', 'Hostname', 'ISP', 'Origin', 'Continent', 'Country', 'State', 'City', 'Latitude', 'Longitude', 'Postal Code', 'visit'];
protected $hidden = [''];
}
