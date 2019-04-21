<?php

namespace App\Http\Controllers;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\App;
use App\Session;
use App\Chat;
use App\Traits\SqlQueries;
use App\Traits\CreatesModelClass;
use App\Traits\StoresSessionTokens;
use App\Traits\UtilityFunctions;
use App\Traits\SendsChatMessages;
use App\Http\Controllers\Controller;

class MyChatController extends Controller implements MessageComponentInterface {
    
    use SqlQueries;
    use CreatesModelClass;
    use StoresSessionTokens;
    use UtilityFunctions;
    use SendsChatMessages;

    protected $clients;
    public $con;
    public $app_id;
    public $app;
    public $aid;
    public $fid;
    public $fap;
    public $fname;
    public $tarray;
    public $can_chat_with;
    public $chat_admins;
    public $group_chat;
    public $response;
    public $fc;

    public function __construct() {
        // $this->clients = new \SplObjectStorage;
        $this->clients = array();
        $this->fc = "MyChatController::";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        // $this->clients->attach($conn);
        $this->clients[$conn->resourceId] = $conn;
        $response = ['command'=>'crid', 'data' => $conn->resourceId];
        $this->clients[$conn->resourceId]->send(json_encode($response));
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $colors=array("red", "maroon", "yellow", "olive", "lime", "green", "aqua", "teal", "blue", "navy", "fuchsia", "purple", "grey", "silver", "black", "white");
        $msgObj = json_decode($msg, true);

        $this->app_id = $this->checkSessionToken($msgObj['_token']);
        if(!is_numeric($this->app_id)){$from->send(json_encode($this->app_id->original)); return $this->app_id;}

        $this->app = App::findOrFail($this->app_id);

        if(!$this->checkCanChatWith($msgObj['command']??"", $msgObj['tid']??"", $msgObj['tap']??"")){
            return 0;
        }
        
        $this->con = $this->app->db_connection;

        \Log::Info($this->fc.$msgObj['command']);

        if($msgObj['command'] == 'save_crid'){
            $response = ['command'=>'save_crid', 'data' => $this->saveChatResourceId($msgObj)];
        }elseif($msgObj['command'] == 'customer_care_app_config'){
            $response = ['command'=>'customer_care_app_config', 'data' => $this->ccAppConfigGet($msgObj)];
        }elseif($msgObj['command'] == 'waiting_chats'){
            $response = ['command'=>'waiting_chats', 'data' => $this->getWaitingChats()];
        }elseif($msgObj['command'] == 'delete_chat_request'){
            $response = ['command'=>'delete_chat_request', 'data' => $this->deleteChatRequest()];
        }elseif($msgObj['command'] == 'pick_chat'){
            $response = $this->pickWaitingChat($msgObj);
            $this->tarray = $response['waiting_customers'];
            unset($response['waiting_customers']);
            $tresponse = $response;
            unset($tresponse['data']);
        }elseif($msgObj['command'] == 'start_chat'){
            $response = $this->saveNullMessage($msgObj);
            $this->tarray = $response['waiting_customers'];
            unset($response['waiting_customers']);
            $tresponse = $response;
        }elseif($msgObj['command'] == 'chat_message'){
            $response = ['command'=>'chat_message', 'data' => $this->saveChatMessage($msgObj)];
            $tresponse = $response;
        }elseif($msgObj['command'] == 'get_messages'){
            $response = $this->getMessages($msgObj);
        }elseif($msgObj['command'] == 'get_chats'){
            $response = ['command'=>'get_chats', 'data' => $this->getMyChats($msgObj)];
        }

        if($msgObj['command'] != 'start_chat'){
            $frecord = Session::where('_token', $msgObj['_token'])->first();
            $fcrids = Session::where([
                'auth_provider' => $frecord->auth_provider, 
                'user_id'=>$frecord->user_id,
                ['chat_resource_id', '<>', null], 
            ])->pluck('chat_resource_id');

            foreach (json_decode($fcrids,true) as $crid) {
                $this->clients[$crid]->send(json_encode($response));
            }
        }

        if($msgObj['command'] == 'save_crid'){
            $this->changeOnlineStatus($frecord, $from);
        }

        if( !in_array($msgObj['command'], ['chat_message','pick_chat','start_chat']) ){
            return 1;
        }

        $tcrids = $this->getTcrids($this->tarray??[['tap' => $msgObj['tap'], 'tid' => $msgObj['tid']]]);

        foreach ($tcrids as $crid) {
            $this->clients[$crid]->send(json_encode($tresponse));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // $this->clients->detach($conn);
        $frecord = Session::where('chat_resource_id', $conn->resourceId)->first();
        if(!empty($frecord)){
            $frecord->update(['chat_resource_id' => null]);
            $frecord->save();
            $this->changeOnlineStatus($frecord, $conn);
        }
        unset($this->clients[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function changeOnlineStatus($frecord, $conn)
    {
        \Log::Info($this->fc.'changeOnlineStatus');
        $fcrids = Session::where([
            'auth_provider' => $frecord->auth_provider, 
            'user_id'=>$frecord->user_id,
            ['chat_resource_id', '<>', null], 
        ])->pluck('chat_resource_id');

        if(count($fcrids) == 0){
            $status = 'offline';
        }else{
            $status = 'online';
        }

        $this->app_id = $this->checkSessionToken($frecord->_token);
        if(!is_numeric($this->app_id)){$conn->send(json_encode($this->app_id->original)); return $this->app_id;}
        $this->app = App::findOrFail($this->app_id);

        $chats = $this->getMyChats(['term'=>'']);
        if(count($chats) == 0){
            return 0;
        }
        $tcrids = $this->getTcrids([['tap'=>$chats[0]['tap'], 'tid'=>$chats[0]['tid']]]);

        foreach ($tcrids as $crid) {
            $this->clients[$crid]->send(json_encode(['command' => 'online_status', 'data' => [
                'tid' => $frecord->user_id,
                'tap' => $frecord->auth_provider,
                'online_status' => $status,
            ]]));
        }
    }

    private function getTcrids($tarray)
    {
        \Log::Info($this->fc.'getTcrids');
        $tcrids = [];
        foreach($tarray as $t){
            if($t['tap'] == $this->group_chat){
                $group_table = $this->gtc($t['tap']);
                $group_members = json_decode($group_table::findOrFail($t['tid'])->members, true)??[];
                foreach ($group_members as $key => $member) {
                    $tcrids = array_merge($tcrids, json_decode(Session::where([
                        'auth_provider'=>$member['ap'], 
                        'user_id'=>$member['id'],
                        ['chat_resource_id', '<>', null],
                    ])->pluck('chat_resource_id'),true));
                }
            }else{
                $tcrids = array_merge($tcrids, json_decode(Session::where([
                    'auth_provider'=>$t['tap'], 
                    'user_id'=>$t['tid'],
                    ['chat_resource_id', '<>', null],
                ])->pluck('chat_resource_id'),true));
            }
        }
        return $tcrids;
    }

    // public function checkCanChatWith($command, $tid, $tap)
    // {
    //     $can_chat_with = json_decode($this->app->can_chat_with,true);
    //     $this->chat_admins = explode(', ', $can_chat_with['chat_admins']);
    //     $this->group_chat = $can_chat_with['group_chat'];
    //     if($command == 'get_chats'){
    //         return $can_chat_with[$this->fap] == 1;
    //     }
    //     if($command == 'get_messages' || $command == 'chat_message'){
    //         if($can_chat_with['guest'] == 1 && $this->fap == 'guest'){
    //             return 1;
    //         }elseif(empty($tap) || empty($can_chat_with[$this->fap.':'.$tap])){
    //             return 0;
    //         }elseif($tap == $this->group_chat){
    //             return $can_chat_with[$this->fap.':'.$tap] == 1 || $can_chat_with[$this->fap.':'.$tap] == '*';
    //         }elseif($can_chat_with[$this->fap.':'.$tap] == 1){
    //             if($this->app_id == 1  && in_array($this->fap, ['users']) ){
    //                 $my_friends = json_decode(('App\\User')::findOrFail($this->fid)->{'chat_'.$tap},true)??[];
    //             }else{
    //                 $this->con = $this->app_id?$this->app->db_connection:'apps_db';
    //                 $fromTable = $this->gtc($this->fap);
    //                 $my_friends = json_decode($fromTable::findOrFail($this->fid)->{'chat_'.$tap},true)??[];
    //             }
    //             return in_array($tid, $my_friends);
    //         }
    //         return $can_chat_with[$this->fap.':'.$tap] == '*';
    //     }
    // }

    // public function saveChatResourceId($request)
    // {
    //     Session::where('_token', $request['_token'])->update(['chat_resource_id'=>$request['chat_resource_id']]);
    //     return "saved";
    // }

    // public function customer_care_app_config($request)
    // {
    //     return App::findOrFail($request['app_id'])->ccac;
    // }

    // public function getWaitingChats()
    // {
    //     return Chat::where(['app_id' => $this->app_id])->where('status', 'waiting')->get();
    // }

    // public function deleteChatRequest()
    // {
    //     $record = Chat::where([
    //         'app_id' => $this->aid,
    //         'fid'=>$this->fid, 
    //         'fap'=>$this->fap,
    //     ])->first();
    //     if(!empty($record)){
    //         $record->update(['status' => 'closed']);
    //         $record->save();
    //     }
    //     return 'closed';
    // }

    // public function pickWaitingChat($request)
    // {
    //     $message = Chat::findOrFail($request['id']);
    //     if($message->app_id != $this->aid){
    //         return 0;
    //     }
    //     if($message->status != 'waiting'){
    //         return 0;
    //     }
    //     $message->update([
    //         'tid' => $this->fid, 
    //         'tap' => $this->fap, 
    //         'tname' => $this->fname,
    //         'status' => 'chatting',
    //     ]);
    //     $message->save();
    //     Chat::firstOrCreate([
    //         'app_id' => $this->aid,
    //         'cid'=>$message->cid,
    //         'fid'=>$this->fid, 
    //         'fap'=>$this->fap, 
    //         'fname'=>$this->fname, 
    //         'tid' => $message->fid, 
    //         'tap' => $message->fap, 
    //         'tname' => $message->fname,
    //     ]);
    //     return 'success';
    // }

    // public function saveChatMessage($request)
    // {
    //     if(!$request['message']){
    //         return ['message' => 'message empty'];
    //     }
    //     $cid = $this->getCID($request);
    //     if($cid){
    //         $id= Chat::create([
    //             'app_id' => $this->aid,
    //             'cid'=>$cid,
    //             'message'=>$request['message'],
    //             'fid'=>$this->fid, 
    //             'fap'=>$this->fap, 
    //             'fname'=>$this->fname, 
    //             'tid' => $request['tid'], 
    //             'tap' => $request['tap'], 
    //             'tname'=>$request['tname']??null, 
    //             'style'=>$request['style']??null, 
    //             'status'=>'saved',
    //         ])->id;
    //         return Chat::findOrFail($id);
    //     }else{
    //         $cid_new = Chat::max('cid')+1;
    //         Chat::create([
    //             'app_id' => $this->aid,
    //             'cid'=>$cid_new,
    //             'fid'=>$this->fid, 
    //             'fap'=>$this->fap, 
    //             'tid' => $request['tid'], 
    //             'tap' => $request['tap'],
    //         ]);
    //         Chat::create([
    //             'app_id' => $this->aid,
    //             'cid'=>$cid_new,
    //             'fid'=>$request['tid'], 
    //             'fap'=>$request['tap'], 
    //             'tid' => $this->fid, 
    //             'tap' => $this->fap,
    //         ]);
    //         return 1;
    //     }
    // }

    // public function getCID($request)
    // {
    //     $cid_record = Chat::where([
    //         'app_id' => $this->aid,
    //         'fid' => $this->fid, 
    //         'fap' => $this->fap, 
    //         'tid' => $request['tid'], 
    //         'tap' => $request['tap'], 
    //         'message' => null,
    //     ])->first();
    //     return empty($cid_record)?0:$cid_record->cid;
    // }

    // public function getMessages($request)
    // {
    //     $cid = $this->getCID($request);
    //     $mcs = $request['mcs']??10;
    //     $stc = $request['stc']??0;
    //     $bl = $request['bl']??$mcs;
    //     $mc = Chat::where([
    //         'app_id' => $this->aid,
    //         'cid' => $cid, 
    //         ['message', '!=', null],
    //     ])->count();
    //     // $offset = min($mcs+$mcs*$stc-$bl, $mc-$bl);
    //     $offset = 0;
    //     $messages = Chat::select(['id','message','fid','fap','fname','tid','tap','tname','style','created_at'])->where([
    //         'app_id' => $this->aid,
    //         'cid' => $cid, 
    //         ['message', '!=', null],
    //     ])->latest()->offset($offset)->limit($bl)->get();
    //     return ['type'=>'get_messages', 'data' => $messages, 'count' => $mc, 'eom' => ($mc<=$bl)?"eom":""];
    //     $mcount = count($messages);
    //     // $messages = array_slice(json_decode($messages,true),count($messages)-$mcs,count($messages));
    //     $ar = [];
    //     $si = 1;$ph = 0;
    //     for($i=$mcount-1; $i>=0; $i--){
    //         $date = date('d-M-Y', strtotime($messages[$i]->created_at));
    //         $time = date('H:i', strtotime($messages[$i]->created_at));
    //         $ar[$date] = $ar[$date]??[];
    //         $ar[$date][$si] = $ar[$date][$si]??[];
    //         if($messages[$i]->fid == $this->fid && $messages[$i]->fap == $this->fap){
    //             $t = $time;
    //             if($ph == 1){$si++;}
    //             $ph = 0;
    //         }else{
    //             $t = $messages[$i]->fname.' '.$time;
    //             if($ph == 0){$si++;}
    //             $ph = 1;
    //         }
    //         $ar[$date][$si][$t] = $ar[$date][$si][$t]??[];
    //         $ar[$date][$si][$t][] = ['id'=>$messages[$i]->id,'msg'=>$messages[$i]->message];
    //     }
    //     if(($mc - $mcount) == $offset){
    //         $ar['eom'] = 'eom';
    //     }
        
    //     return $ar;
    // }

    // public function getMyChats($request)
    // {
    //     $myContacts = Chat::select(['tid','tap','tname','message','updated_at'])->where([
    //         'app_id' => $this->aid,
    //         'fid' => $this->fid, 
    //         'fap' => $this->fap, 
    //         ['tid', '!=', null],
    //         ['tap', '!=', null],
    //         'message' => null,
    //         ['fname', 'LIKE', '%'.$request['term'].'%']
    //     ])->latest()->get();
    //     $myContacts = json_decode($myContacts, true);
    //     for($i=0; $i<count($myContacts); $i++){
    //         $m = Chat::where([
    //             'app_id' => $this->aid,
    //             'fid' => $myContacts[$i]['tid'], 
    //             'fap' => $myContacts[$i]['tap'], 
    //             ['message','!=', null],
    //         ])->latest()->first();
    //         $n = Chat::where([
    //             'app_id' => $this->aid,
    //             'tid' => $myContacts[$i]['tid'], 
    //             'tap' => $myContacts[$i]['tap'], 
    //             ['message','!=', null],
    //         ])->latest()->first();
    //         if(!empty($m) && !empty($n)){
    //             $mobj = $m['id']>$n['id']?$m:$n;
    //         }elseif(!empty($m)){
    //             $mobj = $m;
    //         }elseif(!empty($n)){
    //             $mobj = $n;
    //         }else{

    //         }
    //         if(!empty($mobj)){
    //             $myContacts[$i]['message'] = $mobj->message;
    //             $myContacts[$i]['updated_at'] = date('d/M/Y',strtotime($mobj->updated_at));
    //         }
    //     }
    //     return $myContacts;
    // }

}