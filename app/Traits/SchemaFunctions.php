<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

trait SchemaFunctions
{
	public function createUsersSchema($app_id)
	{
		\Log::Info($this->fc.'createUsersSchema');
		Schema::connection($this->con)->create('app'.$app_id.'_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('session');
            $table->unsignedInteger('srid');
            $table->string('email_varification');
            $table->boolean('blocked')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
	}

	public function createTableSchema($request, $table)
	{
		\Log::Info($this->fc.'createTableSchema');
		Schema::connection($this->con)->create($this->table($table), function (Blueprint $table) use ($request) {
            $this->schemaColumnsDeveloper($request, $table);
        });
	}

	public function renameTableSchema($from, $to)
	{
		\Log::Info($this->fc.'renameTableSchema');
		if(!Schema::connection($this->con)->hasTable($this->table($from))){
			return ['status' => 'table name doest not exist.'];
		}
		Schema::connection($this->con)->rename($this->table($from), $this->table($to));
	}

	public function addColumnsSchema($request, $table)
	{
		\Log::Info($this->fc.'addColumnsSchema');
		Schema::connection($this->con)->table($this->table($table), function (Blueprint $table) use ($request) {
			$this->schemaColumnsDeveloper($request, $table);
        }); 
	}

	public function renameSchemaColumn($table, $from, $to)
	{
		\Log::Info($this->fc.'renameSchemaColumn');
		if(!Schema::connection($this->con)->hasColumn($this->table($table), $from)){
			\Log::Info('hasColumn');
			return ['status' => 'column desnot exist.'];
		}
		\Log::Info('renameColumn');
		Schema::connection($this->con)->table($this->table($table), function (Blueprint $table)use($from, $to){
			\Log::Info('renameColumn');
            $table->renameColumn($from, $to);
        });
	}

	public function deleteSchemaColumn($table, $column)
	{
		\Log::Info($this->fc.'deleteSchemaColumn');
		if(!Schema::connection($this->con)->hasColumn($this->table($table), $column)){
			return ['status' => 'column desnot exist.'];
		}
		Schema::connection($this->con)->table($this->table($table), function (Blueprint $table)use($column){
            $table->dropColumn($column);
        });
	}

	public function addIndexToSchemaColumn($table_name, $column_name, $index_name)
	{
		\Log::Info($this->fc.'addIndexToSchemaColumn');
		Schema::connection($this->con)->table($this->table($table_name), function (Blueprint $table) use($table_name, $column_name, $index_name){
		    $sm = Schema::connection($this->con)->getConnection()->getDoctrineSchemaManager();
		    $indexesFound = $sm->listTableIndexes($table_name);

		    $in = $table_name.'_'.$column_name.'_'.$index_name;
		    if(!array_key_exists($in, $indexesFound)){
		        switch ($index_name) {
		        	case 'primary':
		        		$table->primary($column_name);
		        		break;
		        	
		        	case 'unique':
		        		$table->unique($column_name);
		        		break;
		        	
		        	case 'index':
		        		$table->index($column_name);
		        		break;
		        	
		        	default:
		        		# code...
		        		break;
		        }
		    }
		});
	}

	public function removeIndexFromSchemaColumn($table_name, $column_name, $index_name)
	{
		\Log::Info($this->fc.'removeIndexFromSchemaColumn');
        Schema::connection($this->con)->table($this->table($table_name), function (Blueprint $table) use($table_name, $column_name, $index_name){
		    $sm = Schema::connection($this->con)->getConnection()->getDoctrineSchemaManager();
		    $indexesFound = $sm->listTableIndexes($table_name);

		    $in = $table_name.'_'.$column_name.'_'.$index_name;
		    if(!array_key_exists($index_name, $indexesFound)){
		        switch ($index_name) {
		        	case 'primary':
		        		$table->dropPrimary($in);
		        		break;
		        	
		        	case 'unique':
		        		$table->dropUnique($in);
		        		break;
		        	
		        	case 'index':
		        		$table->dropIndex($in);
		        		break;
		        	
		        	default:
		        		# code...
		        		break;
		        }
		    }
		        
		});
	}

	public function schemaColumnsDeveloper($request, $table)
	{
		\Log::Info($this->fc.'schemaColumnsDeveloper');
		foreach ($request->field_type as $key => $value) {
            $params = $this->schemaColumnsParamCondetions($request, $key, $value);
            \Log::Info($params);
            if($value == 'timestamps'){	
            	if($request->model == "authenticatable"){
		            $table->rememberToken();
		        }
            	$table->timestamps(); 

            }else if($value == 'timestampsTz'){ 
            	if($request->model == "authenticatable"){
		            $table->rememberToken();
		        }
            	$table->timestampsTz(); 

            }else if(strpos($value, 'ncrements')){

                $table->addColumn($this->lookupTypes($value), $request->field_name[$key], $params);

            }else if(!empty($request->field_default[$key])){

            	if(isset($request->field_pos[$key])){
            		$table->addColumn($this->lookupTypes($value), $request->field_name[$key], $params)->default($request->field_default[$key])->after($request->field_pos[$key]);
            	}else{
            		$table->addColumn($this->lookupTypes($value), $request->field_name[$key], $params)->default($request->field_default[$key]);
            	}
                $this->addIndexToColumns($table, $request->field_name[$key], $request->field_key[$key]);
            }else{

            	if(isset($request->field_pos[$key])){
            		$table->addColumn($this->lookupTypes($value), $request->field_name[$key], $params)->nullable()->after($request->field_pos[$key]);
            	}else{
            		$table->addColumn($this->lookupTypes($value), $request->field_name[$key], $params)->nullable();
            	}
                $this->addIndexToColumns($table, $request->field_name[$key], $request->field_key[$key]);
            }

        }

	}

	public function addIndexToColumns($table, $column_name, $index_name)
	{
		\Log::Info($this->fc.'addIndexToColumns');
		if(in_array($index_name, ['primary','unique','index'])){

            switch ($index_name) {
                case 'primary':
                    $table->primary($column_name);
                    break;
                case 'unique':
                    $table->unique($column_name);
                    break;
                case 'index':
                    $table->index($column_name);
                    break;
                default:
                    # code...
                    break;
            }
        }
	}

	public function schemaColumnsParamCondetions($request, $key, $value)
	{
		\Log::Info($this->fc.'schemaColumnsParamCondetions');
		$params = ['length'=>null, 'autoIncrement'=>null, 'unsigned'=>null, 'total'=>null, 'places'=>null];

        if(strpos($value, 'Integer')){
            $params['autoIncrement'] = false;$params['unsigned'] = false;
        }

        if(strpos($value, 'nsigned')){
            $params['unsigned'] = true;
            \Log::Info($params['unsigned']);
        }

        if(strpos($value, 'ncrements')){
            $params['autoIncrement'] = true;$params['unsigned'] = true;
        }

        switch ($value) {
            case 'text': case "mediumText": case "longText": case "boolean": case 'double': 
            case 'json': case 'jsonb': case 'date': case 'year': case 'binary': case 'uuid':
            case 'ipAddress': case 'macAddress': case 'geometry': case 'point': case 'lineString': case 'polygon':
            case 'geometryCollection': case 'multiPoint': case 'multiLineString': case 'multiPolygon':
                break;
            case 'char': 
            	$length = $request->field_param[$key];
            	$params['length'] = $length??1;
            case "string":
                $length = $request->field_param[$key];
                $params['length'] = $length ?: Builder::$defaultStringLength;
                break;
            case 'float': case "decimal": case "unsignedDecimal":
            	if(empty($request->field_param[$key])){
            		$params['total'] = 8; $params['places'] = 2;
            	}else{
            		$t=explode(',', $request->field_param[$key]);
            		$params['total'] = $t[0]; $params['places'] = $t[1];
            	}
                break;
            case 'enum':
            	if(strpos($request->field_param[$key], ',')){
            		$params['allowed'] = explode(',', $request->field_param[$key]);
            	}else{
            		$params['allowed'] = $request->field_param[$key];
            	}
                break;
            case 'dateTime': case 'dateTimeTz': case 'time': case 'timeTz': case 'timestamp': case 'timestampTz':
                $params['precision'] = 0;
                break;
            case 'timestamps':
                // $table->timestamps();
                break;
            case 'timestampsTz':
                // $table->timestampsTz();
                break;
        }
        return $params;
	}

	private function lookupTypes($type)
	{
		\Log::Info($this->fc.'lookupTypes');
		$arr = [
			'tinyInteger' => 'tinyInteger',
			'unsignedTinyInteger' => 'tinyInteger',
			'smallInteger' => 'smallInteger',
			'unsignedSmallInteger' => 'smallInteger',
			'mediumInteger' => 'mediumInteger',
			'unsignedMediumInteger' => 'mediumInteger',
			'integer' => 'integer',
			'unsignedInteger' => 'integer',
			'bigInteger' => 'bigInteger',
			'unsignedBigInteger' => 'bigInteger',
			'decimal' => 'decimal',
			'unsignedDecimal' => 'decimal',
			'float' => 'float',
			'double' => 'double',
			'boolean' => 'boolean',
			'date' => 'date',
			'dateTime' => 'dateTime',
			'dateTimeTz' => 'dateTimeTz',
			'timestamp' => 'timestamp',
			'timestampsTz' => 'timestampsTz',
			'time' => 'time',
			'timeTz' => 'timeTz',
			'char' => 'char',
			'string' => 'string',
			'text' => 'text',
			'mediumText' => 'mediumText',
			'longText' => 'longText',
			'binary' => 'binary',
			'enum' => 'enum',
			'geometry' => 'geometry',
			'point' => 'point',
			'lineString' => 'lineString',
			'polygon' => 'polygon',
			'multiPoint' => 'multiPoint',
			'multiLineString' => 'multiLineString',
			'multiPolygon' => 'multiPolygon',
			'geometryCollection' => 'geometryCollection',
			'ipAddress' => 'ipAddress',
			'macAddress' => 'macAddress',
			'uuid' => 'uuid',
			'year' => 'year',
			'increments' => 'integer',
			'tinyIncrements' => 'tinyInteger',
			'smallIncrements' => 'smallInteger',
			'mediumIncrements' => 'mediumInteger',
			'bigIncrements' => 'bigInteger',
			'timestamps' => 'timestamps',
			'timestampsTz' => 'timestampsTz',
		];
		return $arr[$type]??'string';
	}
}