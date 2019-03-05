<?php

namespace App\Traits;

use App\Notifications\Welcome;
use Illuminate\Http\Request;

trait PushesNotifications
{
	public function saveSubscription(Request $request)
	{
		$table = "App\\PushSubscription";
		$exists = $table::where('subscription', json_encode($request->all()))->first();
		if(!$exists){
			$table::create(['subscription' => json_encode($request->all())]);
			return ['message' => 'successfully saved'];
		}else{
			return ['message' => 'already saved'];
		}
	}

	public function sendMessage()
	{
		$table = "App\\PushSubscription";
		\Notification::send($table::all(), new Welcome());
		return ['status' => 'success'];
	}
}
