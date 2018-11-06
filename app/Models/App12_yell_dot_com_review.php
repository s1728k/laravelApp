<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App12_yell_dot_com_review extends Model
{
public $table = 'app12_yell_dot_com_reviews';
protected $fillable = ['yell_id', 'user_name', 'date', 'headline', 'ratingValue', 'reviewBody'];
protected $hidden = [''];
}
