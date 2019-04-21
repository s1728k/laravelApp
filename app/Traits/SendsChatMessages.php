<?php

namespace App\Traits;

use App\App;
use App\Guest;
use App\User;
use App\Chat;
use App\Ccac;
use App\Session;
use Illuminate\Http\Request;

trait SendsChatMessages
{
    public function chatMessagesView(Request $request)
    {
        $query = Chat::where(['app_id'=>$this->app_id, ['message','!=',null]]);
        foreach (['message','fid','fap','fname','tid','tap','tname','style','status'] as $key => $value) {
            $query = $request->{$value}?$query->where($value,'LIKE','%'.$request->{$value}.'%'):$query;
        }
        $query = $this->dateFilter($request, $query);
        return view($this->theme.'.chat_messages')->with([
            'messages' => $query->latest()->paginate(10),
            'page'=>$request->page??1,
        ]);
    }

    public function chatRequestsView(Request $request)
    {
        $query = Chat::whereIn('status', ['waiting', 'chatting', 'closed'])->where([
            'app_id'=>$this->app_id, 'message' => null]);
        foreach (['fid','fap','fname','tid','tap','tname','status'] as $key => $value) {
            $query = $request->{$value}?$query->where($value,'LIKE','%'.$request->{$value}.'%'):$query;
        }
        $query = $this->dateFilter($request, $query);
        return view($this->theme.'.chat_requests')->with([
            'requests' => $query->paginate(10),
            'page'=>$request->page??1,
        ]);
    }

    public function updateChatMessage(Request $request)
    {
        \Log::Info($request->all());
        if($request->cmd == 'status_only'){
            Chat::findOrFail($request->id)->update(['status' => $request->status]);
            return ['message', 'status was successfully updated'];
        }
        if(!$request->message){
            return ['message' => 'message empty'];
        }
        $request->validate(['message' => 'required|string|max:255']);
        if($request->style){$request->validate(['style' => 'string|max:32']);}
        if($request->status){$request->validate(['status' => 'string|max:20']);}
        Chat::findOrFail($request->id)->update($request->all());
        return ['message', 'message was successfully updated'];
    }

    public function deleteChatMessage(Request $request)
    {
        $record = Chat::findOrFail($request->id);
        if($record->app_id == $this->app_id){
            Chat::destroy($request->id);
            return ['message', 'message was successfully deleted'];
        }
        return ['message', 'message was not deleted'];
    }

    public function canChatWithView(Request $request)
    {
        $ap = json_decode(App::findOrFail(\Auth::user()->active_app_id)->auth_providers,true)??[];
        $ap = array_slice($ap,1);
        $ccw = json_decode(App::findOrFail(\Auth::user()->active_app_id)->can_chat_with,true)??[];
        return view($this->theme.'.can_chat_with')->with(['ap' => $ap,'ccw'=>$ccw,
            'ca'=>$ccw['chat_admins']?explode(', ', $ccw['chat_admins']):[] ]);
    }

    public function canChatWith(Request $request)
    {
        App::findOrFail(\Auth::user()->active_app_id)->update(['can_chat_with' => json_encode($request->can_chat_with)]);
        return ['message' => 'updated can_chat_with'];
    }

    public function ccAppConfigView(Request $request)
    {
        return view('cb.chat_cc_app_config')->with([
            'ccac' => json_decode(App::findOrFail($this->app_id)->ccac,true)??[],
        ]);
    }

    public function ccAppConfig(Request $request)
    {
        $request->validate([
            'signup' => 'required|numeric|non_fraction|integerCustomUnsigned',
            'login' => 'required|numeric|non_fraction|integerCustomUnsigned',
            'sevc' => 'required|numeric|non_fraction|integerCustomUnsigned',
            've' => 'required|numeric|non_fraction|integerCustomUnsigned',
        ]);
        $app = App::findOrFail($this->app_id);
        $ap = [];
        $can_chat_with = json_decode($app->can_chat_with);
        $ar = [
            'signup' => $request->signup,
            'login' => $request->login,
            'sevc' => $request->sevc,
            've' => $request->ve,
            'ap'=> $can_chat_with->chat_admins,
            'secret'=>$app->secret,
        ];
        $app->update(['ccac' => json_encode($ar)]);
        $app->save();
        return ['message' => 'success'];
    }

    public function chatPage(Request $request)
    {
        return view('cb.chatpage');
    }

