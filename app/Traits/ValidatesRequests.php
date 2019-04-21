<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait ValidatesRequests
{
	public function validateCreateAppRequest($request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
		]);
	}

    public function validateCreateTableRequest($request)
    {
        $request->validate([
            "name" => ['required','string','max:255', function($attribute, $value, $fail){
                if(Schema::connection($this->con)->hasTable($this->table($value))){
                    $fail('A table with this name already exisits.');
                }
            }],
        ]);
        $this->validateAddColumnsRequest($request);
        \Log::Info(request()->ip()." validation passed for create table request for app id ".$this->app_id);
    }

    public function validateAddColumnsRequest($request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            "field_name.*" => "required|string|max:255",

            "field_type.*" => "required|in:increments,tinyIncrements,smallIncrements,mediumIncrements,bigIncrements,tinyInteger,unsignedTinyInteger,smallInteger,unsignedSmallInteger,mediumInteger,unsignedMediumInteger,integer,unsignedInteger,bigInteger,unsignedBigInteger,decimal,unsignedDecimal,float,double,boolean,date,dateTime,dateTimeTz,time,timeTz,char,string,text,mediumText,longText,binary,enum,geometry,point,lineString,polygon,multiPoint,multiLineString,multiPolygon,geometryCollection,ipAddress,macAddress,uuid,year,timestamp,timestamps,timestampsTz",

            "field_param.*" => [function($attribute, $value, $fail)use($request){
                if(!empty($value)){ 
                    $key = str_replace('field_param.','',$attribute);
                    if(in_array($request->field_type[$key], ['char','string'])){
                        if(is_numeric($value)){
                            if($value>21844){
                                $fail('String length must not be more than 21844.');
                            }else if( strpos($value, ".") ){
                                $fail('String length must be whole number.');
                            }
                        }else {
                            $fail('String length must be numeric.');
                        } 
                    }else if(in_array($request->field_type[$key], ['decimal','unsignedDecimal','float'])){
                        if( strpos($value, ",") ){
                            $t=explode(',',$value);
                            if(is_numeric($t[0]) && is_numeric($t[1])){
                                if($t[0]>65){
                                    $fail('Real type M(total digits) must not be more than 65.');
                                }else if( $t[1]>30 ){
                                    $fail('Real type D(decimals) must not be more than 30.');
                                }else if( $t[1]>$t[0] ){
                                    $fail('Real type  M(total digits) must be greater than or equal to D(decimals).');
                                }else if( strpos($t[0], ".") || strpos($t[1], ".") ){
                                    $fail('Real type must have M & D values as whole number.');
                                }
                            }else{
                                $fail('Real type must have numeric lengths.');
                            }
                        }else{
                            $fail('Real type must have both M(total digits), D(decimals).');
                        }
                    }else if($request->field_type[$key] == 'enum'){
                        if($value==""){
                            $fail('Enum type must have options separated by comma.');
                        }
                    }
                }
            }],

            "field_key.*" => "in:primary,unique,index,null",
        ]);

        $rules = [];
        foreach ($request->field_default as $key => $value) {
            if(!empty($value)){
                $val=$this->getValidationString($this->getDataTypeString($request->field_type[$key], $request->field_param[$key]));
                $rules["field_default.".$key] = $val;
            }
        }
        $request->validate($rules);

        $fields = $this->getAfterFields($request->name);
        if($fields !== []){
            $rules = ['field_pos.*' => 'in:'.implode(',', $fields)];
            $request->validate($rules);
            \Log::Info(request()->ip()." validation passed for add columns request for app id ".$this->app_id);
        }
    }

    public function validateGenericInputs($request, $table, $skips=[], $mandatory=[], $pc=false)
    {
        $td = $this->getDescriptions($table, $skips);
        $rules = [];
        foreach ($td as $k => $v) {
                if($v->Field == 'password' && $pc == true){
                    $rules[$v->Field] = 'required|min:6|'.$this->getValidationString($v->Type).'|confirmed';
                }else{
                    if(in_array($v->Field, $mandatory)){
                        $rules[$v->Field] = 'required|'.$this->getValidationString($v->Type);
                        if($v->Field == 'email'){
                            $rules[$v->Field] = $rules[$v->Field] .'|email';
                        }
                    }else{
                        if(!empty($request->input($v->Field))){
                            $rules[$v->Field] = $this->getValidationString($v->Type);
                            if($v->Field == 'email'){
                                $rules[$v->Field] = $rules[$v->Field] .'|email';
                            }
                        }
                    }
                }
                if($v->Key == 'UNI' && $pc == true){
                    $rules[$v->Field] = $rules[$v->Field] .'|unique:'.$this->con.'.app'.$this->app_id.'_'.$table;
                }
        }
        \Log::Info($rules);
        $request->validate($rules);
        \Log::Info(request()->ip()." validation passed for end user create request for app id ".$this->app_id);
    }

    public function getDataTypeString($dt, $len="")
    {
        $arr = [
            'increments' => 'int(10) unsigned',
            'tinyIncrements' => 'tinyint(3) unsigned',
            'smallIncrements' => 'smallint(5) unsigned',
            'mediumIncrements' => 'mediumint(8) unsigned',
            'bigIncrements' => 'bigint(20) unsigned',
            'tinyInteger' => 'tinyint(4)',
            'unsignedTinyInteger' => 'tinyint(3) unsigned',
            'smallInteger' => 'smallint(6)',
            'unsignedSmallInteger' => 'smallint(5) unsigned',
            'mediumInteger' => 'mediumint(9)',
            'unsignedMediumInteger' => 'mediumint(8) unsigned',
            'integer' => 'int(11)',
            'unsignedInteger' => 'int(10) unsigned',
            'bigInteger' => 'bigint(20)',
            'unsignedBigInteger' => 'bigint(20) unsigned',
            'decimal' => 'decimal(8,2)',
            'unsignedDecimal' => 'decimal(8,2)',
            'float' => 'double(8,2)',
            'double' => 'double',
            'boolean' => 'tinyint(1)',
            'date' => 'date',
            'dateTime' => 'datetime',
            'dateTimeTz' => 'datetime',
            'time' => 'time',
            'timeTz' => 'time',
            'char' => 'char(255)',
            'string' => 'varchar(255)',
            'text' => 'text',
            'mediumText' => 'mediumtext',
            'longText' => 'longtext',
            'binary' => 'blob',
            'geometry' => 'geometry',
            'point' => 'point',
            'lineString' => 'linestring',
            'polygon' => 'polygon',
            'multiPoint' => 'multipoint',
            'multiLineString' => 'multilinestring',
            'multiPolygon' => 'multipolygon',
            'geometryCollection' => 'geometrycollection',
            'ipAddress' => 'varchar(45)',
            'macAddress' => 'varchar(17)',
            'uuid' => 'char(36)',
            'year' => 'year(4)',
            'timestamp' => 'timestamp',
            'timestamps' => 'timestamp',
            'timestampsTz' => 'timestamp',
        ];
        $obj = [
            'decimal' => 'decimal(:param)',
            'unsignedDecimal' => 'decimal(:param)',
            'float' => 'double(:param)',
            'char' => 'char(:param)',
            'string' => 'varchar(:param)',
            'enum' => 'enum(:param)',
        ];
        if($len != ""){
            return str_replace(':param',$len,$obj[$dt])??"";
        }else{
            return $arr[$dt]??'';
        }
    }

	public function getValidationString($dt)
	{
        // string max:21,844
		$arr = [
            'tinyint(4)' => 'numeric|non_fraction|tinyInteger',
            'tinyint(3) unsigned' => 'numeric|non_fraction|tinyIntegerUnsigned',
            'smallint(6)' => 'numeric|non_fraction|smallInteger',
            'smallint(5) unsigned' => 'numeric|non_fraction|smallIntegerUnsigned',
            'mediumint(9)' => 'numeric|non_fraction|mediumInteger',
            'mediumint(8) unsigned' => 'numeric|non_fraction|mediumIntegerUnsigned',
            'int(11)' => 'numeric|non_fraction|integerCustom',
            'int(10) unsigned' => 'numeric|non_fraction|integerCustomUnsigned',
            'bigint(20)' => 'numeric|non_fraction|bigInteger',
            'bigint(20) unsigned' => 'numeric|non_fraction|bigIntegerUnsigned',
            'decimal(8,2)' => 'numeric|decimal:8,2',
            'decimal(8,2) unsigned' => 'numeric|decimal:8,2',
            'double(8,2)' => 'numeric|decimal:8,2',
            'double' => 'numeric',
            'tinyint(1)' => 'boolean',
            'date' => 'date_multi_format:Y-m-d,y-m-d',
            'datetime' => 'date_multi_format:Y-m-d H:i:s,Y-m-d H:i,y-m-d H:i:s,y-m-d H:i',
            'time' => 'date_multi_format:H:i:s,H:i',
            'char(255)' => 'char:10',
            'varchar(255)' => 'string|max:255',
            'text' => 'max:65535',
            'mediumtext' => 'max:16777215',
            'longtext' => 'max:4294967295',
            'blob' => 'max:65535',
            'geometry' => 'geometry',
            'point' => 'point',
            'linestring' => 'linestring',
            'polygon' => 'polygon',
            'multipoint' => 'multipoint',
            'multilinestring' => 'multilinestring',
            'multipolygon' => 'multipolygon',
            'geometrycollection' => 'geometrycollection',
            'varchar(45)' => 'string|max:45',
            'varchar(17)' => 'string|max:17',
            'char(36)' => 'char:36',
            'year(4)' => 'numeric|non_fraction|year',
            'timestamp' => 'date_multi_format:Y-m-d H:i:s,Y-m-d H:i,y-m-d H:i:s,y-m-d H:i',
        ];
        if(empty($arr[$dt])){
            if(strpos($dt, 'ecimal')){
                return 'numeric|decimal:'.str_replace(['decimal(',')'],['',''],$dt);
            }
            if(strpos($dt, 'ouble(')){
                return 'numeric|decimal:'.str_replace(['double(',')'],['',''],$dt);
            }
            if(strpos($dt, 'archar(')){
                return 'string|max:'.str_replace(['varchar(',')'],['',''],$dt);
            }
            if(strpos($dt, 'har(')){
                return 'char:'.str_replace(['char(',')'],['',''],$dt);
            }
            if(strpos($dt, 'num(')){
                return 'in:'.str_replace(['enum(',')',"'",' '],['','','',''],$dt);
            }
            return "";
        }else{
            return $arr[$dt];
        }
	}

    public function getInputTypeArray($td)
    {
        $arr = [
            'tinyint(4)' => 'number',
            'tinyint(3) unsigned' => 'number',
            'smallint(6)' => 'number',
            'smallint(5) unsigned' => 'number',
            'mediumint(9)' => 'number',
            'mediumint(8) unsigned' => 'number',
            'int(11)' => 'number',
            'int(10) unsigned' => 'number',
            'bigint(20)' => 'number',
            'bigint(20) unsigned' => 'number',
            'decimal(8,2)' => 'number',
            'decimal(8,2) unsigned' => 'number',
            'double(8,2)' => 'number',
            'double' => 'number',
            'tinyint(1)' => 'number',
            'datetime' => 'text',
            'date' => 'date',
            'time' => 'time',
            'char(255)' => 'text',
            'varchar(255)' => 'text',
            'text' => 'text',
            'mediumtext' => 'text',
            'longtext' => 'text',
            'blob' => 'text',
            'geometry' => 'text',
            'point' => 'text',
            'linestring' => 'text',
            'polygon' => 'text',
            'multipoint' => 'text',
            'multilinestring' => 'text',
            'multipolygon' => 'text',
            'geometrycollection' => 'text',
            'varchar(45)' => 'text',
            'varchar(17)' => 'text',
            'char(36)' => 'text',
            'year(4)' => 'number',
            'timestamp' => 'text'
        ];
        foreach ($td as $k => $v) {
            if(empty($arr[$v->Type])){
                if(strpos($v->Type, 'ecimal')){
                    $arr[$v->Type] = 'number';
                    $t=explode(",", str_replace(['decimal(',')'],['',''],$v->Type));
                    $this->deciArr[$v->Type] = '0.'.str_pad("1", $t[1],"0",STR_PAD_LEFT);
                }else if(strpos($v->Type, 'ouble(')){
                    $arr[$v->Type] = 'number';
                    $t=explode(",", str_replace(['double(',')'],['',''],$v->Type));
                    $this->deciArr[$v->Type] = '0.'.str_pad("1", $t[1],"0",STR_PAD_LEFT);
                }else if(strpos($v->Type, 'archar(')){
                    $arr[$v->Type] = 'text';
                }else if(strpos($v->Type, 'har(')){
                    $arr[$v->Type] = 'text';
                }
            }
        }
        return $arr;
    }

    public function getTextAreaTypes()
    {
        return [
            'text' => '2',
            'mediumtext' => '3',
            'longtext' => '5',
            'blob' => '5',
        ];
    }

    public function getDecimalTypes()
    {
        return $this->deciArr;
    }

    private $deciArr = [
        'decimal(8,2)' => '0.01',
        'decimal(8,2) unsigned' => '0.01',
        'double(8,2)' => '0.01',
        'double' => 'any',
    ];

}