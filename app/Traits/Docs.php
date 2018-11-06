<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Docs
{
	
	public function routeMap()
	{
		return view($this->theme.'.docs.routemap');
	}

	public function apps()
	{
		return view($this->theme.'.docs.apps');
	}

	public function tables()
	{
		return view($this->theme.'.docs.tables');
	}

	public function sessions()
	{
		return view($this->theme.'.docs.authentication');
	}

	public function auth()
	{
		return view($this->theme.'.docs.authorisation');
	}

	public function files()
	{
		return view($this->theme.'.docs.files');
	}

	public function emails()
	{
		return view($this->theme.'.docs.emails');
	}

	public function cdn()
	{
		return view($this->theme.'.docs.apps');
	}

	public function chat()
	{
		return view($this->theme.'.docs.apps');
	}

	public function alerts()
	{
		return view($this->theme.'.docs.apps');
	}

	public function pushNotifications()
	{
		return view($this->theme.'.docs.apps');
	}

	public function prebuild()
	{
		return view($this->theme.'.docs.apps');
	}

	public function licenses()
	{
		return view($this->theme.'.docs.licenses');
	}

}