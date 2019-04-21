<?php
namespace App\Http\Controllers;

use App\Chat;
use App\User;
use Illuminate\Http\Request;

class ChatController 
{

    public function __construct()
    {
        \Log::Info('fsdf');
    }

    public function saveChatResourceId(Request $request)
    {
        \Log::Info('chat_resource_id: '.$request->chat_resource_id);
        if($request->app_id == 1 && $request->fap == ''){
            \DB::update('update users set chat_resource_id = '.$request->chat_resource_id.' where id = '.$request->fid );
        }else{
            $this->app_id = $request->app_id;
            $table = $this->gtc($request->fap, ['chat_resource_id']);
            $table::findOrFail($request->fid)->update([
                'chat_resource_id' => $request->chat_resource_id,
            ]);
        }
        return ['message' => 'chat resource id saved'];
    }

    public function saveChatResourceId_(Request $request)
    {
        \Log::Info('chat_resource_id: '.$request->chat_resource_id);
        if($request->app_id == 1){
            User::findOrFail($request->id)->update([
                'chat_resource_id' => $request->chat_resource_id,
            ]);
        }else{
            $this->app_id = $request->app_id;
            $table = $this->gtc($request->fap, ['chat_resource_id']);
            $table::findOrFail($request->fid)->update([
                'chat_resource_id' => $request->chat_resource_id,
            ]);
        }
        return ['message' => 'chat resource id saved'];
    }

}