@extends('cb.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
    	<div class="col-md-3"></div>
        <div class="col-md-6" style="text-align: center;">
        	<div class="panel panel-default">
              <div class="panel-body">
	            	<h1>Hi {{$user->name}}. you have been blocked by the site admin.</h1>
	          </div>
	        </div>
        </div>
    </div>
</div>
@endsection
