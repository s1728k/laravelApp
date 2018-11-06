<?php

namespace App\Traits;

use App\Notifications\Welcome;
use Illuminate\Http\Request;

trait PushesNotifications
{
	public function saveSubscription(Request $request)
	{
		$table = "App\\PushSubscription";
		foreach ($table::all() as $key => $value) {
			$value->delete();
		}
		$table::create(['subscription' => json_encode($request->all())]);
		return ['status' => 'success'];
	}

	public function sendMessage()
	{
		$table = "App\\PushSubscription";
		\Notification::send($table::all(), new Welcome());
		return ['status' => 'success'];
	}
}