<?php

namespace App\Traits;

use App\App;
use App\PushSubscription;
use App\PushMessage;
use App\Notifications\Welcome;
use Illuminate\Http\Request;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

trait PushesNotifications
{

	public function saveSubscription(Request $request)
	{
		\Log::Info($this->fc.'saveSubscription');
		$table = "App\\PushSubscription";
		$exists = $table::where('subscription', json_encode($request->subscription))->first();
		if(!$exists){
			$table::create(['app_id' => 1, 'auth_provider'=>'users', 'user_id'=>\Auth::user()->id, 'subscription' => json_encode($request->subscription)]);
			return ['message' => 'successfully saved'];
		}else{
			return ['message' => 'already saved'];
		}
	}

	public function pushMessageList(Request $request)
	{
		\Log::Info($this->fc.'pushMessageList');
		$push_messages = PushMessage::where('app_id', $this->app_id)->paginate(10);
		return view('cb.p.messages')->with(['push_messages' => $push_messages, 'page'=>$page??1]);
	}

	public function createMessageView(Request $request)
	{
		\Log::Info($this->fc.'createMessageView');
		$app = App::findOrFail($this->app_id);
		$push = [
			"app_id" => $app->id,
			"secret" => $app->secret,
			"to" => ['users' => [1,2]],
			"message" => [
				"title" => $app->name,
				"body" => "Hi, I am push message from ".$app->name.".",
				"icon" => "https://via.placeholder.com/20",
				"image" => "https://via.placeholder.com/100",
				"badge" => "https://via.placeholder.com/10",
				"sound" => "https://soundcloud.com/secret-service-862007284/old-town-road",
				"vibrate" => [12, 154, 56, 56, 565, 464, 654, 5646, 54645],
				"dir" => "",
				"tag" => "my_tag",
				"data" => "",
				"requireInteraction" => "1",
				"renotify" => "1",
				"silent" => "0",
				"actions" => [
					['action'=>'/app/app-list', 'title'=>'Visit Site', 'icon'=>'']
				],
				"timestamp" => "",
				"lang" => "",
			],
		];
		return view($this->theme.'.p.push_json')->with(['push' => json_encode($push)]);
	}

	public function createMessage(Request $request)
	{
		\Log::Info($this->fc.'createMessage');
		$request->validate(['push' => 'required|json']);
		PushMessage::create(['app_id'=>$this->app_id, 'push_message'=>$request->push]);
		return ['status' => 'success', 'message'=>'Push message was successfully created.'];
	}

	public function updateMessageView(Request $request, $id)
	{
		\Log::Info($this->fc.'updateMessageView');
		return view('cb.p.push_json')->with(['push' => PushMessage::findOrFail($id)->push_message, 'id'=>$id]);
	}

	public function updateMessage(Request $request)
	{
		\Log::Info($this->fc.'updateMessage');
		$request->validate(['push' => 'required|json']);
		PushMessage::findOrFail($request->id)->update(['push_message' => $request->push]);
		return ['status' => 'success', 'message'=>'Push message was successfully updated.'];
	}

	public function copyMessage(Request $request)
	{
		\Log::Info($this->fc.'copyMessage');
		PushMessage::findOrFail($request->id)->replicate()->save();
		return redirect()->route('c.push.messages');
	}

    public function deleteMessage(Request $request)
    {
    	\Log::Info($this->fc.'deleteMessage');
        if(!empty($request->id)){
            PushMessage::destroy($request->id);
        }
        return ['status' => 'success', 'message'=>'Push message was successfully deleted.'];
    }

	public function broadcast(Request $request, $id)
	{
		\Log::Info($this->fc.'broadcast');
		$push = PushMessage::findOrFail($id);
		if($this->app_id != $push->app_id){
			return ['status' => 'invalid app'];
		}

		return $this->sendPushMessageObject(json_decode($push->push_message, true));
	}

	public function pushSubscriptionList(Request $request)
	{
		\Log::Info($this->fc.'pushSubscriptionList');
		$subscriptions = PushSubscription::select(['auth_provider','user_id'])->where('app_id',$this->app_id)->paginate(10);
		return view('cb.p.push_subscribers')->with(['subscriptions' => $subscriptions, 'page'=>$request->page??1]);
	}
	
}
