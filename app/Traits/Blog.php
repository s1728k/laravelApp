<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait Blog
{
	
	public function categoryHome()
	{
		\Log::Info($this->fc.'categoryHome');
		return view($this->theme.'.blog.category')->with(['category'=>'category']);
	}

	public function blogCategory($category)
	{
		\Log::Info($this->fc.'blogCategory');
		return view($this->theme.'.blog.category')->with(['category'=>$category]);
	}

	public function blogArticle($category, $sub_category)
	{
		\Log::Info($this->fc.'blogArticle');
		return view($this->theme.'.blog.article')->with(['category'=>$category, 'article' => $sub_category]);
	}

}