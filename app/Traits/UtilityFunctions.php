<?php

namespace App\Traits;

use App\App;
use App\VirtualDomain;
use App\VirtualUser;
use App\ValidationMessage;
use App\PushSubscription;
use App\PushMessage;
use App\UsageReport;
use App\Mail\CommonMail;
use Illuminate\Support\Facades\Mail;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

trait UtilityFunctions
{
	public function dateFilter($request, $query)
    {
        \Log::Info($this->fc.'dateFilter');
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

    public function whereJoins($query, $joins, $table)
    {
        \Log::Info($this->fc.'whereJoins');
        foreach ($joins as $join) {
            $j = explode(",", str_replace(', ',',',$join));
            $jc = count($j);
            if($jc==4){
                $query = $query->join('app'.$this->app_id.'_'.$j[0], 'app'.$this->app_id.'_'.$table.'.'.$j[1], $j[2], 'app'.$this->app_id.'_'.$j[0].'.'.$j[3]);
            }elseif($jc==3){
                $query = $query->join('app'.$this->app_id.'_'.$j[0], 'app'.$this->app_id.'_'.$table.'.'.$j[1], 'app'.$this->app_id.'_'.$j[0].'.'.$j[2]);
            }
        }
        return $query;
    }

    public function whereAppends($collection, $hiddens)
    {
        \Log::Info($this->fc.'whereAppends');
        $table_class = $this->gtc('models', null, $hiddens);
        return $collection->map(function ($item) use($table_class) {
            $item['models'] = $table_class::where('brand_id',$item['id'])->get();
            return $item;
        });
    }
    
    public function whereFilters($query, $filters, $table = null)
    {
        \Log::Info($this->fc.'whereFilters');
        $tp = $table?'app'.$this->app_id.'_'.$table.'.':'';
        foreach ($filters as $filter) {
            $f = explode(",", str_replace(', ',',',$filter));
            $fc = count($f);
            if($f[0] == 'where' || $f[0] == 'having'){
                if($fc==4){
                    $query = $query->where($tp.$f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->where($tp.$f[1],$f[2]);
                }
            }elseif($f[0] == 'orWhere'){
                if($fc==4){
                    $query = $query->orWhere($tp.$f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->orWhere($tp.$f[1],$f[2]);
                }
            }elseif($f[0] == 'whereBetween'){
                if($fc==5){
                    $query = $query->whereBetween($tp.$f[1],$f[2],[$f[3],$f[4]]);
                }elseif($fc==4){
                    $query = $query->whereBetween($tp.$f[1],[$f[2],$f[3]]);
                }
            }elseif($f[0] == 'whereNotBetween'){
                if($fc==5){
                    $query = $query->whereNotBetween($tp.$f[1],$f[2],[$f[3],$f[4]]);
                }elseif($fc==4){
                    $query = $query->whereNotBetween($tp.$f[1],[$f[2],$f[3]]);
                }
            }elseif($f[0] == 'whereIn'){
                if($fc>4){
                    $query = $query->whereIn($tp.$f[1],$f[2],implode(',',array_slice($f,3)));
                }elseif($fc>3){
                    $query = $query->whereIn($tp.$f[1],implode(',',array_slice($f,2)));
                }
            }elseif($f[0] == 'whereNotIn'){
                if($fc>4){
                    $query = $query->whereNotIn($tp.$f[1],$f[2],implode(',',array_slice($f,3)));
                }elseif($fc>3){
                    $query = $query->whereNotIn($tp.$f[1],implode(',',array_slice($f,2)));
                }
            }elseif($f[0] == 'whereNull' && $fc>1){
                $query = $query->whereNull($tp.$f[1]);
            }elseif($f[0] == 'whereNotNull' && $fc>1){
                $query = $query->whereNotNull($tp.$f[1]);
            }elseif($f[0] == 'whereDate'){
                if($fc==4){
                    $query = $query->whereDate($tp.$f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereDate($tp.$f[1],$f[2]);
                }
            }elseif($f[0] == 'whereMonth'){
                if($fc==4){
                    $query = $query->whereMonth($tp.$f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereMonth($tp.$f[1],$f[2]);
                }
            }elseif($f[0] == 'whereDay'){
                if($fc==4){
                    $query = $query->whereDay($tp.$f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereDay($tp.$f[1],$f[2]);
                }
            }elseif($f[0] == 'whereYear'){
                if($fc==4){
                    $query = $query->whereYear($tp.$f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereYear($tp.$f[1],$f[2]);
                }
            }elseif($f[0] == 'whereTime'){
                if($fc==4){
                    $query = $query->whereTime($tp.$f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->whereTime($tp.$f[1],$f[2]);
                }
            }elseif($f[0] == 'whereColumn'){
                if($fc==4){
                    $query = $query->whereColumn($tp.$f[1],$f[2],$tp.$f[3]);
                }elseif($fc==3){
                    $query = $query->whereColumn($tp.$f[1],$tp.$f[2]);
                }
            }elseif($f[0] == 'orderBy'){
                if($fc==4){
                    $query = $query->orderBy($tp.$f[1],$f[2],$f[3]);
                }elseif($fc==3){
                    $query = $query->orderBy($tp.$f[1],$f[2]);
                }elseif($fc==2){
                    $query = $query->orderBy($tp.$f[1]);
                }
            }elseif($f[0] == 'latest' && !$tp){
                $query = $query->latest();
            }elseif($f[0] == 'oldest' && !$tp){
                $query = $query->oldest();
            }elseif($f[0] == 'inRandomOrder' && !$tp){
                $query = $query->inRandomOrder();
            }elseif($f[0] == 'distinct' && !$tp){
                $query = $query->distinct();
            }elseif($f[0] == 'offset' || $f[0] == 'skip'){
                if($fc==2){
                    $query = $query->offset($tp.$f[1]);
                }
            }elseif($f[0] == 'limit' || $f[0] == 'take'){
                if($fc==2){
                    $query = $query->limit($tp.$f[1]);
                }
            }elseif($f[0] == 'groupBy'){
                if($fc>1){
                    $query = $query->groupBy(implode(',',array_slice(array_map(function($v)use($tp){return $tp.$v;}, $f),1)));
                }
            }
        }
        return $query;
    }

    public function returnValidateError($request, $field, $error)
    {
        $request->validate([$field => [function($attribute, $value, $fail)use($error){
            $fail($error);
        }]]);
    }

    public function isDomainEmailValid($user_id, $email)
    {
        \Log::Info($this->fc.'isDomainEmailValid');
        $vuser = VirtualUser::where(['user_id' => $user_id, 'email' => $email])->first();
        if(empty($vuser)){
            return false;
        }

        $domain = VirtualDomain::findOrFail($vuser->domain_id);

        return $this->isDomainValid($domain);
    }

    public function isDomainValid($domain)
    {
        \Log::Info($this->fc.'isDomainValid');
        $dns_txt_arr = @dns_get_record('mail.'.$domain->name, DNS_TXT);
        if(!empty($dns_txt_arr)){
            if(is_array($dns_txt_arr)){
                foreach ($dns_txt_arr as $dns_txt) {
                    if($domain->dns == $dns_txt['txt']){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function paginate_urls($arr, $ppc, $page)
    {
        $urls = [];
        for($i=1; $i<=ceil(count($arr)/$ppc); $i++){
            $url = request()->fullUrl();
            if(str_replace('page='.$page,'',$url)!==$url){
                $url = str_replace('page='.$page,'page='. $i,$url);
            }else{
                if(str_replace('?','',$url)==$url){
                    $url = $url . '?page='. $i;
                }else{
                    $url = $url . '&page='. $i;
                }
            }
            $urls[] = $url;
        }
        return $urls;
    }

    public function paginate_arr($arr, $ppc, $page)
    {
        return array_slice($arr, (($page??1)-1)*$ppc, $ppc);
    }

    public function custom_error_messages()
    {
        $_custom_messages = json_decode(ValidationMessage::where('app_id',$this->app_id)->pluck('error_message','rule'),true);
        $_default_messages = json_decode(ValidationMessage::where('app_id',null)->where('rule','LIKE','%[%')->pluck('error_message','rule'),true);
        // $_custom_messages = array_merge(json_decode(ValidationMessage::where('app_id',$this->app_id)->pluck('error_message','rule'),true), $this->getValidationMessages('custom'));
        // $_default_messages = $this->getValidationMessages('arr');
        $arr = ['numeric','file','string','array'];
        $rrr = ['between','max','min','size'];
        for ($i=0; $i < 4; $i++) { 
            for ($j=0; $j < 4; $j++) { 
                if(!empty($_custom_messages[$rrr[$i].'['.$arr[$j].']'])){
                    $_custom_messages[$rrr[$i]] = [];
                    foreach ($arr as $ar) {
                        if(!empty($_custom_messages[$rrr[$i].'['.$ar.']'])){
                            $_custom_messages[$rrr[$i]][$ar] = $_custom_messages[$rrr[$i].'['.$ar.']'];
                            unset($_custom_messages[$rrr[$i].'['.$ar.']']);
                        }else{
                            $_custom_messages[$rrr[$i]][$ar] = $_default_messages[$rrr[$i].'['.$ar.']'];
                        }
                    }
                    break;
                }
            }
        }
        return array_merge(json_decode(ValidationMessage::where('app_id',0)->pluck('error_message','rule'),true), $_custom_messages);
    }

    public function testMail($obj)
    {
        \Log::Info($this->fc.'testMail');
        \Log::Info($obj['secret']);
        return [
            "app_id" => \Auth::user()->active_app_id,
            "secret" => $obj['secret'],
            "to" => $obj['alias'],
            "cc" => "",
            "bcc" => "",
            "from_email" => $obj['email'],
            "from_name" => \Auth::user()->name,
            "subject" => "Test Mail",
            "attach" => "https://via.placeholder.com/150",
            "template" => "common_mail",
            "message" => [
                "title" => "Invoice",
                "embed" => "https://via.placeholder.com/150",
                "Name" => \Auth::user()->name,
                "report" => [
                    [
                        "Sr.No." => "1",
                        "Particular" => "T-Shirt",
                        "Qty" => "2",
                        "Price Per Unit" => "100 usd",
                        "Price" => "200 usd"
                    ],
                    [
                        "Sr.No." => "2",
                        "Particular" => "Jeans Pant",
                        "Qty" => "2",
                        "Price Per Unit" => "200 usd",
                        "Price" => "400 usd"
                    ]
                ],
                "Total Price" => "600 usd",
                "plain_text" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec non molestie arcu. Maecenas auctor elit dapibus, congue ante ut, euismod erat. Donec iaculis magna elit, et lobortis leo faucibus ac. Cras in dolor et tellus mattis rutrum quis in tortor. Morbi consequat non velit quis scelerisque. Phasellus dictum luctus molestie. Etiam quis nulla ac turpis mattis placerat in sed tellus. Etiam vitae libero risus. Mauris scelerisque vulputate orci, ut blandit dui. Aenean dolor velit, pellentesque quis felis sit amet, porttitor tempus lorem. Donec eu posuere lectus. Duis in ipsum orci. Fusce sit amet sagittis orci, a euismod tellus. Pellentesque rhoncus facilisis feugiat.\n\nDuis sit amet rhoncus quam, sodales malesuada ex. Fusce consectetur purus sed dictum aliquam. Quisque ut libero a nibh faucibus mattis. In fermentum ac elit id consequat. Cras nec feugiat quam, vel semper mauris. Sed justo sem, tristique et sodales sit amet, pretium eu justo. Mauris tincidunt nec nibh vitae rutrum. Morbi et tellus eget velit accumsan malesuada. Sed porta magna justo, in efficitur est iaculis a. Aliquam varius ut odio quis aliquet.\n\nDonec porttitor sapien ut dignissim posuere. Nulla elit urna, pharetra at ullamcorper non, aliquet venenatis justo. Nunc a orci tortor. Phasellus a felis quis sem hendrerit cursus at quis eros. Mauris ac odio sed nibh pharetra elementum. Donec erat risus, mollis ac imperdiet non, tincidunt eget mi. Duis eget elit consectetur, maximus purus non, varius metus. Suspendisse pellentesque, sapien at varius pulvinar, massa justo condimentum neque, quis tristique diam purus sit amet eros. Suspendisse non volutpat metus, vitae feugiat urna. Suspendisse id venenatis arcu. Pellentesque pulvinar purus cursus lectus venenatis, non lobortis neque blandit. Cras quis dignissim sapien. Donec hendrerit consequat augue vel pulvinar. Vivamus placerat interdum augue, non consectetur diam.\n\nAenean cursus orci ac dolor pharetra, vel vestibulum orci laoreet. Mauris ante arcu, accumsan id libero vitae, vestibulum varius enim. Cras nunc odio, ullamcorper id mollis fringilla, sodales sit amet leo. Vivamus ullamcorper ante lacus, vel placerat risus tempor ut. Donec nisl lacus, congue sodales neque non, tempus varius augue. Phasellus porttitor ipsum blandit blandit fringilla. Cras rutrum iaculis cursus. Curabitur id tellus non tellus hendrerit imperdiet.\n\nSed iaculis, nisi ut egestas consectetur, turpis justo pretium nunc, eget dictum elit quam vitae arcu. Phasellus vel nibh posuere, accumsan diam at, porta nisl. Aenean fringilla ex quis nibh rhoncus, sit amet maximus lorem posuere. Curabitur id pulvinar nibh, in vestibulum sem. Ut lectus ex, volutpat in viverra quis, luctus vel enim. Quisque augue nibh, fringilla sed sodales et, convallis ac arcu. Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
            ]
        ];
    }

    public function sendMailObject($app, $request)
    {
        \Log::Info($this->fc.'sendMailObject');
        if(filter_var($request['to'], FILTER_VALIDATE_EMAIL)){
            $to = explode(',',str_replace(' ','',$request['to']));
            $cc = explode(',',str_replace(' ','',$request['cc']));
            $bcc = explode(',',str_replace(' ','',$request['bcc']));
            $mail_obj = [
                'subject' => $request['subject']??'No Subject',
                'attach' => $request['attach']??null,
                'attachData' => $request['attachData']??null,
                'template' => $request['template']??null,
                'message' => $request['message']??['title'=>''],
            ];
            if(filter_var($request['from_email'], FILTER_VALIDATE_EMAIL)){
                if($this->isDomainEmailValid($app->user_id, $request['from_email'])){
                    $from = explode('@',$request['from_email']);
                    $mail_obj['from_name'] = $request['from_name']??$from[0];
                    $mail_obj['from_email'] = $request['from_email'];
                }else{
                    return ['status'=>'warning', 'message' => 'domain name could not be verified'];
                }
            }else{
                $mail_obj['from_name'] = $app->name;
                $mail_obj['from_email'] = 'no_reply2@honeyweb.org';
            }
            try{
                if(filter_var($request['cc'], FILTER_VALIDATE_EMAIL) && filter_var($request['bcc'], FILTER_VALIDATE_EMAIL)){
                    Mail::to($to)->cc($cc)->bcc($bcc)->send(new CommonMail($mail_obj));
                }elseif(filter_var($request['cc'], FILTER_VALIDATE_EMAIL)){
                    Mail::to($to)->cc($cc)->send(new CommonMail($mail_obj));
                }elseif(filter_var($request['bcc'], FILTER_VALIDATE_EMAIL)){
                    Mail::to($to)->bcc($bcc)->send(new CommonMail($mail_obj));
                }else{
                    Mail::to($to)->send(new CommonMail($mail_obj));
                }
            }catch(Exception $e){
                return ['status'=>'warning', 'message' => 'mail sending failed'];
            }
            $this->updateUsageReport('emails_sent');
            return ['status'=>'success', 'message' => 'mail successfully sent'];
        }
        return ['status'=>'warning', 'message' => 'mail not sent'];
    }

    public function sendPushMessageObject($request)
    {
        \Log::Info($this->fc.'sendPushMessage');

        $push = $request['message']??[];
        foreach ($push as $key => $value) {
            if(!$value){
                unset($push[$key]);
            }
        }

        $auth = array(
            'VAPID' => array(
                'subject' => env('VAPID_SUBJECT'),
                'publicKey' => env('VAPID_PUBLIC_KEY'), // don't forget that your public key also lives in app.js
                'privateKey' => env('VAPID_PRIVATE_KEY'), // in the real world, this would be in a secret file
            ),
        );
        $defaultOptions = [
            'TTL' => 300, // defaults to 4 weeks
            'urgency' => 'normal', // protocol defaults to "normal"
            'topic' => 'new_event', // not defined by default,
            'batchSize' => 200, // defaults to 1000
        ];
        $webPush = new WebPush($auth);
        $webPush->setDefaultOptions($defaultOptions);

        $query = PushSubscription::query();
        $isFirst = true;
        if(!empty($request['to']) && $request['to'] == '*'){
            $subscriptions = PushSubscription::where('app_id',$request['app_id'])->pluck('subscription');
        }else if(!empty($request['to']) && is_array($request['to'])){
            foreach ($request['to'] as $key => $value) {
                if ($isFirst) {
                    $isFirst = false;
                    $query->where(function ($q)use($request, $key, $value) {
                        if($value == '*'){
                            $q->where('app_id',$request['app_id'])->where('auth_provider', $key);
                        }else if(is_array($value)){
                            $q->where('app_id',$request['app_id'])->where('auth_provider', $key)
                              ->whereIn('user_id', $value);
                        }else{

                        }
                    });
                    continue;
                }
                $query->orWhere(function ($q)use($request, $key, $value) {
                    if($value == '*'){
                        $q->where('app_id',$request['app_id'])->where('auth_provider', $key);
                    }else if(is_array($value)){
                        $q->where('app_id',$request['app_id'])->where('auth_provider', $key)
                          ->whereIn('user_id', $value);
                    }else{

                    }
                });
            }
            $subscriptions = $query->pluck('subscription');
        }else{
            $subscriptions = [];
        }
        
        // $subscriptions = PushSubscription::where('app_id',$request['app_id'])->pluck('subscription');
        $notifications = [];
        foreach ($subscriptions as $s) {
            $webPush->sendNotification(Subscription::create(json_decode($s,true)), json_encode($push) );
        }

        $sent = 0; $failed = 0;
        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                $sent++;
            } else {
                $failed++;
            }
        }
        $this->updateUsageReport('push_sent');
        return ['status'=>'success', 'message' => "Push message was send to {$sent} subscriptions but failed to send to {$failed} subscriptions."];
    }

    public function rechargePlans()
    {
        return [
            'Trial' => ['plan'=>'Trial ( ₹ 50 )','amount'=>1,'validity'=>28],
            'Monthly' => ['plan'=>'Monthly ( ₹ 250 )','amount'=>250,'validity'=>28],
            'Yearly' => ['plan'=>'Yearly ( ₹ 2000 )','amount'=>2000,'validity'=>365],
        ];
    }

    public function updateUsageReport($param)
    {
        \Log::Info($this->fc.'updateUsageReport');
        $user_id = App::findOrFail($this->app_id)->user_id;
        \Log::Info($user_id);
        $report = UsageReport::firstOrCreate([
            'date' => date('Y-m-d'),
            'user_id' => $user_id,
            'app_id' => $this->app_id
        ]);
        \Log::Info($report);
        $report->update([
            $param => $report->{$param} + 1,
        ]);
        $report->save();
        $user = ('App\\User')::findOrFail($user_id);
        $user->update([ 'recharge_balance' => $user->recharge_balance - 0.01 ]);
        $user->save();
    }

    private function setTable($table)
    {
        \Log::Info($this->fc.'setTable');
        $this->table = 'App\\Models\\'.ucwords(rtrim($table,'s'));
    }

    public function gtc($table, $fillables = null, $hiddens = null)
    {
        \Log::Info($this->fc.'gtc');
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
        \Log::Info($this->fc.'remModelClass');
        $myFilePath = base_path() .'/'.$table_class.'.php';
        if(is_writable($myFilePath)){
            unlink($myFilePath);
        }
    }

    public function tClass($table)
    {
        \Log::Info($this->fc.'tClass');
        return ucwords(rtrim('app'.$this->app_id.'_'.$table,'s'));
    }

    private function table($table)
    {
        \Log::Info($this->fc.'table');
        return 'app'.$this->app_id.'_'.$table;
    }

}