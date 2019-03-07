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
		$table = "App\\PushSubscription";
		$exists = $table::where('subscription', json_encode($request->subscription))->first();
		if(!$exists){
			$table::create(['subscription' => json_encode($request->subscription)]);
			return ['message' => 'successfully saved'];
		}else{
			return ['message' => 'already saved'];
		}
	}

	public function messageList(Request $request)
	{
		// $messages = PushMessage::paginate(10);
		return view('cb.p.messages')->with([
			'messages' => PushMessage::where('app_id', $this->app_id)->paginate(10), 
			'page'=>$page??1
		]);
	}

	public function createMessageView(Request $request)
	{
		$title = App::findOrFail($this->app_id)->name;
		return view('cb.p.create_message')->with(['title' => $title]);
	}

	public function createMessage(Request $request)
	{
		$arr = [];
		foreach ($request->all() as $key => $value) {
			if(!empty($value)){
				if(in_array($key, ['title', 'vibrate', 'dir', 'tag', 'lang', 'data'])){
					$request->validate([$key => 'string|max:255']);
				}
				if(in_array($key, ['body', 'icon', 'image', 'badge', 'sound', 'actions'])){
					$request->validate([$key => 'string|max:65536']);
				}
			}
			$arr[$key] = $value;
		}
		PushMessage::create($arr);
		return redirect()->route('c.push.messages');
	}

	public function updateMessageView(Request $request, $id)
	{
		return view('cb.p.update_message')->with(['message' => PushMessage::findOrFail($id)]);
	}

	public function updateMessage(Request $request)
	{
		$arr = [];
		foreach ($request->all() as $key => $value) {
			if(!empty($value)){
				if(in_array($key, ['title', 'vibrate', 'dir', 'tag', 'lang', 'data'])){
					$request->validate([$key => 'string|max:255']);
				}
				if(in_array($key, ['body', 'icon', 'image', 'badge', 'sound', 'actions'])){
					$request->validate([$key => 'string|max:65536']);
				}
			}
			$arr[$key] = $value;
		}
		PushMessage::findOrFail($request->id)->update($arr);
		return redirect()->route('c.push.messages');
	}

	public function copyMessage(Request $request)
	{
		PushMessage::findOrFail($request->id)->replicate()->save();;
		return redirect()->route('c.push.messages');
	}

    public function deleteMessage(Request $request)
    {
        \Log::Info(request()->ip()." deleted push message ".$request->id." for app id ".$this->app_id);
        if(!empty($request->id)){
            PushMessage::destroy($request->id);
        }
        return ['status' => 'success'];
    }

	public function broadcast(Request $request, $id)
	{
		$push = PushMessage::findOrFail($id);
		if($this->app_id != $push->app_id){
			return ['status' => 'invalid app'];
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

		$subscriptions = PushSubscription::where('app_id', $this->app_id)->latest()->pluck('subscription');
		$notifications = [];
		foreach ($subscriptions as $s) {
            $webPush->sendNotification(Subscription::create(json_decode($s,true)), json_encode($push) );
		}

        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                echo "[v] Message sent successfully for subscription {$endpoint}.";
            } else {
                echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
            }
        }
	}
	
}
