<!DOCTYPE html>
<html>
<head>
	<title>Email Confirmation</title>
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
        	<label>Click below verification link</label>
            <a href="{{ url('email_verified') }}{{$urlpath}}">{{ url('email_verified') }}{{$urlpath}}</a>
        </div>
    </div>
</div>
</body>
</html>