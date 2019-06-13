<?php

namespace App\Traits;

use App\App;
use App\Query;
use App\ValidationMessage;
use App\ValidationRule;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

trait CreatesQueries
{

    public function queryListView()
    {
        \Log::Info($this->fc.'queryListView');
        return view($this->theme.'.q.query_list')->with([
            'queries' => Query::where('app_id', \Auth::user()->active_app_id)->paginate(10), 
            'page' => $request->page??1
        ]);
    }

    public function createNewQueryView(Request $request)
    {
        \Log::Info($this->fc.'createNewQueryView');
        $app = App::findOrFail(\Auth::user()->active_app_id);
        $commands = ['ReadAll'=>'all', 'Create'=>'new', 'Read'=>'get', 'Update'=>'mod', 'Delete'=>'del', 
        'SignUp' => 'signup', 'SendEmailVerificationCode' => 'sevc', 'VerifyEmail' => 've', 'Login' => 'login', 
        'ConditionalLogin' => 'clogin', 'RefreshToken' => 'refresh', 'FilesUpload' => 'files_upload', 'SendMail' => 'mail'
        , 'PushSubscribe' => 'ps', 'GetAppSecret' => 'secret'];
        $specials = ['pluck', 'count', 'max', 'min', 'avg', 'sum'];
        return view($this->theme.'.q.create_query')->with([
            'auth_providers' => json_decode($app->auth_providers,true)??[], 
            'tables' => $this->getTables(),
            'commands' => $commands,
            'specials' => $specials,
        ]);
    }

    public function getAllColumns(Request $request)
    {
        \Log::Info($this->fc.'getAllColumns');
        $res = [];
        foreach ($request->tables??[] as $table) {
            $arr = $this->getFields($table, ['password', 'remember_token'], $this->app_id);
            $a = array_intersect($res, $arr);
            $b = array_diff($res, $arr);
            $c = array_diff($arr, $res);
            $res = array_merge($a, $b, $c);
        }
        return $res;
    }

	public function createNewQuery(Request $request)
    {
        \Log::Info($this->fc.'createNewQuery');
        $request->validate([
            "name" => "required",
            "auth_providers" => "required",
            "tables" => "required",
            "commands" => "required",
        ]);
        Query::create([
            "app_id" => \Auth::user()->active_app_id,
            'name' => $request->name,
            "auth_providers" => $request->auth_providers,
            "tables" => $request->tables,
            "commands" => $request->commands,
            "fillables" => $request->fillables??null,
            "hiddens" => $request->hiddens??null,
            "mandatory" => $request->mandatory??null,
            "joins" => $request->joins??null,
            "filters" => $request->filters??null,
            "specials" => $request->specials??null,
        ]);
        return redirect()->route('c.query.list.view');
    }

    public function queryDetailsView(Request $request, $id)
    {
        \Log::Info($this->fc.'queryDetailsView');
        $query = Query::findOrFail($id);
        $app = App::findOrFail(\Auth::user()->active_app_id);
        $commands = ['ReadAll'=>'all', 'Create'=>'new', 'Read'=>'get', 'Update'=>'mod', 'Delete'=>'del', 
        'SignUp' => 'signup', 'SendEmailVerificationCode' => 'sevc', 'VerifyEmail' => 've', 'Login' => 'login', 
        'ConditionalLogin' => 'clogin', 'RefreshToken' => 'refresh', 'FilesUpload' => 'files_upload', 'SendMail' => 'mail'
        , 'PushSubscribe' => 'ps', 'GetAppSecret' => 'secret'];
        $specials = ['pluck', 'count', 'max', 'min', 'avg', 'sum'];
        return view($this->theme.'.q.update_query')->with([
            'query'=> $query,
            'auth_providers' => json_decode($app->auth_providers,true)??[], 
            'tables' => $this->getTables(),
            'commands' => $commands,
            'specials' => $specials,
        ]);
    }

    public function updateQuery(Request $request)
    {
        \Log::Info($this->fc.'updateQuery');
        $request->validate([
            "name" => "required",
            "auth_providers" => "required",
            "tables" => "required",
            "commands" => "required",
        ]);
        Query::findOrFail($request->id)->update([
            'name' => $request->name,
            "auth_providers" => $request->auth_providers,
            "tables" => $request->tables,
            "commands" => $request->commands,
            "fillables" => $request->fillables??null,
            "hiddens" => $request->hiddens??null,
            "mandatory" => $request->mandatory??null,
            "joins" => $request->joins??null,
            "filters" => $request->filters??null,
            "specials" => $request->specials??null,
        ]);
        return redirect()->route('c.query.list.view');
    }

