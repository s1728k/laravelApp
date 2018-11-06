@extends('cb.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6" style="text-align: center;">
        	<div class="panel panel-default">
              <div class="panel-body">
	            	<h1>Congratulations! your email address is verified.</h1>
	            	<a class="btn btn-primary" href="{{ route('c.auth.login') }}">Login</a>
	          </div>
	        </div>
        </div>
    </div>
</div>
@endsection
