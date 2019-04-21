<?php

namespace App\Traits;

use App\Query;
use App\App;
use Illuminate\Http\Request;

trait CreatesQueries
{

    public function queryListView()
    {
        \Log::Info(request()->ip()." visited query list page for app id ".$this->app_id);
        return view($this->theme.'.q.query_list')->with([
            'queries' => Query::where('app_id', \Auth::user()->active_app_id)->paginate(10), 
            'page' => $request->page??1
        ]);
    }

    public function createNewQueryView(Request $request)
    {
        \Log::Info(request()->ip()." visited create new query page for app id ".$this->app_id);
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
        \Log::Info(request()->ip()." requested columns for the table ".$request->table." for app id ".$this->app_id);
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
        \Log::Info(request()->ip()." created new query ".$request->query_nick_name." for app id ".$this->app_id);
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
        \Log::Info(request()->ip()." visited update query page id=".$id." for app id ".$this->app_id);
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
        \Log::Info(request()->ip()." updated query ".$request->id." for query ".$request->query_nick_name." for app id ".$this->app_id);
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
        \Log::Info(request()->ip()." deleted query ".$request->id." for app id ".$this->app_id);
        Query::destroy($request->id);
        return ['status' => 'success'];
    }

}