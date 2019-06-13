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

    public function myTableListView(Request $request)
    {
        \Log::Info($this->fc.'myTableListView');
        // $tables = $this->getTables();
        $tables = $this->getTablesWithSizes();
        $size = 0;
        foreach ($tables as $table) {
            $size = $size + $table['size'];
        }
        $page = $request->page;
        $urls = $this->paginate_urls($tables, 10, $page);
        $np = count($urls);
        return view($this->theme.'.db.mytable_list')->with([
            'tables' => $this->paginate_arr($tables, 10, $page), 
            'page' => $page?max(1,min($page,$np)):1,
            'urls' => $urls,
            'np' => $np,
            'size' => $size,
        ]);
    }

    public function createNewTableView(Request $request)
    {
        \Log::Info($this->fc.'createNewTableView');
        return view($this->theme.'.db.create_table')->with(['fn' => $request->fn]);
    }

	public function createNewTable(Request $request)
    {
        \Log::Info($this->fc.'createNewTable');
        $this->validateCreateTableRequest($request);
        $this->createTableSchema($request, $request->name);
        // $this->createModelClass($request->name, $request->model == "authenticatable");
        if($request->model == "authenticatable"){
            $this->addUserTypeToApp($request->name);
        }
        return redirect()->route('c.table.list.view');
    }

    public function addColumnsView(Request $request)
    {
        \Log::Info($this->fc.'addColumnsView');
        $fields = $this->getAfterFields($request->table);
        return view($this->theme.'.db.add_columns')->with(['fn' => $request->fn??0, 'table' => $request->table??'', 'fields' => $fields]);
    }

    public function addColumns(Request $request)
    {
        \Log::Info($this->fc.'addColumns');
        $this->validateAddColumnsRequest($request);
        $this->addColumnsSchema($request, $request->name);
        // $this->createModelClass($request->name);
        return redirect()->route('c.table.list.view');
    }

    public function getColumns(Request $request)
    {
        \Log::Info($this->fc.'getColumns');
        return $this->getFieldsSelectOptions($request->table);
    }

    public function renameColumn(Request $request)
    {
        \Log::Info($this->fc.'renameColumn');
        $this->renameSchemaColumn($request->table, $request->old_field_name, $request->new_field_name);
        // $this->createModelClass($request->table);
        return ['status' => 'success'];
    }

    public function deleteColumn(Request $request)
    {
        \Log::Info($this->fc.'deleteColumn');
        $this->deleteSchemaColumn($request->table, $request->field_name);
        // $this->createModelClass($request->table);
        return ['status' => 'success'];
    }

    public function addIndex(Request $request)
    {
        \Log::Info($this->fc.'addIndex');
        $this->addIndexToSchemaColumn($request->table, $request->field_name, $request->index_name);
        return ['status' => 'success'];
    }

    public function removeIndex(Request $request)
    {
        \Log::Info($this->fc.'removeIndex');
        $this->removeIndexFromSchemaColumn($request->table, $request->field_name, $request->index_name);
        return ['status' => 'success'];
    }

    public function renameTable(Request $request)
    {
        \Log::Info($this->fc.'renameTable');
        $this->renameTableSchema($request->table, $request->new_name);
        $this->removeUserTypeFromApp($request->table, $request->new_name);
        // $return = $this->deleteModelClass($request->table);
        // $this->createModelClass($request->new_name);
        return redirect()->route('c.table.list.view');
    }

    public function truncateTable(Request $request){
        \Log::Info($this->fc.'truncateTable');
        $table = $this->gtc($request->table);
        $table::truncate();
        return ['status' => 'success'];
    }

    public function deleteTable(Request $request)
    {
        \Log::Info($this->fc.'deleteTable');
        Schema::connection($this->con)->table($this->table($request->table), function (Blueprint $table){
            $table->drop();
        });
        $this->removeUserTypeFromApp($request->table);
        return ['status' => 'success'];
    }

    public function crudTableView(Request $request)
    {
        \Log::Info($this->fc.'crudTableView');
        // $td = \DB::select(\DB::raw('DESCRIBE app'.$this->app_id.'_'.$request->table));
        $td = $this->getDescriptions($request->table, []);
        $table = $this->gtc($request->table);
        $records = $table::where('id','!=',0);
        foreach ($td as $key => $value) { 
            $records = empty($request->{$value->Field})?$records:$records->where($value->Field,'LIKE','%'.$request->{$value->Field}.'%');
        }
        $records = $this->dateFilter($request, $records);
        $records = $this->whereFilters($records, $request->_f?explode('|', $request->_f):[]);
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
        \Log::Info($this->fc.'addRecordView');
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
        \Log::Info($this->fc.'addRecord');
        $this->validateGenericInputs($request, $request->table);
        $table = $this->gtc($request->table);
        $table::create($request->all());
        return redirect()->route('c.db.crud.table', ['table' => $request->table]);
    }

    public function editRecordView(Request $request)
    {
        \Log::Info($this->fc.'editRecordView');
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
        \Log::Info($this->fc.'editRecord');
        $this->validateGenericInputs($request, $request->table);
        $table = $this->gtc($request->table);
        $table::findOrFail($request->id)->update($request->all());
        return redirect()->route('c.db.crud.table', ['table' => $request->table]);
    }

    public function deleteRecord(Request $request)
    {
        \Log::Info($this->fc.'deleteRecord');
        if(!empty($request->id)){
            $table::destroy($request->id);
        }
        return ['status' => 'success'];
    }

    private function createDefaultUsersTable($id)
    {
        \Log::Info($this->fc.'createDefaultUsersTable');
        $this->createUsersSchema($id);
        // $this->createModelClass('users', true);
    }

    public function addUserTypeToApp($auth_provider)
    {
        \Log::Info($this->fc.'addUserTypeToApp');
        $app = App::findOrFail($this->app_id);
        $arr = json_decode($app->auth_providers, true)??[];
        array_push($arr, $auth_provider);
        $app->update(['auth_providers' => json_encode($arr)]);
    }

    public function removeUserTypeFromApp($auth_provider, $new_auth_provider = null)
    {
        \Log::Info($this->fc.'removeUserTypeFromApp');
        $app = App::findOrFail($this->app_id);
        $arr = json_decode($app->auth_providers, true)??[];
        if(!empty($new_auth_provider)){
            array_push($arr, $new_auth_provider);
        }
        array_splice($arr, array_search($auth_provider, $arr), 1);
        $app->update(['auth_providers' => json_encode($arr)]);
    }

}