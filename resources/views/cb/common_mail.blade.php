<!DOCTYPE html>
<html>
<head>
	<title>Common Mail</title>
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
        	@foreach($obj as $k => $v)
                @if(is_array($v))
                <table class="table">
                    <thead>
                        <tr>
                            @foreach($v[0] as $k1 => $v1)
                            <th>{{$k1}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($v as $aobj)
                        <tr>
                            @foreach($aobj as $k1 => $v1)
                            <td>{{$v1}}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <label>{{$k}}:</label>{{$v}}
                @endif
            @endforeach
        </div>
    </div>
</div>
</body>
</html>