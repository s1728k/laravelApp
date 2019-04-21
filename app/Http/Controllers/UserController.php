<?php
namespace App\Http\Controllers;

class UserController extends CloudController
{
    protected $rtype = 'guest';
    protected $auth = 'auth';
    protected $theme = 'cb';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $table = 'App\\Visitor';
        // $record = $table::where('ip_address', request()->ip())->first();
        // $this->theme = $record->theme??'cb';
        parent::__construct($this->rtype, $this->auth, $this->theme);
    }
}