    public function apiChatRouteGuard(Request $request)
    {
        if($request->_token){
            $this->app_id = $this->checkSessionToken($request->_token);
            if(!is_numeric($this->app_id)){ return $this->app_id; }
            $this->app = App::findOrFail($this->app_id);
            if(!$this->checkCanChatWith($request->command, $request->tid, $request->tap)){
                return response()->json(['message' => 'un-authorized'], 401);
            }
        }else{
            if ($request->command == 'request_token') {
                $this->app = App::findOrFail($request->app_id);
                if(!$this->checkCanChatWith($request->command, $request->tid, $request->tap)){
                    return response()->json(['message' => 'un-authorized'], 401);
                }
            }elseif($request->command == 'customer_care_app_config'){

            }else{
                return response()->json(['message' => 'un-authorized'], 401);
            }
        }
        return $this->chat_junction($request->all());
    }

    public function checkCanChatWith($command, $tid, $tap)
    {
        $can_chat_with = json_decode($this->app->can_chat_with,true);
        // $this->chat_admins = explode(', ', $can_chat_with['chat_admins']);
        $this->group_chat = $can_chat_with['group_chat'];

        if ($command == 'customer_care_app_config')
            return 1;
        if ($command == 'request_token')
            return $can_chat_with['guest'] == 1 && $this->fap == 'guest';
        if (in_array($command, ['save_crid', 'get_chats', 'start_chat', 'message_status']))
            return $can_chat_with[$this->fap] == 1;
        if (in_array($command, ['get_messages','chat_message'])) {
            if($can_chat_with['guest'] == 1 && $this->fap == 'guest'){
                return 1;
            }elseif(empty($tap) || empty($can_chat_with[$this->fap.':'.$tap])){
                return 0;
            }elseif($tap == $this->group_chat){
                return $can_chat_with[$this->fap.':'.$tap] == 1 || $can_chat_with[$this->fap.':'.$tap] == '*';
            }elseif($can_chat_with[$this->fap.':'.$tap] == 1){
                if($this->app_id == 1  && in_array($this->fap, ['users']) ){
                    $my_friends = json_decode(('App\\User')::findOrFail($this->fid)->{'chat_'.$tap},true)??[];
                }else{
                    $this->con = $this->app_id?$this->app->db_connection:'apps_db';
                    $fromTable = $this->gtc($this->fap);
                    $my_friends = json_decode($fromTable::findOrFail($this->fid)->{'chat_'.$tap},true)??[];
                }
                return in_array($tid, $my_friends);
            }
            return $can_chat_with[$this->fap.':'.$tap] == '*';
        }
        if (in_array($command, ['waiting_chats','delete_chat_request','pick_chat']))
            return in_array($this->fap, explode(', ', $can_chat_with['chat_admins']));
    }

    public function chat_junction($request)
    {
        switch ($request['command']) {
            case 'customer_care_app_config':
                return $this->ccAppConfigGet($request);
            case 'request_token':
                return ['command'=>'request_token', 'data'=>$this->requestToken($request)];
            case 'save_resource_id':
                return ['command'=>'save_resource_id', 'data'=>$this->saveChatResourceId($request)];
            case 'get_chats':
                return ['command'=>'get_chats', 'data'=>$this->getMyChats($request)];
            case 'get_messages':
                return ['command'=>'get_messages', 'data'=>$this->getMessages($request)];
            case 'start_chat':
                return ['command'=>'start_chat', 'data'=>$this->saveNullMessage($request)];
            case 'waiting_chats':
                return ['command'=>'waiting_chats', 'data'=>$this->getWaitingChats($request)];
            case 'delete_chat_request':
                return ['command'=>'delete_chat_request', 'data'=>$this->deleteChatRequest($request)];
            case 'pick_chat':
                return ['command'=>'pick_chat', 'data'=>$this->pickWaitingChat($request)];
            case 'save_message':
                return ['command'=>'save_message', 'data'=>$this->saveChatMessage($request)];
            case 'message_status':
                return ['command'=>'save_message', 'data'=>$this->updateMessageStatus($request)];
            default:
                return 0;
        }

    }

    public function ccAppConfigGet($request)
    {
        return json_decode(App::findOrFail($request['app_id'])->ccac,true);
    }

    public function requestToken(Request $request)
    {
        \Log::Info($this->fc.'requestToken');
        if($this->fap == 'guest'){
            \Log::Info('from guest');
            Chat::destroy(Chat::where([
                'app_id' => $this->aid,
                'fap'=>$this->fap, 
                'ip_address'=>request()->ip(),
            ])->pluck('id'));

            $guest = Guest::where('ip_address', request()->ip())->first();
            if($guest){
                $guest->update(['name' => $request->fname??'Guest']);
                $guest->save();
            }else{
                $guest = Guest::create(['ip_address' => request()->ip(), 'name' => $request->fname??'Guest']);
            }

            $cid_new = Chat::max('cid')+1;
            Chat::create([
                'app_id' => $this->aid,
                'cid'=>$cid_new,
                'fid'=>$guest->id, 
                'fap'=>$this->fap, 
                'fname'=>$guest->name, 
                'status'=>'waiting',
            ]);

            return $this->createSessionToken($request, $this->aid, $this->fap, $guest->id,  $guest->name);
        }elseif($this->fromWeb){
            \Log::Info('from Web');
            $new_token = $this->createSessionToken($request, $this->aid, $this->fap, $this->fid, $this->fname);
            return $this->saveNullMessage($request)?$new_token:'';
        }
        return '';
    }

