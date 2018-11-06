<?php

namespace App\Traits;

use App\App;
use App\Traits\SchemaFunctions;
use App\Traits\CreatesModelClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

trait CreatesTables
{
    use SchemaFunctions;
    use CreatesModelClass;

    public function myTableListView()
    {
        \Log::Info(request()->ip()." visited table list page for app id ".$this->app_id);
        $tables = $this->getTables();
        return view($this->theme.'.db.mytable_list')->with(['tables' => $tables]);
    }

    public function createNewTableView(Request $request)
    {
        \Log::Info(request()->ip()." visited create new table page for app id ".$this->app_id);
        return view($this->theme.'.db.create_table')->with(['fn' => $request->fn]);
    }

	public function createNewTable(Request $request)
    {
        \Log::Info(request()->ip()." created new table ".$request->name." for app id ".$this->app_id);
        $this->validateCreateTableRequest($request);
        $this->createTableSchema($request, $request->name);
        $this->createModelClass($request->name, $request->model == "authenticatable");
        if($request->model == "authenticatable"){
            $this->addUserTypeToApp($request->name);
        }else{
            $this->addTableFilersToApp($request->name);
        }
        return redirect()->route('c.table.list.view');
    }

    public function addColumnsView(Request $request)
    {
        \Log::Info(request()->ip()." visited add columns pages for the table ".$request->table." for app id ".$this->app_id);
        $fields = $this->getAfterFields($request->table);
        return view($this->theme.'.db.add_columns')->with(['fn' => $request->fn??0, 'table' => $request->table??'', 'fields' => $fields]);
    }

    public function addColumns(Request $request)
    {
        \Log::Info(request()->ip()." added columns for the table ".$request->table." for app id ".$this->app_id);
        $this->validateAddColumnsRequest($request);
        $this->addColumnsSchema($request, $request->name);
        $this->createModelClass($request->name);
        return redirect()->route('c.table.list.view');
    }

    public function getColumns(Request $request)
    {
        \Log::Info(request()->ip()." requested columns for the table ".$request->table." for app id ".$this->app_id);
        return $this->getFieldsSelectOptions($request->table);
    }

    public function renameColumn(Request $request)
    {
        \Log::Info(request()->ip()." renamed column ".$request->old_field_name." to ".$request->new_field_name." for the table ".$request->table." for app id ".$this->app_id);
        $this->renameSchemaColumn($request->table, $request->old_field_name, $request->new_field_name);
        $this->createModelClass($request->table);
        return ['status' => 'success'];
    }

    public function deleteColumn(Request $request)
    {
        \Log::Info(request()->ip()." deleted column ".$request->field_name." for the table ".$request->table." for app id ".$this->app_id);
        $this->deleteSchemaColumn($request->table, $request->field_name);
        $this->createModelClass($request->table);
        return ['status' => 'success'];
    }

    public function addIndex(Request $request)
    {
        \Log::Info(request()->ip()." added index ".$request->index_name." to field ".$request->field_name." for the table ".$request->table." for app id ".$this->app_id);
        $this->addIndexToSchemaColumn($request->table, $request->field_name, $request->index_name);
        return ['status' => 'success'];
    }

    public function removeIndex(Request $request)
    {
        \Log::Info(request()->ip()." removed index ".$request->index_name." from field ".$request->field_name." for the table ".$request->table." for app id ".$this->app_id);
        $this->removeIndexFromSchemaColumn($request->table, $request->field_name, $request->index_name);
        return ['status' => 'success'];
    }

    public function renameTable(Request $request)
    {
        \Log::Info(request()->ip()." renamed table ".$request->table." to ".$request->new_name." for app id ".$this->app_id);
        $this->renameTableSchema($request->table, $request->new_name);
        $this->removeUserTypeFromApp($request->table, $request->new_name);
        $return = $this->deleteModelClass($request->table);
        $this->createModelClass($request->new_name);
        return redirect()->route('c.table.list.view');
    }

    public function truncateTable(Request $request){
        \Log::Info(request()->ip()." truncated table ".$request->table." for app id ".$this->app_id);
        $table = $this->gtc($request->table);
        $table::truncate();
        return ['status' => 'success'];
    }

    public function deleteTable(Request $request)
    {
        \Log::Info(request()->ip()." deleted table ".$request->table." for app id ".$this->app_id);
        Schema::table($this->table($request->table), function (Blueprint $table){
            $table->drop();
        });
        $this->removeUserTypeFromApp($request->table);
        return $this->deleteModelClass($request->table);
    }

    public function crudTableView(Request $request)
    {
        \Log::Info(request()->ip()." visited CRUD view for table ".$request->table." for app id ".$this->app_id);
        // $td = \DB::select(\DB::raw('DESCRIBE app'.$this->app_id.'_'.$request->table));
        $td = $this->getDescriptions($request->table, []);
        $table = $this->gtc($request->table);
        $records = $table::where('id','!=',0);
        foreach ($td as $key => $value) { 
            $records = empty($request->{$value->Field})?$records:$records->where($value->Field, $request->{$value->Field});
        }
        $records = $records->paginate(10);
        return view($this->theme.'.db.crud')->with([
            'td'=>$td??[], 
            'table'=>$request->table??'', 
            'records' => $records??[],
            'inpTyp' => $this->getInputTypeArray($td),
            'isTA' => $this->getTextAreaTypes(),
        ]);
    }

