<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Docs
{
	public function indexHome()
	{
		\Log::Info($this->fc.'indexHome');
		return view($this->theme.'.docs.index')->with(['index'=>'index']);
	}

	public function docIndex($index)
	{
		\Log::Info($this->fc.'docIndex');
		return view($this->theme.'.docs.index')->with(['index'=>$index]);
	}
	
	public function routeMap()
	{
		\Log::Info($this->fc.'routeMap');
		return view($this->theme.'.docs.routemap');
	}

	public function docArticle($index, $sub_index)
	{
		\Log::Info($this->fc.'docArticle');
		return view($this->theme.'.docs.article')->with(['index'=>$index, 'article' => $sub_index]);
	}

	// public function apps()
	// {
	// 	\Log::Info($this->fc.'apps');
	// 	return view($this->theme.'.docs.index')->with(['index'=>'apps']);
	// }

	public function tables()
	{
		\Log::Info($this->fc.'tables');
		return view($this->theme.'.docs.tables');
	}

	public function sessions()
	{
		\Log::Info($this->fc.'sessions');
		return view($this->theme.'.docs.authentication');
	}

	public function auth()
	{
		\Log::Info($this->fc.'auth');
		return view($this->theme.'.docs.authorisation');
	}

	public function files()
	{
		\Log::Info($this->fc.'files');
		return view($this->theme.'.docs.files');
	}

	public function emails()
	{
		\Log::Info($this->fc.'emails');
		return view($this->theme.'.docs.emails');
	}

	public function cdn()
	{
		\Log::Info($this->fc.'cdn');
		return view($this->theme.'.docs.apps');
	}

	public function chat()
	{
		\Log::Info($this->fc.'chat');
		return view($this->theme.'.docs.apps');
	}

	public function alerts()
	{
		\Log::Info($this->fc.'alerts');
		return view($this->theme.'.docs.apps');
	}

	public function pushNotifications()
	{
		\Log::Info($this->fc.'pushNotifications');
		return view($this->theme.'.docs.apps');
	}

	public function prebuild()
	{
		\Log::Info($this->fc.'prebuild');
		return view($this->theme.'.docs.apps');
	}

	public function licenses()
	{
		\Log::Info($this->fc.'licenses');
		return view($this->theme.'.docs.licenses');
	}

}