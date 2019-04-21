<?php

namespace App\Traits;

use App\App;

trait UtilityFunctions
{
	public function dateFilter($request, $query)
    {
        if(!empty($request->_u)){
            $query = $request->date?$query->whereDate('created_at',$request->date):$query;
            $query = $request->day?$query->whereDay('created_at',$request->day):$query;
            $query = $request->month?$query->whereMonth('created_at',$request->month):$query;
            $query = $request->year?$query->whereYear('created_at',$request->year):$query;
            $query = $request->time?$query->whereTime('created_at','>',$request->time):$query;
            $query = $request->_u?$query->whereColumn('updated_at','>','created_at'):$query;
        }else{
            $query = $request->date?$query->whereDate('updated_at',$request->date):$query;
            $query = $request->day?$query->whereDay('updated_at',$request->day):$query;
            $query = $request->month?$query->whereMonth('updated_at',$request->month):$query;
            $query = $request->year?$query->whereYear('updated_at',$request->year):$query;
            $query = $request->time?$query->whereTime('updated_at','>',$request->time):$query;
        }
        return $query;
    }
    
    public function whereFilters($query, $filters)
    {
        foreach ($filters as $filter) {
            $f = explode(",", $filter);
            $fc = count($f);
            if($f[0] == 'where' || $f[0] == 'having'){
                if($fc==4){
                    $query = $query->where($f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->where($f[1],$f[2]);
                }
            }elseif($f[0] == 'orWhere'){
                if($fc==4){
                    $query = $query->orWhere($f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->orWhere($f[1],$f[2]);
                }
            }elseif($f[0] == 'whereBetween'){
                if($fc==5){
                    $query = $query->whereBetween($f[1],$f[2],[$f[3],$f[4]]);
                }elseif($fc==4){
                    $query = $query->whereBetween($f[1],[$f[2],$f[3]]);
                }
            }elseif($f[0] == 'whereNotBetween'){
                if($fc==5){
                    $query = $query->whereNotBetween($f[1],$f[2],[$f[3],$f[4]]);
                }elseif($fc==4){
                    $query = $query->whereNotBetween($f[1],[$f[2],$f[3]]);
                }
            }elseif($f[0] == 'whereIn'){
                if($fc>4){
                    $query = $query->whereIn($f[1],$f[2],implode(',',array_slice($f,3)));
                }elseif($fc>3){
                    $query = $query->whereIn($f[1],implode(',',array_slice($f,2)));
                }
            }elseif($f[0] == 'whereNotIn'){
                if($fc>4){
                    $query = $query->whereNotIn($f[1],$f[2],implode(',',array_slice($f,3)));
                }elseif($fc>3){
                    $query = $query->whereNotIn($f[1],implode(',',array_slice($f,2)));
                }
            }elseif($f[0] == 'whereNull' && $fc>1){
                $query = $query->whereNull($f[1]);
            }elseif($f[0] == 'whereNotNull' && $fc>1){
                $query = $query->whereNotNull($f[1]);
            }elseif($f[0] == 'whereDate'){
                if($fc==4){
                    $query = $query->whereDate($f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereDate($f[1],$f[2]);
                }
            }elseif($f[0] == 'whereMonth'){
                if($fc==4){
                    $query = $query->whereMonth($f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereMonth($f[1],$f[2]);
                }
            }elseif($f[0] == 'whereDay'){
                if($fc==4){
                    $query = $query->whereDay($f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereDay($f[1],$f[2]);
                }
            }elseif($f[0] == 'whereYear'){
                if($fc==4){
                    $query = $query->whereYear($f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereYear($f[1],$f[2]);
                }
            }elseif($f[0] == 'whereTime'){
                if($fc==4){
                    $query = $query->whereTime($f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereTime($f[1],$f[2]);
                }
            }elseif($f[0] == 'whereColumn'){
                if($fc==4){
                    $query = $query->whereColumn($f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereColumn($f[1],$f[2]);
                }
            }elseif($f[0] == 'orderBy'){
                if($fc==4){
                    $query = $query->orderBy($f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->orderBy($f[1],$f[2]);
                }elseif($fc==2){
                    $query = $query->orderBy($f[1]);
                }
            }elseif($f[0] == 'latest'){
                $query = $query->latest();
            }elseif($f[0] == 'oldest'){
                $query = $query->oldest();
            }elseif($f[0] == 'inRandomOrder'){
                $query = $query->inRandomOrder();
            }elseif($f[0] == 'distinct'){
                $query = $query->distinct();
            }elseif($f[0] == 'offset' || $f[0] == 'skip'){
                if($fc==2){
                    $query = $query->offset($f[1]);
                }
            }elseif($f[0] == 'limit' || $f[0] == 'take'){
                if($fc==2){
                    $query = $query->limit($f[1]);
                }
            }elseif($f[0] == 'groupBy'){
                if($fc>1){
                    $query = $query->groupBy(implode(',',array_slice($f,1)));
                }
            }
        }
        return $query;
    }

    private function setTable($table)
    {
        $this->table = 'App\\Models\\'.ucwords(rtrim($table,'s'));
    }

    public function gtc($table, $fillables = null, $hiddens = null)
    {
        $table_name = $this->tClass($table);
        // $myFilePath = app_path() ."/Models/$table_name.php";
        // if(!file_exists($myFilePath)){
            $arr = json_decode(App::findOrFail($this->app_id)->auth_providers, true);
            $this->createModelClass($table, in_array($table, $arr), $fillables, $hiddens);
        // }
        return "App\\Models\\".$table_name;
    }

    private function remModelClass($table_class)
    {
        $myFilePath = base_path() .'/'.$table_class.'.php';
        if(is_writable($myFilePath)){
            unlink($myFilePath);
        }
    }

    public function tClass($table)
    {
        return ucwords(rtrim('app'.$this->app_id.'_'.$table,'s'));
    }

    private function table($table)
    {
        return 'app'.$this->app_id.'_'.$table;
    }
}