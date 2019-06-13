<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App26_model extends Model
{
public $table = 'app26_models';
protected $connection = 'apps_db';
protected $fillable = ['model', 'brand_id'];
protected $hidden = ['created_at', 'updated_at'];
}
