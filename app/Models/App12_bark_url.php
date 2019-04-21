<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App12_bark_url extends Model
{
public $table = 'app12_bark_urls';
protected $connection = 'apps_db';
protected $fillable = ['main_category', 'category', 'category_name', 'url', 'category_id', 'postcode_id', 'postcode_type', 'bark_mode', 'exp_ph'];
protected $hidden = [''];
}
