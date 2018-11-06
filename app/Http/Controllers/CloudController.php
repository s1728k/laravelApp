<?php

namespace App\Http\Controllers;
// $computerId = $_SERVER['HTTP_USER_AGENT'].$_SERVER['LOCAL_ADDR'].$_SERVER['LOCAL_PORT'].$_SERVER['REMOTE_ADDR'];
// $browser = get_browser();
use App\User;
use App\Admin;
use App\App;
use App\License;
use App\LicenseDetail;
use App\Chat;
use App\Comment;
use App\Traits\Crone;
use App\Traits\LicensesSoftwares;
use App\Traits\CreatesTables;
use App\Traits\SqlQueries;
use App\Traits\EmailAccounts;
use App\Traits\FilesStore;
use App\Traits\ValidatesRequests;
use App\Traits\PushesNotifications;
use Illuminate\Http\Request;

class CloudController extends Controller
{
    use Crone;
    use LicensesSoftwares;
    use CreatesTables;
    use SqlQueries;
    use EmailAccounts;
    use FilesStore;
    use ValidatesRequests;
    use PushesNotifications;

    public $app_id;

	protected $rtype = '';
    protected $auth = 'auth';
    protected $theme = 'cb';

    public function __construct($rtype, $auth, $theme)
    {
        $this->rtype = $rtype;
        $this->auth = $auth;
        $this->theme = $theme;
        $this->middleware($this->auth);
        $this->middleware(function ($request, $next) {
            $this->app_id = \Auth::user()->active_app_id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        \Log::Info(request()->ip()." visited app list page.");
        if($this->app_id == 0){
            $this->createNewAppAndAssociatives($request);
        }
    	return redirect()->intended($this->rtype==""?'/app/app-list':'/admin/app/app-list');
    }

    public function createNewApp(Request $request)
    {
        \Log::Info(request()->ip()." created New App.");
        $this->createNewAppAndAssociatives($request);
        return redirect()->route('c.app.list.view');
    }

    public function appListView(Request $request)
    {
        \Log::Info(request()->ip()." visited app list page.");
        if($this->app_id == 0){
            $this->createNewAppAndAssociatives($request);
        }
        $apps = App::where('user_id', \Auth::user()->id)->paginate(10);
        return view($this->theme.'.myapp_list')->with([
            'apps' => $apps, 
            'page' => $request->page??1,
            'active_app' => App::findOrFail($this->app_id),
        ]);
    }

    public function appOriginsView(Request $request, $id)
    {
        \Log::Info(request()->ip()." visited app origins page.");
        $app = App::findOrFail($id);
        return view($this->theme.'.app_origins')->with([
            'id' => $id,
            'origins' => json_decode($app->origins, true)??[],
        ]);
    }

    public function addNewOrigin(Request $request, $id)
    {
        \Log::Info(request()->ip()." added new origin ".$request->name." for app id ".$this->app_id);
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);
        $app = App::findOrFail($id);
        $or = json_decode($app->origins, true)??[];
        array_push($or, $request->name);
        $app->update(['origins' => json_encode($or)]);
        return redirect()->route('c.app.origins.view', ['id' => $id]);
    }

    public function deleteOrigin(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);
        \Log::Info(request()->ip()." delete origin ".$request->name." for app id ".$this->app_id);
        $app = App::findOrFail($id);
        $arr = json_decode($app->origins, true)??[];
        $key = array_search ($request->name, $arr);
        if(!empty($key)){
            unset($arr[$key]);
            $app->update(['origins' => json_encode($arr)]);
        }
        return 'success';
    }

    public function appActivate(Request $request)
    {
        \Log::Info(request()->ip()." activated app_id ".$request->active_app_id);
        \Auth::user()->active_app_id = $request->active_app_id;
        \Auth::user()->save();
        return ['status' => 'success'];
    }

    private function createNewAppAndAssociatives(Request $request)
    {
        \Log::Info(request()->ip()." created new app and associatives");
        $this->validateCreateAppRequest($request);
        $id = App::create([
            'name' => $request->name??'My App',
            'user_id' => \Auth::user()->id,
            'secret' => bcrypt(uniqid(rand(), true)),
            'permissions' => json_encode(array('c' => '', 'r' => '', 'u' => '', 'd' => '')),
            'roles' => "",
            'auth_providers' => json_encode(array('users')),
            'blocked' => false,
            'origins' => "",
        ])->id;
        $this->app_id = $id;
        \Auth::user()->save();

        $this->createDefaultUsersTable($id);
    }

