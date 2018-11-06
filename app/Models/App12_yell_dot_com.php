<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App12_yell_dot_com extends Model
{
public $table = 'app12_yell_dot_com';
protected $fillable = ['cat', 'p', 'name', 'classification', 'website', 'phone1', 'phone2', 'streetAddress', 'addressLocality', 'postalCode', 'opens_at', 'image_url', 'review_url', 'yell_id'];
protected $hidden = [''];
}
