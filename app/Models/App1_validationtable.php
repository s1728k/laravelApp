<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App1_validationtable extends Model
{
public $table = 'app1_validationtable';
protected $connection = 'apps_db';
protected $fillable = ['timestamp', 'fdsfsd', 'numeric', 'non_fraction', 'tinyInteger', 'tinyIntegerUnsigned', 'smallInteger', 'smallIntegerUnsigned', 'mediumInteger', 'mediumIntegerUnsigned', 'integerCustom', 'integerCustomUnsigned', 'bigInteger', 'bigIntegerUnsigned', 'decimal', 'decimall', 'boolean', 'date_multi_format', 'char', 'string', 'max', 'geometry', 'point', 'linestring', 'polygon', 'multipoint', 'multilinestring', 'multipolygon', 'geometrycollection', 'year'];
protected $hidden = [''];
}
