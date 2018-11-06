<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App10_NewTenant extends Model
{
public $table = 'app10_NewTenant';
protected $fillable = ['name', 'img_path', 'attach_images', 'rent_history', 'rent_rate_history', 'join_date', 'total_rent_agreed', 'rent_effective_from', 'previous_dues', 'advance_received'];
protected $hidden = [''];
}
