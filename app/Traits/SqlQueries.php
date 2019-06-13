<?php

namespace App\Traits;

use PDO;
use PDOException;

trait SqlQueries
{
    public function getNonHiddenFields($table, $app_id = null)
    {
        \Log::Info($this->fc.'getNonHiddenFields');
        return $this->getFields($table, ['password', 'remember_token'], $app_id);
    }

    public function getRemovableFields($table, $app_id = null)
    {
        \Log::Info($this->fc.'getRemovableFields');
        return $this->getFields($table, ['id','password', 'remember_token','created_at', 'updated_at'], $app_id);
    }

    public function getAfterFields($table, $app_id = null)
    {
        \Log::Info($this->fc.'getAfterFields');
        return $this->getFields($table, ['remember_token', 'created_at', 'updated_at'], $app_id);
    }

    public function getFieldsSelectOptions($table, $app_id = null)
    {
        \Log::Info($this->fc.'getFieldsSelectOptions');
        $array = $this->getRemovableFields($table, $app_id);
        $fields="";
        foreach ($array as $field) {
            $fields=$fields.'<option>'.$field.'</option>';
        }
        return $fields??'';
    }

    public function getRawTables($app_id = null)
    {
        \Log::Info($this->fc.'getRawTables');
        $app_id = $app_id??$this->app_id;
        $tables = [];
        $raw=\DB::connection($this->con)->select(\DB::connection($this->con)->raw("SHOW TABLES LIKE 'app".$app_id."\_%'"));
        foreach($raw as $key => $value){
            foreach($value as $key1 => $table){
                $tables[]=$table;
            }
        }
        return $tables;
    }

    public function getTables($app_id = null)
    {
        \Log::Info($this->fc.'getTables');
        $app_id = $app_id??$this->app_id;
        $tables = [];
        $raw=\DB::connection($this->con)->select(\DB::connection($this->con)->raw("SHOW TABLES LIKE 'app".$app_id."\_%'"));
        foreach($raw as $key => $value){
            foreach($value as $key1 => $table){
                $tables[]=str_replace('app'.$app_id.'_','', $table);
            }
        }
        return $tables;
    }

    public function getTablesWithSizes($app_id = null)
    {
        \Log::Info($this->fc.'getTablesWithSizes');
        $app_id = $app_id??$this->app_id;
        $tables = [];
        $query = 'SELECT table_name AS `Table`, round(((data_length + index_length) / 1024 / 1024), 2) `Size` FROM information_schema.TABLES WHERE table_schema = "'.$this->con.'" AND table_name LIKE "app'.$app_id.'\_%"';
        $raw=\DB::connection($this->con)->select(\DB::connection($this->con)->raw($query));
        foreach($raw as $key => $value){
            $tables[]=['name'=>str_replace('app'.$app_id.'_','', $value->Table), 'size'=>$value->Size];
        }
        return $tables;
    }

	public function getFields($table, $skips, $app_id = null)
	{
        \Log::Info($this->fc.'getFields');
        $app_id = $app_id??$this->app_id;
        $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".'app'.$app_id.'_'.$table."'";
        // $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".env('DB_DATABASE')."' AND TABLE_NAME = '".'app'.$app_id.'_'.$table."'";
		$raw = \DB::connection($this->con)->select(\DB::connection($this->con)->raw($query));
        // \Log::Info($raw);
        $fields=[];
        foreach ($raw as $key => $value) {
            foreach ($value as $key2 => $field) {
                if(in_array($field, $skips))
                    continue;
                $fields[]=$field;
            }
        }
        return $fields??[];
	}

    public function getFieldsLike($table, $likes, $app_id = null)
    {
        \Log::Info($this->fc.'getFieldsLike');
        $app_id = $app_id??$this->app_id;
        $fields=[];
        foreach ($likes as $like) {
            $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".'app'.$app_id.'_'.$table."' AND COLUMN_NAME LIKE '%".$like."%'";
            // $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".env('DB_DATABASE')."' AND TABLE_NAME = '".'app'.$app_id.'_'.$table."' AND COLUMN_NAME LIKE '%".$like."%'";
            $raw = \DB::connection($this->con)->select(\DB::connection($this->con)->raw($query));
            foreach ($raw as $key => $value) {
                foreach ($value as $key2 => $field) {
                    $fields[]=$field;
                }
            }
        }
        return $fields??[];
    }