    public function updateApp(Request $request)
    {
        \Log::Info(request()->ip()." updated app ".$request->id);
        if(isset($request->request_new_secret)){
            App::findOrFail($request->id)->update([
                'name' => $request->new_app_name??'My App',
                'secret' => bcrypt(uniqid(rand(), true)),
            ]);
        }else{
            App::findOrFail($request->id)->update([
                'name' => $request->new_app_name??'My App',
            ]);
        }
        
        return redirect()->route('c.app.list.view');
    }

    public function appFiltersView(Request $request, $id)
    {
        \Log::Info(request()->ip()." visited app roles page for app ".$id);
        $app = App::findOrFail($id);
        $table_filters = json_decode($app->table_filters, true)??[];
        return view($this->theme.'.app_filters')->with([
            'selected_app' => $app,
            'table_filters' => $table_filters,
        ]);
    }

    public function saveFilters(Request $request, $id)
    {
        \Log::Info(request()->ip()." requested to save roles for app ".$id);
        $app = App::findOrFail($id)->update([
            'table_filters' => json_encode($request->f),
        ]);
        return ['status' => 'success'];
    }

    public function appRolesView(Request $request, $id)
    {
        \Log::Info(request()->ip()." visited app roles page for app ".$id);
        $app = App::findOrFail($id);
        $auth_providers = json_decode($app->auth_providers, true)??[];
        return view($this->theme.'.app_roles')->with([
            'selected_app' => $app,
            'auth_providers' => $auth_providers,
        ]);
    }

    public function saveRoles(Request $request, $id)
    {
        \Log::Info(request()->ip()." requested to save roles for app ".$id);
        \Log::Info($request->r);
        $app = App::findOrFail($id)->update([
            'auth_providers' => json_encode($request->r),
        ]);
        return ['status' => 'success'];
    }

    public function appPermissionsView(Request $request, $id)
    {
        \Log::Info(request()->ip()." visited app permissions page for app ".$id);
        $app = App::findOrFail($id);
        $tables = $this->getTables($id);
        $user_type_fields = [];
        $user_role_fields =[];
        $user_id_fields = [];
        $fields = [];
        foreach ($tables as $table) {
            $user_type_fields[$table]=$this->getFieldsLike($table, ["\_by", "\_type"], $id);
            $user_role_fields[$table]=$this->getFieldsLike($table, ["\_role"], $id);
            $user_id_fields[$table]=$this->getFieldsLike($table, ["\_id"], $id);
            $fields[$table]=$this->getRemovableFields($table, $id);
        }
        $perm = json_decode($app->permissions, true)??[];
        $arr = json_decode($app->auth_providers, true)??[];
        $tfs = json_decode($app->table_filters, true)??[];
        $p = [];
        foreach ($arr as $ap => $roles) {
            $r=[];
            foreach ($roles['r'] as $role) {
                $t=[];
                foreach ($tfs as $table => $filters) {
                    $f=[];
                    foreach ($filters as $filter) {
                        if(isset($perm[$ap][$role][$table]['f'][$filter])){
                            $f[$filter] = $perm[$ap][$role][$table]['f'][$filter];
                            $t[$table] = ['f'=>$f, 'p'=>$perm[$ap][$role][$table]['p']];
                        }else{
                            $f[$filter] = ['p' => "none", 'u' => "none", 'r' => "none", 'd' => "none", 'uf' => []];
                            $t[$table] = ['f'=>$f, 'p'=>0];
                        }
                        $r[$role] = $t;
                        $p[$ap] = $r;
                    }
                }
            }
        }
        return view($this->theme.'.app_permissions')->with([
            'selected_app' => $app,
            'user_type_fields' => $user_type_fields,
            'user_role_fields' => $user_role_fields,
            'user_id_fields' => $user_id_fields,
            'fields' => $fields,
            'p' => $p,
        ]);
    }

    public function savePermissions(Request $request, $id)
    {
        \Log::Info(request()->ip()." saved app permissions for app ".$id);
        $app = App::findOrFail($id)->update([
            'permissions' => json_encode($request->p),
        ]);
        return ['status' => 'success'];
    }

    public function gtc($table, $app_id=null)
    {
        $this->app_id = $app_id??$this->app_id;
        $table_name = $this->tClass($table);
        $myFilePath = app_path() ."/Models/$table_name.php";
        if(!file_exists($myFilePath)){
            $arr = json_decode(App::findOrFail($this->app_id)->auth_providers, true);
            $this->createModelClass($table, in_array($table, $arr));
        }
        return "App\\Models\\".$table_name;
    }

    public function tClass($table)
    {
        return ucwords(rtrim('app'.$this->app_id.'_'.$table,'s'));
    }

    private function table($table)
    {
        return 'app'.$this->app_id.'_'.$table;
    }

    public function vbaObfuView()
    {
        return view($this->theme.'.obfu.vba');
    }

}
