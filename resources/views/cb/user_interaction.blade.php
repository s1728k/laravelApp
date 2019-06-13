@extends('cb.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
    	<div class="col-md-3"></div>
        <div class="col-md-6" style="text-align: center;">
        	<div class="panel panel-default">
              <div class="panel-body">
                @if($msg == "login_redirect")
                    <h1>Verification link has been sent to your email (Please check in spam folder also). Please click the verification link to confirm your email.</h1>
                @endif
                @if($msg == "signup")
	            	<h1>Verification link has been sent to your email (Please check in spam folder also). Please click the verification link to confirm your email.</h1>
                @endif
                @if($msg == "signup_complete")
                    <h1>Congratulations! your email address is verified.</h1>
                    <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                @endif
                @if($msg == "reset")
                    <h1>Password reset link has been sent to your email (Please check in spam folder also). Please click the link to reset your password.</h1>
                @endif
                @if($msg == "invalid_link")
                    <h1>This link is not valid now.</h1>
                @endif
                @if($msg == "reset_complete")
                    <h1>Congratulations! you have reset your password.</h1>
                    <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                @endif
                @if($msg == "user_blocked")
                    <h1>Hi {{$user->name}}. you have been blocked by the site admin.</h1>
                @endif
                @if($msg == "404")
                    <h1>404 Page Not Found.</h1>
                @endif
	          </div>
	        </div>
        </div>
    </div>
</div>
@endsection