    public function getAppFields($skips)
    {
        \Log::Info($this->fc.'getAppFields');
        $query = "SELECT DISTINCT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME LIKE 'app".$this->app_id."\_%'";
        $raw = \DB::connection($this->con)->select(\DB::connection($this->con)->raw($query));
        $fields=[];
        foreach ($raw as $key => $value) {
            foreach ($value as $key2 => $field) {
                if(in_array($field, $skips))
                    continue;
                $fields[]=$field;
            }
        }
        return $fields??[];
    }

    public function getAppFieldsOfDataTypes($data_types)
    {
        \Log::Info($this->fc.'getAppFieldsOfDataTypes');
        $query = "SELECT DISTINCT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME LIKE 'app".$this->app_id."\_%' AND DATA_TYPE IN ('".implode(', ', $data_types)."')";
        $raw = \DB::connection($this->con)->select(\DB::connection($this->con)->raw($query));
        $fields=[];
        foreach ($raw as $key => $value) {
            foreach ($value as $key2 => $field) {
                $fields[]=$field;
            }
        }
        return $fields??[];
    }

    public function getDescriptions($table, $skips, $app_id = null)
    {
        \Log::Info($this->fc.'getDescriptions');
        $app_id = $app_id??$this->app_id;
        $td = \DB::connection($this->con)->select(\DB::connection($this->con)->raw('DESCRIBE app'.$app_id.'_'.$table));
        foreach ($td as $k => $v) {
            if(in_array($v->Field, $skips))
                unset($td[$k]);
        }
        return $td??[];
    }

    public function copyTables($new_app_id, $app_id = null)
    {
        \Log::Info($this->fc.'copyTables');
        $app_id = $app_id??$this->app_id;
        $raw=\DB::connection($this->con)->select(\DB::connection($this->con)->raw("SHOW TABLES LIKE 'app".$app_id."\_%'"));
        foreach($raw as $key => $value){
            foreach($value as $key1 => $table){
                $query = 'CREATE TABLE '.str_replace('app'.$app_id.'_','app'.$new_app_id.'_',$table).' LIKE '.$table;
                $raw = \DB::connection($this->con)->select(\DB::connection($this->con)->raw($query));
            }
        }
        return true;
    }

    public function deleteTables($app_id = null)
    {
        \Log::Info($this->fc.'deleteTables');
        $app_id = $app_id??$this->app_id;
        $raw=\DB::connection($this->con)->select(\DB::connection($this->con)->raw("SHOW TABLES LIKE 'app".$app_id."\_%'"));
        foreach($raw as $key => $value){
            foreach($value as $key1 => $table){
                $query = 'DROP TABLE '.$table;
                $raw = \DB::connection($this->con)->select(\DB::connection($this->con)->raw($query));
            }
        }
        return true;
    }

    public function getUserStorageFootPrint($app_id = null)
    {
        \Log::Info($this->fc.'getTablesSize');
        $app_id = $app_id??$this->app_id;
        $user_id = ('App\\App')::findOrFail($app_id)->user_id;
        $app_ids = ('App\\App')::where('user_id', $user_id)->pluck('id');
        $size = 0;
        foreach ($app_ids as $id) {
            $query = 'SELECT SUM(round(((data_length + index_length) / 1024 / 1024), 2)) `Size` FROM information_schema.TABLES WHERE table_schema = "'.$this->con.'" AND table_name LIKE "app'.$id.'\_%"';
            $raw=\DB::connection($this->con)->select(\DB::connection($this->con)->raw($query));
            $size = $size + $raw[0]->Size;
        }
        $size = $size + round(('App\\File')::whereIn('app_id',$app_ids)->sum('size')/1024/1024,2);
        return $size;
    }
    