    public function saveChatResourceId($request)
    {
        \Log::Info('chat_resource_id: '.$request['chat_resource_id']);
        Session::where('_token', $request['_token'])->update(['chat_resource_id'=>$request['chat_resource_id']]);
        return ['message' => 'chat resource id saved'];
    }

    public function getMyChats($request)
    {
        \Log::Info($this->fc.'getMyChats');
        $myContacts = Chat::select(['tid','tap','tname','updated_at'])->where([
            'app_id' => $this->aid,
            'fid' => $this->fid, 
            'fap' => $this->fap, 
            ['tid', '!=', null],
            ['tap', '!=', null],
            'message' => null,
            ['fname', 'LIKE', '%'.$request['term']??''.'%']
        ])->latest()->get();
        $myContacts = json_decode($myContacts, true);
        for($i=0; $i<count($myContacts); $i++){
            $m = Chat::where([
                'app_id' => $this->aid,
                'fid' => $myContacts[$i]['tid'], 
                'fap' => $myContacts[$i]['tap'], 
                ['message','!=', null],
            ])->latest()->first();
            $n = Chat::where([
                'app_id' => $this->aid,
                'tid' => $myContacts[$i]['tid'], 
                'tap' => $myContacts[$i]['tap'], 
                ['message','!=', null],
            ])->latest()->first();
            if(!empty($m) && !empty($n)){
                $mobj = $m['id']>$n['id']?$m:$n;
            }elseif(!empty($m)){
                $mobj = $m;
            }elseif(!empty($n)){
                $mobj = $n;
            }else{

            }
            if(!empty($mobj)){
                $myContacts[$i]['message'] = $mobj->message;
            }
            $tr = Session::where([
                'auth_provider'=>$myContacts[$i]['tap'],
                'user_id'=>$myContacts[$i]['tid'],
                ['chat_resource_id', '<>', null],
            ])->first();
            if(!empty($tr)){
                $myContacts[$i]['online_status'] = 'online';
            }else{
                $myContacts[$i]['online_status'] = 'offline';
            }
            $myContacts[$i]['unread_messages'] = Chat::where([
                'app_id' => $this->aid,
                'fid' => $myContacts[$i]['tid'], 
                'fap' => $myContacts[$i]['tap'], 
                'tid' => $this->fid, 
                'tap' => $this->fap, 
                ['message','!=', null],
                'status' => 'unread',
            ])->count();
            $myContacts[$i]['updated_at'] = date('d/M/Y',strtotime($myContacts[$i]['updated_at']));
        }
        return $myContacts;
    }

	public function getMessages($request)
    {
        $cid = $this->getCID($request);
        $nom = $request['nom'];
        $mc = Chat::where([
            'app_id' => $this->aid,
            'cid' => $cid, 
            ['message', '!=', null],
        ])->count();

        $messages = Chat::select(['id','message','fid','fap','fname','tid','tap','tname','style','created_at'])->where([
            'app_id' => $this->aid,
            'cid' => $cid, 
            ['message', '!=', null],
        ])->latest()->offset($request['offset']??0)->limit($request['nom']??10)->get();

        return ['command'=>'get_messages', 'data' => $messages, 'count' => $mc, 'eom' => ($mc<=$nom&&$nom>15)?"eom":""];
    }

    public function saveNullMessage($request)
    {
        \Log::Info($this->fc.'saveNullMessage');
        $record = Chat::where([
            'app_id' => $this->aid,
            'fid'=>$this->fid, 
            'fap'=>$this->fap, 
            'fname'=>$this->fname,
        ])->first();
        if(empty($record)){
            $cid_new = Chat::max('cid')+1;
            Chat::create([
                'app_id' => $this->aid,
                'cid'=>$cid_new,
                'fid'=>$this->fid, 
                'fap'=>$this->fap, 
                'fname'=>$this->fname, 
                'status'=>'waiting',
            ]);
        }else{
            $record->update(['status' => 'waiting']);
            $record->save();
        }
        $tarray = [];
        foreach(Chat::where(['app_id'=>$this->aid,'status'=>'waiting'])->get() as $t){
            $tarray[] = ['tap'=>$t->fap,'tid'=>$t->fid];
        }
        return [
            'command'=>'start_chat', 
            'count' => count($tarray), 
            'waiting_customers' =>$tarray,
        ];
        return Chat::where(['app_id' => $this->aid])->where('status', 'waiting')->count();
    }

