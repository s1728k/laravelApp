<?php

namespace App\Traits;

use App\App;
use Illuminate\Http\Request;

trait Crone
{
	public function change_auth_providers_josn_structure11()
	{
		foreach (App::all() as $app) {
			$a = json_decode($app->auth_providers, true);
			$arr=[];
			foreach ($a as $k => $v) {
				$arr[$k]=['f'=>'role', 'r' =>$v];
			}
			$app->update([
				"auth_providers" => json_encode($arr)
			]);
		}
		return "success";
	}

	public function change_auth_providers_josn_structure()
	{
		\Log::Info('change_auth_providers_josn_structure');
		foreach (App::all() as $app) {
			// $a = json_decode($app->table_filters, true);
			$t = $this->getTables($app->id);
			\Log::Info($t);
			$arr=[];
			foreach ($t as $v) {
				$arr[$v]=['All Rows'];
			}
			$app->update([
				"table_filters" => json_encode($arr)
			]);
		}
		return "success";
	}
}