    private function type($dt)
    {
        \Log::Info($this->fc.'type');
        $arr = [
            'tinyint(4)' => 'tinyint',
            'tinyint(3) unsigned' => 'tinyint',
            'smallint(6)' => 'smallint',
            'smallint(5) unsigned' => 'smallint',
            'mediumint(9)' => 'mediumint',
            'mediumint(8) unsigned' => 'mediumint',
            'int(11)' => 'int',
            'int(10) unsigned' => 'int',
            'bigint(20)' => 'bigint',
            'bigint(20) unsigned' => 'bigint',
            'decimal(8,2)' => 'decimal(8,2)',
            'decimal(8,2) unsigned' => 'decimal(8,2)',
            'double(8,2)' => 'double(8,2)',
            'double' => 'double',
            'tinyint(1)' => 'tinyint',
            'date' => 'date',
            'time' => 'time',
            'char(255)' => 'char',
            'varchar(255)' => 'varchar',
            'text' => 'text',
            'mediumtext' => 'mediumtext',
            'longtext' => 'longtext',
            'blob' => 'blob',
            'geometry' => 'geometry',
            'point' => 'point',
            'linestring' => 'linestring',
            'polygon' => 'polygon',
            'multipoint' => 'multipoint',
            'multilinestring' => 'multilinestring',
            'multipolygon' => 'multipolygon',
            'geometrycollection' => 'geometrycollection',
            'varchar(45)' => 'varchar',
            'varchar(17)' => 'varchar',
            'char(36)' => 'char',
            'year(4)' => 'year',
        ];
        if(strpos($dt,"num(\'")){
            return 'enum';
        }else{
            return $arr[$dt]??'string';
        }
    }

    private function len($dt)
    {
        \Log::Info($this->fc.'len');
        $arr = [
            'tinyint(4)' => '4',
            'tinyint(3) unsigned' => '3',
            'smallint(6)' => '6',
            'smallint(5) unsigned' => '5',
            'mediumint(9)' => '9',
            'mediumint(8) unsigned' => '8',
            'int(11)' => '11',
            'int(10) unsigned' => '10',
            'bigint(20)' => '20',
            'bigint(20) unsigned' => '20',
            // 'decimal(8,2)' => 'decimal(8,2)',
            // 'decimal(8,2) unsigned' => 'decimal(8,2) unsigned',
            // 'double(8,2)' => 'double(8,2)',
            'tinyint(1)' => '1',
            'char(255)' => '255',
            'varchar(255)' => '255',
            'varchar(45)' => '45',
            'varchar(17)' => '17',
            'char(36)' => '36',
            'year(4)' => '4',
        ];
        if(strpos($dt,"num(\'")){
            return 'in:'.str_replace(['enum(',')',"\'",' '],['','','',''],$dt);
        }else{
            return $arr[$dt]??'';
        }
    }

    private function uns($dt)
    {
        \Log::Info($this->fc.'uns');
        $arr = [
            'tinyint(3) unsigned' => 'unsigned',
            'smallint(5) unsigned' => 'unsigned',
            'mediumint(8) unsigned' => 'unsigned',
            'int(10) unsigned' => 'unsigned',
            'bigint(20) unsigned' => 'unsigned',
            'decimal(8,2) unsigned' => 'unsigned',
        ];
        return $arr[$dt]??'';
    }

    private function ret($dt)
    {
        \Log::Info($this->fc.'ret');
        $enum = "enum(\'apple\',\' banana\',\' mango\',\' grapes\',\' jack\')";
        $arr = [
            'tinyint(4)' => 'tinyint(4)',
            'tinyint(3) unsigned' => 'tinyint(3) unsigned',
            'smallint(6)' => 'smallint(6)',
            'smallint(5) unsigned' => 'smallint(5) unsigned',
            'mediumint(9)' => 'mediumint(9)',
            'mediumint(8) unsigned' => 'mediumint(8) unsigned',
            'int(11)' => 'int(11)',
            'int(10) unsigned' => 'int(10) unsigned',
            'bigint(20)' => 'bigint(20)',
            'bigint(20) unsigned' => 'bigint(20) unsigned',
            'decimal(8,2)' => 'decimal(8,2)',
            'decimal(8,2) unsigned' => 'decimal(8,2) unsigned',
            'double(8,2)' => 'double(8,2)',
            'double' => 'double',
            'tinyint(1)' => 'tinyint(1)',
            'date' => 'date',
            'time' => 'time',
            'char(255)' => 'char(255)',
            'varchar(255)' => 'varchar(255)',
            'text' => 'text',
            'mediumtext' => 'mediumtext',
            'longtext' => 'longtext',
            'blob' => 'blob',
            'geometry' => 'geometry',
            'point' => 'point',
            'linestring' => 'linestring',
            'polygon' => 'polygon',
            'multipoint' => 'multipoint',
            'multilinestring' => 'multilinestring',
            'multipolygon' => 'multipolygon',
            'geometrycollection' => 'geometrycollection',
            'varchar(45)' => 'varchar(45)',
            'varchar(17)' => 'varchar(17)',
            'char(36)' => 'char(36)',
            'year(4)' => 'year(4)',
        ];
        return $arr[$dt]??'';
    }

}