    public function getWaitingChats()
    {
        return Chat::where(['app_id' => $this->aid])->where('status', 'waiting')->get();
    }

    public function deleteChatRequest($request)
    {
        $record = Chat::where([
            'app_id' => $this->aid,
            'fid'=>$this->fid, 
            'fap'=>$this->fap,
        ])->first();
        if(!empty($record)){
            $record->update(['status' => 'closed']);
            $record->save();
        }
        return ['message' => 'closed'];
    }

    public function pickWaitingChat($request)
    {
        \Log::Info($this->fc.'pickWaitingChat');
        $message = Chat::findOrFail($request['id']);
        if($message->app_id != $this->aid){
            return response()->json(['message' => 'un-authorized'], 401);
        }
        if($message->status != 'waiting'){
            return response()->json(['message' => 'you can only pick waiting chat requests'], 401);
        }
        $message->update([
            'tid' => $this->fid, 
            'tap' => $this->fap, 
            'tname' => $this->fname,
            'status' => 'chatting',
        ]);
        $message->save();
        Chat::firstOrCreate([
            'app_id' => $this->aid,
            'cid'=>$message->cid,
            'fid'=>$this->fid, 
            'fap'=>$this->fap, 
            'fname'=>$this->fname, 
            'tid' => $message->fid, 
            'tap' => $message->fap, 
            'tname' => $message->fname,
        ]);
        $pickedChat = Chat::select(['tid','tap','tname','updated_at'])->where([
            'app_id' => $this->aid,
            'fid' => $this->fid, 
            'fap' => $this->fap, 
            ['tid', '!=', null],
            ['tap', '!=', null],
            'message' => null,
        ])->latest()->first();
        $pickedChat = json_decode($pickedChat, true);
        $pickedChat['updated_at'] = date('d/M/Y',strtotime($pickedChat['updated_at']));
        // return $pickedChat;
        $tarray = [['tap'=>$message->fap,'tid'=>$message->fid]];
        foreach(Chat::where(['app_id'=>$this->aid,'status'=>'waiting'])->get() as $t){
            $tarray[] = ['tap'=>$t->fap,'tid'=>$t->fid];
        }
        return [
            'command'=>'pick_chat', 
            'data' => $pickedChat, 
            'count' => count($tarray)-1, 
            'waiting_customers' =>$tarray,
        ];
    }

    public function saveChatMessage($request)
    {
        \Log::Info('saveChatMessage');
        if(!$request['message']){
            return ['message' => 'message empty'];
        }
        $cid = $this->getCID($request);
        if($cid){
            $id = Chat::create([
                'app_id' => $this->aid,
                'cid'=>$cid,
                'message'=>$request['message'],
                'fid'=>$this->fid, 
                'fap'=>$this->fap, 
                'fname'=>$this->fname, 
                'tid' => $request['tid'], 
                'tap' => $request['tap'], 
                'tname'=>$request['tname']??null, 
                'style'=>$request['style']??null, 
                'status'=>'saved',
            ])->id;
            return Chat::findOrFail($id);
        }else{
            $cid_new = Chat::max('cid')+1;
            Chat::create([
                'app_id' => $this->aid,
                'cid'=>$cid_new,
                'fid'=>$this->fid, 
                'fap'=>$this->fap, 
                'tid' => $request['tid'], 
                'tap' => $request['tap'],
            ]);
            Chat::create([
                'app_id' => $this->aid,
                'cid'=>$cid_new,
                'fid'=>$request['tid'], 
                'fap'=>$request['tap'], 
                'tid' => $this->fid, 
                'tap' => $this->fap,
            ]);
        }
        
        return 1;
    }

    public function updateMessageStatus($request)
    {
        $message = Chat::findOrFail($request['id']);
        if($message->app_id == $this->aid && $message->fap == $this->fap && $message->fid == $this->fid){
            $message->update(['status' => 'sent']);
            $message->save();
        }elseif($message->app_id == $this->aid && $message->tap == $this->fap && $message->tid == $this->fid){
            $message->update(['status' => 'received']);
            $message->save();
        }
        return ['message' => 'success'];
    }

    public function getCID($request)
    {
        $cid_record = Chat::where([
            'app_id' => $this->aid,
            'fid' => $this->fid, 
            'fap' => $this->fap, 
            'tid' => $request['tid'], 
            'tap' => $request['tap'], 
            'message' => null,
        ])->first();
        return empty($cid_record)?0:$cid_record->cid;
    }

}
