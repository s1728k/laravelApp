<!DOCTYPE html>
<html>
<head>
	<title>Reset Password Request</title>
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
        	<label>Click below password reset link</label>
            <a href="{{ url('password-reset-form') }}{{$urlpath}}">{{ url('password-reset-form') }}{{$urlpath}}</a>
        </div>
    </div>
</div>
</body>
</html>