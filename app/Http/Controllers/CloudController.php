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
use App\File;
use App\RechargeHistory;
use App\UsageReport;
use App\Mail\CommonMail;
use App\Traits\Crone;
use App\Traits\LicensesSoftwares;
use App\Traits\CreatesTables;
use App\Traits\SqlQueries;
use App\Traits\ExportsDb;
use App\Traits\CreatesQueries;
use App\Traits\EmailAccounts;
use App\Traits\FilesStore;
use App\Traits\ValidatesRequests;
use App\Traits\PushesNotifications;
use App\Traits\SendsChatMessages;
use App\Traits\UtilityFunctions;
use App\Traits\StoresSessionTokens;
use App\Traits\Paytm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CloudController extends Controller
{
    use Crone;
    use LicensesSoftwares;
    use CreatesTables;
    use SqlQueries;
    use ExportsDb;
    use CreatesQueries;
    use EmailAccounts;
    use FilesStore;
    use ValidatesRequests;
    use PushesNotifications;
    use SendsChatMessages;
    use UtilityFunctions;
    use StoresSessionTokens;
    use Paytm;

    public $con;
    public $app_id;
    public $aid;
    public $fid;
    public $fap;
    public $fname;
    public $fromWeb;
    public $fc;

	protected $rtype = '';
    protected $auth = 'auth';
    protected $theme = 'cb';

    public function __construct($rtype, $auth, $theme)
    {
        $this->fc = "CloudController::";
        $this->rtype = $rtype;
        $this->auth = $auth;
        $this->theme = $theme;
        $this->middleware($this->auth);
        $this->middleware(function ($request, $next) {
            $this->aid = 1;
            $this->fid = \Auth::user()->id;
            $this->fap = 'users';
            $this->fname = \Auth::user()->name;
            $this->fromWeb = 1;
            
            $this->app_id = \Auth::user()->active_app_id;
            $this->con = $this->app_id?App::findOrFail($this->app_id)->db_connection:'apps_db';
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        \Log::Info($this->fc.'index');
        if($this->app_id == 0){
            $this->createNewAppAndAssociatives($request);
        }
    	return redirect()->intended($this->rtype==""?'/app/app-list':'/admin/app/app-list');
    }

    public function addAvatar(Request $request)
    {
        \Log::Info($this->fc.'addAvatar');
        try{
            if(filter_var($request->avatar, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)){
                \Auth::user()->avatar = $request->avatar;
                \Auth::user()->save();
                return ['status' => 'success', 'message' => 'Avatar added successfully'];
            }else{
                return ['status' => 'failed', 'message' => 'Avatar url not valid'];
            }
        }catch(Exception $e){
            return ['status' => 'failed', 'message' => 'Avatar url not valid'];
        }
    }

    public function inviteFriend(Request $request)
    {
        \Log::Info($this->fc.'inviteFriend');
        try{
            if(filter_var($request->email, FILTER_VALIDATE_EMAIL)){
                if(empty(('App\\User')::where('email',$request->email)->first())){
                    $invite_url = url('register');
                    Mail::to($request->email)->send(new CommonMail([
                        'from_name' => 'HoneyWeb.Org',
                        'from_email' => 'no_reply@honeyweb.org',
                        'subject' => 'Congratulations! you have been invited to join HoneyWeb.Org by your friend.',
                        'message' => ['title'=>'Invitation to join HoneyWeb.Org', 
                        'Click this link to signup' => $invite_url],
                    ]));
                    return ['status' => 'success', 'message' => 'invitation sent to your friend'];
                }else{
                    return ['status' => 'warning', 'message' => 'this email is already registered with us'];
                }
            }else{
                return ['status' => 'failed', 'message' => 'invalid email'];
            }
        }catch(Exception $e){
            return ['status' => 'failed', 'message' => 'invalid email'];
        }
    }

    public function createNewApp(Request $request)
    {
        \Log::Info($this->fc.'createNewApp');
        $this->createNewAppAndAssociatives($request);
        return redirect()->route('c.app.list.view');
    }

    public function appListView(Request $request)
    {
        \Log::Info($this->fc.'appListView');
        if($this->app_id == 0){
            $this->createNewAppAndAssociatives($request);
        }
        $apps = App::where(['user_id' => \Auth::user()->id])->paginate(10);
        return view($this->theme.'.app.myapp_list')->with([
            'apps' => $apps, 
            'page' => $request->page??1,
            'active_app' => App::findOrFail($this->app_id),
        ]);
    }

    public function invitedAppListView(Request $request)
    {
        \Log::Info($this->fc.'invitedAppListView');
        $app_ids = json_decode(\Auth::user()->invited_apps??"[]",true);
        $apps = App::whereIn('id', $app_ids)->paginate(10);
        return view($this->theme.'.app.invited_app_list')->with([
            'apps' => $apps, 
            'page' => $request->page??1,
            'active_app' => App::findOrFail($this->app_id),
        ]);
    }

    public function publicAppListView(Request $request)
    {
        \Log::Info($this->fc.'publicAppListView');
        $apps = App::where(['user_id' => \Auth::user()->id, 'availability' => 'public'])->paginate(10);
        return view($this->theme.'.app.public_app_list')->with([
            'apps' => $apps, 
            'page' => $request->page??1,
            'active_app' => App::findOrFail($this->app_id),
        ]);
    }

    public function appOriginsView(Request $request, $id)
    {
        \Log::Info($this->fc.'appOriginsView');
        $app = App::findOrFail($id);
        return view($this->theme.'.app.app_origins')->with([
            'id' => $id,
            'origins' => json_decode($app->origins, true)??[],
        ]);
    }

    public function addNewOrigin(Request $request, $id)
    {
        \Log::Info($this->fc.'addNewOrigin');
        $err = ['active_url' => 'The name must be an active url or a valid IP address.', 
                'ip' => 'The name must be an active url or a valid IP address.'];
        if(str_replace('localhost','',$request->name) != $request->name || $request->name == '*'){

        }elseif(str_replace('http','',$request->name) != $request->name){
            $request->validate(['name' => ['required', 'active_url', 'max:255'] ], $err);
        }else{
            $request->validate(['name' => ['required', 'ip', 'max:255'] ], $err);
        }
        $app = App::findOrFail($id);
        $or = json_decode($app->origins, true)??[];
        array_push($or, $request->name);
        $app->update(['origins' => json_encode($or)]);
        return redirect()->route('c.app.origins.view', ['id' => $id]);
    }

    public function deleteOrigin(Request $request, $id)
    {
        \Log::Info($this->fc.'deleteOrigin');
        $app = App::findOrFail($id);
        $arr = json_decode($app->origins, true)??[];
        array_splice($arr, array_search($request->name, $arr), 1);
        $app->update(['origins' => json_encode($arr)]);
        return ['status'=>'success'];
    }

    public function invitedUsersView(Request $request, $id)
    {
        \Log::Info($this->fc.'invitedUsersView');
        $app = App::findOrFail($id);
        $invited_users = ('App\\User')::select(['id', 'name', 'email'])->whereIn('id',json_decode($app->invited_users??'[]', true))->get();
        \Log::Info(json_decode($app->invited_users??'[]', true));
        return view($this->theme.'.app.invited_users')->with([
            'id' => $id,
            'invited_users' => $invited_users,
        ]);
    }

    public function inviteNewUser(Request $request)
    {
        \Log::Info($this->fc.'inviteNewUser');
        try{
            if(filter_var($request->email, FILTER_VALIDATE_EMAIL)){
                $msg_obj = [
                    'from_name' => 'HoneyWeb.Org',
                    'from_email' => 'no_reply@honeyweb.org',
                    'subject' => 'Congratulations! you have been invited to join HoneyWeb.Org by your friend.',
                    'message' => ['title'=>'Invitation to join HoneyWeb.Org', 
                    'Click this link to signup' => url('register')],
                ];
                $invited_user = ('App\\User')::where('email',$request->email)->first();
                if(!empty($invited_user)){
                    $app = App::findOrFail($request->app_id);
                    $app_name = $app->name;
                    $app_ids = json_decode($invited_user->invited_apps??"[]",true);
                    if(in_array($app->id, $app_ids)){
                        $this->returnValidateError($request, 'email', 'email already in invited users list');
                    }
                    array_push($app_ids, $app->id);
                    $invited_user->invited_apps = json_encode($app_ids);
                    $invited_user->save();

                    $invited_users = json_decode($app->invited_users??'[]',true);
                    array_push($invited_users, $invited_user->id);
                    $app->invited_users = json_encode($invited_users);
                    $app->save();

                    $msg_obj['subject'] = 'Hi! you have been invited to work on app '.$app_name;
                    $msg_obj['message'] = ['title'=>'Invitation to work on app '.$app_name, 
                    'Click this link to login' => url('login')];
                    Mail::to($request->email)->send(new CommonMail($msg_obj));
                    return redirect()->route('c.invited.users.view',['id'=>$app->id]);
                }else{
                    Mail::to($request->email)->send(new CommonMail($msg_obj));
                    $this->returnValidateError($request, 'email', 'this email is not registered with us. invitation has been sent to signup');
                }
            }else{
                $this->returnValidateError($request, 'email', 'invalid email');
            }
        }catch(Exception $e){
            $this->returnValidateError($request, 'email', 'invalid email');
        }
    }

    public function deleteInvitedUser(Request $request)
    {
        \Log::Info($this->fc.'deleteInvitedUser');
        $request->validate(['app_id'=>'numeric','user_id'=>'numeric']);
        $invited_user = ('App\\User')::findOrFail($request->user_id);
        $app = App::findOrFail($request->app_id);

        $invited_apps = json_decode($invited_user->invited_apps??'[]', true);
        array_splice($invited_apps, array_search($app->id, $invited_apps),1);
        $invited_user->update(['invited_apps'=>$invited_apps?json_encode($invited_apps):null]);
        $invited_user->save();

        $invited_users = json_decode($app->invited_users??'[]', true);
        array_splice($invited_users, array_search($invited_user->id, $invited_users),1);
        $app->update(['invited_users'=>$invited_users?json_encode($invited_users):null]);
        $app->save();

        return ['message' => 'success'];
    }

    public function appActivate(Request $request)
    {
        \Log::Info($this->fc.'appActivate');
        \Auth::user()->active_app_id = $request->active_app_id;
        \Auth::user()->save();
        return ['status' => 'success'];
    }

    private function createNewAppAndAssociatives(Request $request)
    {
        \Log::Info($this->fc.'createNewAppAndAssociatives');
        $request->validate(['name' => 'required|string|max:255']);
        $id = App::create([
            'name' => $request->name??'My App',
            'user_id' => \Auth::user()->id,
            'secret' => bcrypt(uniqid(rand(), true)),
            'auth_providers' => json_encode(array('guest', 'users')),
            'blocked' => false,
            'origins' => "",
        ])->id;
        $this->app_id = $id;
        \Auth::user()->active_app_id = $id;
        \Auth::user()->save();

        $this->createDefaultUsersTable($id);
    }

    public function updateApp(Request $request)
    {
        \Log::Info($this->fc.'updateApp');
        if(isset($request->request_new_secret)){
            App::findOrFail($request->id)->update([
                'name' => $request->new_app_name??'My App',
                'token_lifetime' => $request->token_lifetime??43200,
                'availability' => $request->availability??'Private',
                'secret' => bcrypt(uniqid(rand(), true)),
            ]);
        }else{
            App::findOrFail($request->id)->update([
                'name' => $request->new_app_name??'My App',
                'token_lifetime' => $request->token_lifetime??43200,
                'availability' => $request->availability??'Private',
            ]);
        }
        
        return redirect()->route('c.app.list.view');
    }

    public function appUserNameFieldsView(Request $request, $id)
    {
        \Log::Info($this->fc.'appUserNameFieldsView');
        $app = App::findOrFail($id);
        $ap = json_decode($app->auth_providers,true)??[];
        $ap = array_slice($ap,1);
        $aunf = json_decode($app->user_name_fields??'',true)??[];
        $fields = [];
        foreach ($ap as $a) {
            if(empty($aunf[$a])){
                $aunf[$a] = ['email'];
            }
            $fields[$a] = $this->getFields($a, ['id', 'created_at', 'updated_at'], $id);
        }
        return view($this->theme.'.app.app_user_name_fields')->with(['ap' => $ap, 'aunf' => $aunf, 'fields'=>$fields,'id'=>$id]);
    }

    public function saveUserNameFields(Request $request)
    {
        \Log::Info($this->fc.'saveUserNameFields');
        $request->validate(['user_name_fields'=>'json', 'id'=>'numeric']);
        $app = App::findOrFail($request->id)->update([
            'user_name_fields' => $request->user_name_fields,
        ]);
        return ['status' => 'success','message'=>'User name fields saved successfully.'];
    }

    public function appDescView(Request $request, $id)
    {
        \Log::Info($this->fc.'appDescView');
        $desc = App::findOrFail($id)->description;
        $name = App::findOrFail($id)->name;
        return view($this->theme.'.app.app_description')->with(['name'=>$name, 'desc'=>$desc,'id'=>$id]);
    }

    public function saveAppDesc(Request $request)
    {
        \Log::Info($this->fc.'saveAppDesc');
        $request->validate(['description'=>'required|string|max:65536', 'id'=>'numeric']);
        $app = App::findOrFail($request->id)->update([
            'description' => $request->description,
        ]);
        return ['status' => 'success','message'=>'description saved successfully.'];
    }

    public function copyApp(Request $request)
    {
        \Log::Info($this->fc.'copyApp');
        $request->validate(['id'=>'required|numeric']);
        $app = App::findOrFail($request->id)->replicate();
        $app->user_id = \Auth::user()->id;
        $app->availability = 'Private';
        $app->secret = bcrypt(uniqid(rand(), true));
        $app->save();
        \Auth::user()->active_app_id = $app->id;
        \Auth::user()->save();
        $this->copyTables($app->id, $request->id);
        $this->copyQueries($app->id, $request->id);
        return ['status' => 'success','message'=>'app copied successfully.'];
    }

    public function deleteApp(Request $request)
    {
        \Log::Info($this->fc.'deleteApp');
        $request->validate(['id'=>'required|numeric']);
        $first_app = App::where('user_id', \Auth::user()->id)->first();
        if(empty($first_app)){
            return ['status'=>'warning', 'message'=>'atleast one app is required.'];
        }
        \Auth::user()->active_app_id = $first_app->id;
        \Auth::user()->save();
        App::destroy($request->id);
        $this->deleteTables($request->id);
        $this->deleteQueries($request->id);
        return ['status' => 'success','message'=>'app deleted successfully.'];
    }

    public function logView(Request $request)
    {
        \Log::Info($this->fc.'logView');
        return view('cb.logs')->with([
            'logs' => ('App\\Log')::where('aid', $this->app_id)->latest()->paginate(10), 
            'page'=>$request->page??1
        ]);
    }

    public function usageReportView(Request $request)
    {
        \Log::Info($this->fc.'usageReportView');
        $ur = UsageReport::where(['user_id' => \Auth::user()->id])->orderBy('app_id')->paginate(10);
        return view($this->theme.'.user.usage_report')->with([
            'ur' => $ur, 
            'page' => $request->page??1,
            'size' => $this->getUserStorageFootPrint(),
        ]);
    }

    public function rechargeOffersView(Request $request)
    {
        \Log::Info($this->fc.'rechargeOffersView');
        return view($this->theme.'.user.recharge_offers')->with([]);
    }

    public function rechargeHistoryView(Request $request)
    {
        \Log::Info($this->fc.'rechargeHistoryView');
        $rh = RechargeHistory::where(['user_id' => \Auth::user()->id])->paginate(10);
        return view($this->theme.'.user.recharge_history')->with([
            'rh' => $rh, 
            'page' => $request->page??1,
        ]);
    }

    public function vbaObfuView()
    {
        \Log::Info($this->fc.'vbaObfuView');
        return view($this->theme.'.obfu.vba');
    }

}
