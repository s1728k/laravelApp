<?php

namespace App\Providers;

use App\Services\Validator as ValidatorExtended;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider{

    public function boot()
    {
        Validator::resolver(function($translator, $data, $rules, $messages, $customAttributes = array()){
	        return new ValidatorExtended($translator, $data, $rules, $messages, $customAttributes);
	    });
    }

    public function register()
    {
    	
    }
}
