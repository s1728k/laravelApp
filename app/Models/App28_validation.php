<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class App28_validation extends Model
{
public $table = 'app28_validation';
protected $fillable = ['tiny_integer', 'unsigned_tiny_integer', 'small_integer', 'u_small_int', 'm_int', 'u_m_int', 'int', 'u_int', 'b_int', 'u_b_int', 'decimal', 'u_decimal', 'float', 'double', 'boolean', 'date', 'datetime', 'datetimeTz', 'timestamp', 'time', 'timeTz', 'char', 'string', 'text', 'm_text', 'l_text', 'blob', 'enum', 'geometry', 'point', 'lineString', 'polygon', 'multipoint', 'multilinestring', 'multipolygon', 'geometry_collection', 'ip_address', 'mac_address', 'uuid', 'year'];
protected $hidden = [''];
}
