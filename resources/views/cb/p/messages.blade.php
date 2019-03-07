@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
    <div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>Query List | for the app id: {{\Auth::user()->active_app_id}}<div class="btn-group" style="float:right"> <a class="btn btn-default" href="{{route('c.push.new.msg')}}">Create New Push Message</a></div></caption>
				<thead>
					<tr>
						<th>Sr</th>
            <th>title</th>
            <th>body</th>
            <th>urls</th>
            <th>vibrate</th>
            
            <th>dir</th>
            
            <th>tag</th>
            <th>data</th>
            <th>Booleans</th>

            <th>actions</th>
            <th>timestamp</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($messages as $message)
          <tr id="r{{$message->id}}">
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{$message->title}}</td>
            <td>{{$message->body}}</td>
            <td style="word-break: break-all"><strong>icon:</strong>{{$message->icon}}
            <strong>image:</strong>{{$message->image}}
            <strong>badge:</strong>{{$message->badge}}
            <strong>sound:</strong>{{$message->sound}}</td>
            <td>{{$message->vibrate}}</td>
            <td>{{$message->dir}}</td>

            <td>{{$message->tag}}</td>
            <td>{{$message->data}}</td>
            <td>requireInteraction:<strong>{{$message->requireInteraction}}</strong>
            renotify:<strong>{{$message->renotify}}</strong>
            silent:<strong>{{$message->silent}}</strong></td>

            <td style="word-break: break-all">{{$message->actions}}</td>
            <td>{{$message->timestamp}}</td>

            <td><a onclick="bordcast({{$message->id}})" style="cursor: pointer;">Broadcast</a>
            <a href="{{route('c.push.update.msg', ['id' => $message->id])}}">Update</a>
            <a style="cursor: pointer;" onclick="copyMsg('{{$message->id}}')">Copy</a>
            <a style="cursor: pointer;" onclick="delMsg('{{$message->id}}')">Delete</a>
	        </td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$messages->appends(request()->input())->links()}}
		</div>
	</div>
</div>
<script>
  function bordcast(id){
    $.get('{{route('c.push.broadcast', ['id' => ''])}}/'+id, function(data){
      console.log(data);
    })
  }
  function delMsg(id){
    var bool = confirm("Are you sure! you want to remove Push Message ");
    if(!bool){
      return;
    }
    $.post('{{route('c.push.del.msg')}}', {"id":id,"_token":"{{csrf_token()}}"}, function(data){
      console.log(data);
      if(data['status'] == 'success'){
        $('#r'+id).remove();
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Push Message was successfully removed.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Push Message was not removed.</div>');
      }
    })
  }
  function copyMsg(id){
    $.post('{{route('c.push.copy.msg')}}', {"id":id,"_token":"{{csrf_token()}}"}, function(data){
      location.reload();
    })
  }
</script>
@endsection