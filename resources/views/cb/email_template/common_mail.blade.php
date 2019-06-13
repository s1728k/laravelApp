<!DOCTYPE html>
<html>
<head>
	<title>Common Mail</title>
    <style>
        table{
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid lavender;
        }
        table, th, td {
          border-bottom: 1px solid lavender;
        }
        th {
          height: 40px;
          text-align: left;
          background-color: lavender;
        }
        td, th {
            padding: 15px;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        #title{
            background-color: lavender;
            padding: 15px;
        }
        p{
            color:black;
        }
    </style>
</head>
<body>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12" id="title">
			<h3>{{$obj['title']??''}}</h3>
		</div>
	</div><br>
    <div class="row">
        <div class="col-md-12">
        	@foreach($obj as $k => $v)
                @if(!in_array($k, ['title', 'plain_text', 'embed', 'embedData']) )
                    @if(is_array($v))
                    <div class="row">
                        <div class="col-md-12" style="overflow-x:auto;">
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
                                        <td>
                                            @if(filter_var($v1, FILTER_VALIDATE_URL))
                                            <a href="{{$v1}}">{{$v1}}</a>
                                            @else
                                            {{$v1}}
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-md-12">
                            <p>
                                <strong>{{$k}}:</strong>
                                @if(filter_var($v, FILTER_VALIDATE_URL))
                                <a href="{{$v}}">{{$v}}</a>
                                @else
                                {{$v}}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                @endif
                @if($k == 'embed' && $v != '')
                <img src="{{ $message->embed($v) }}">
                @endif
                @if($k == 'plain_text' && $v != '')
                <span style="white-space: pre-line">{{$v}}</span>
                @endif
            @endforeach
        </div>
    </div>
</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Resume Services</title>
</head>
<body>
    <iframe src="https://docs.google.com/gview?url=http://remote.url.tld/path/to/document.doc&embedded=true"></iframe>
    
</body>
</html>