    public function deleteQuery(Request $request)
    {
        \Log::Info($this->fc.'deleteQuery');
        Query::destroy($request->id);
        return ['status' => 'success'];
    }

    public function copyQueries($new_app_id, $app_id)
    {
        \Log::Info($this->fc.'copyQueries');
        $queries = Query::where('app_id',$app_id)->get();
        foreach ($queries as $query) {
            $q = Query::findOrFail($query->id)->replicate();
            $q->app_id = $new_app_id;
            $q->save();
        }
        return true;
    }

    public function deleteQueries($app_id)
    {
        \Log::Info($this->fc.'deleteQueries');
        $queries = Query::where('app_id',$app_id)->get();
        foreach ($queries as $query) {
            Query::destroy($query->id);
        }
        return true;
    }

    public function customValidMsgView(Request $request)
    {
        \Log::Info($this->fc.'customValidMsgView');
        $rules = ValidationMessage::where('app_id', $this->app_id)->pluck('error_message','rule');
        $crules = ValidationMessage::where('app_id', null)->orWhere('app_id',0)->paginate(10);
        // $arr = $this->getValidationMessages('keys');
        // $lookup = $this->getValidationMessages();
        // $crules = Paginator::make($arr, count($arr), 10);
        // $crules = new Paginator($arr, count($arr), 10, $request->page??1, [
        //     'path'  => $request->url(),
        //     'query' => $request->query(),
        // ]);
        // \Log::Info($crules);
        return view($this->theme.'.q.custom_valid_msg')->with([
            'crules'=>$crules, 'rules'=>$rules, 'page'=>$request->page??1]);
    }

    public function customValidMsg(Request $request)
    {
        \Log::Info($this->fc.'customValidMsg');
        $request->validate([
            'rule'=>'max:255', 
            'error_message'=>'max:255'
        ]);
        $error = ValidationMessage::firstOrCreate(['app_id' => $this->app_id, 'rule' => $request->rule]);
        if(!empty($request->error_message)){
            $error->update(['error_message' => $request->error_message]);
            $error->save();
        }else{
            $error->delete();
        }
        return ['status'=>'success', 'message' => 'custom error message added successfully.'];
    }

    public function customValidRulesView(Request $request)
    {
        \Log::Info($this->fc.'customValidRulesView');
        $app_fields = $this->getAppFields(['id', 'password', 'remember_token']);
        $date_fields = $this->getAppFieldsOfDataTypes(['date']);
        $rules = ValidationMessage::where('app_id', null)->pluck('rule');
        $frules = ValidationRule::where('app_id',$this->app_id)->paginate(10);
        return view($this->theme.'.q.custom_valid')->with([
            'fields' => $app_fields, 'date_fields'=>$date_fields, 'rules'=>$rules, 'frules'=>$frules, 'page'=>$request->page??1]);
    }

    public function customValidView(Request $request)
    {
        \Log::Info($this->fc.'customValidView');
        $app_tables = $this->getTables();
        $app_fields = $this->getAppFields(['id', 'password', 'remember_token']);
        $date_fields = $this->getAppFieldsOfDataTypes(['date']);
        $rules = ValidationMessage::where('app_id', null)->pluck('rule');
        $frules = ValidationRule::where('app_id',$this->app_id)->paginate(10);
        return view($this->theme.'.q.custom_valid')->with(['tables' => $app_tables, 'fields' => $app_fields, 
            'date_fields'=>$date_fields, 'rules'=>$rules, 'frules'=>$frules, 'page'=>$request->page??1]);
    }

    public function customValid(Request $request)
    {
        \Log::Info($this->fc.'customValid');
        \Log::Info($request->rule_id);
        if($request->cmd == 'Add'){
            $request->validate(['field'=>'required|max:255', 'rule'=>'required|max:255']);
            $id = ValidationRule::firstOrCreate(['app_id' => $this->app_id, 'field' => $request->field])->id;
            ValidationRule::findOrFail($id)->update(['rule' => $request->rule]);
        }else if($request->cmd == 'Edit'){
            $request->validate(['rule_id'=>'required|numeric|max:4294967295']);
            ValidationRule::findOrFail($request->rule_id)->update(['rule' => $request->rule]);
        }
        return redirect()->route('c.query.valid.view');
    }

    public function deleteCustomValid(Request $request)
    {
        \Log::Info($this->fc.'deleteCustomValid');
        $request->validate(['id'=>'numeric']);
        ValidationRule::destroy($request->id);
        return ['message' => 'validation rule was successfully deleted'];
    }

}