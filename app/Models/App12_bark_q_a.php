<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App12_bark_q_a extends Model
{
public $table = 'app12_bark_q_a';
protected $fillable = ['category_name', 'url', 'question', 'options'];
protected $hidden = [''];
}
