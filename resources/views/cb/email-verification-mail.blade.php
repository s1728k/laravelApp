<!DOCTYPE html>
<html>
<head>
	@if($msg == "email")
	<title>Confirm your email to complete your registration.</title>
	@endif
	@if($msg == "alias")
	<title>Confirm your alias address</title>
    @endif
</head>
<body>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h3>HoneyWeb.Org</h3>
		</div>
	</div>
    <div class="row">
        <div class="col-md-12">
        	@if($msg == "email")
        	<label>Click below verification link</label>
            <a href="{{ url('email_verified') }}{{$urlpath}}">{{ url('email_verified') }}{{$urlpath}}</a>
            @endif
            @if($msg == "alias")
            Your Code: {{$code}}
            @endif
        </div>
    </div>
</div>
</body>
</html>