    public function addRecordView(Request $request)
    {
        \Log::Info(request()->ip()." visited add record page for table ".$request->table." for app id ".$this->app_id);
        $td = $this->getDescriptions($request->table, ['id', 'created_at', 'updated_at', 'remember_token']);
        return view($this->theme.'.db.add_record')->with([
            'td'=> $td, 
            'table'=>$request->table??'',
            'inpTyp' => $this->getInputTypeArray($td),
            'isTA' => $this->getTextAreaTypes(),
            'step' => $this->getDecimalTypes(),
        ]);
    }

    public function addRecord(Request $request)
    {
        \Log::Info(request()->ip()." added record for table ".$request->table." for app id ".$this->app_id);
        $this->validateGenericInputs($request, $request->table);
        $table = $this->gtc($request->table);
        $table::create($request->all());
        return redirect()->route('c.db.crud.table', ['table' => $request->table]);
    }

    public function editRecordView(Request $request)
    {
        \Log::Info(request()->ip()." visited edit record page for table ".$request->table." for app id ".$this->app_id);
        $table = $this->gtc($request->table);
        $td = $this->getDescriptions($request->table, ['created_at', 'updated_at', 'remember_token']);
        return view($this->theme.'.db.edit_record')->with([
            'td'=> $td,  
            'table'=>$request->table??'', 
            'record' => $table::findOrFail($request->id),
            'inpTyp' => $this->getInputTypeArray($td),
            'isTA' => $this->getTextAreaTypes(),
            'step' => $this->getDecimalTypes(),
        ]);
    }

    public function editRecord(Request $request)
    {
        \Log::Info(request()->ip()." edited record ".$request->id." for table ".$request->table." for app id ".$this->app_id);
        $this->validateGenericInputs($request, $request->table);
        $table = $this->gtc($request->table);
        $table::findOrFail($request->id)->update($request->all());
        return redirect()->route('c.db.crud.table', ['table' => $request->table]);
    }

    public function deleteRecord(Request $request)
    {
        \Log::Info(request()->ip()." deleted record ".$request->id." for table ".$request->table." for app id ".$this->app_id);
        $table = $this->gtc($request->table);
        if(!empty($request->id)){
            $table::destroy($request->id);
        }
        return redirect()->route('c.db.crud.table', ['table' => $request->table]);
    }

    private function createDefaultUsersTable($id)
    {
        \Log::Info(request()->ip()." created default users table for app id ".$id);
        $this->createUsersSchema($id);
        $this->createUserModelClass($id);
    }

    public function addUserTypeToApp($auth_provider)
    {
        \Log::Info(request()->ip()." added auth_provider ".$auth_provider." for app id ".$this->app_id);
        $app = App::findOrFail($this->app_id);
        $arr = json_decode($app->auth_providers, true)??[];
        $arr[$auth_provider] = ['f'=>'role', 'r'=>['All Users']];
        $app->update(['auth_providers' => json_encode($arr)]);
        $this->addTableFilersToApp($auth_provider);
    }

    public function removeUserTypeFromApp($auth_provider, $new_auth_provider = null)
    {
        \Log::Info(request()->ip()." removed auth_provider ".$auth_provider." for app id ".$this->app_id);
        $app = App::findOrFail($this->app_id);
        $arr = json_decode($app->auth_providers, true)??[];
        if(isset($arr[$auth_provider])){
            if(!empty($new_auth_provider)){
                $arr[$new_auth_provider] = $arr[$auth_provider];
            }
            unset($arr[$auth_provider]);
            $app->update(['auth_providers' => json_encode($arr)]);
            $this->removeTableFilersFromApp($auth_provider, $new_auth_provider);
        }else{
            $this->removeTableFilersFromApp($auth_provider, $new_auth_provider);
        }
    }

    public function addTableFilersToApp($table)
    {
        \Log::Info(request()->ip()." added table filters to table ".$table." for app id ".$this->app_id);
        $app = App::findOrFail($this->app_id);
        $arr = json_decode($app->table_filters, true)??[];
        $arr[$table] = ['All Rows'];
        $app->update(['table_filters' => json_encode($arr)]);
    }

    public function removeTableFilersFromApp($table, $new_table = null)
    {
        \Log::Info(request()->ip()." removed table filters to table ".$table." for app id ".$this->app_id);
        $app = App::findOrFail($this->app_id);
        $arr = json_decode($app->table_filters, true)??[];
        if(!empty($new_table)){
            $arr[$new_table] = isset($arr[$table])?$arr[$table]:['All Rows'];
        }
        unset($arr[$table]);
        $app->update(['table_filters' => json_encode($arr)]);
    }

}