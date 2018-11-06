@extends('cm.layouts.app')

@section('content')
<div class="row">
    <div class="col s12 m4 offset-m4">
    	<h1 class="center-align">Congratulations! your email address is verified.</h1>
    </div>
    <div class="col s12 m4 offset-m4">
    	<a class="waves-effect waves-light btn blue darken-2 center-align" href="{{ route('c.auth.login') }}">Login</a>
    </div>
</div>
@endsection
