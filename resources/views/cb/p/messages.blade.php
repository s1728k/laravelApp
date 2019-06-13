@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  <div class="row">
    <div class="col-md-6">
      Push Messages List | for the app id: {{\Auth::user()->active_app_id}}
    </div>
    <div class="col-md-6">
      <div class="btn-group" style="float:right">
        <a class="btn btn-default" href="{{route('c.push.subscriptions')}}">Push Subscribers</a>
        <a class="btn btn-default" href="{{route('c.push.new.msg.view')}}">Create New Push Message</a>
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr</th>
            <th>title</th>
            <th>body</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($push_messages as $push_message)
          @php
            $push = json_decode($push_message->push_message,true);
            $message = $push['message'];
          @endphp
          <tr id="r{{$push_message->id}}">
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{$message['title']??''}}</td>
            <td>{{$message['body']??''}}</td>
            <td><a onclick="bordcast({{$push_message->id}})" style="cursor: pointer;">Broadcast</a></td>
            <td><a href="{{route('c.push.update.msg', ['id' => $push_message->id])}}">Update</a></td>
            <td><a style="cursor: pointer;" onclick="copyMsg('{{$push_message->id}}')">Copy</a></td>
            <td><a style="cursor: pointer;" onclick="delMsg('{{$push_message->id}}')">Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$push_messages->appends(request()->input())->links()}}
		</div>
	</div>
</div>
<script>
  function bordcast(id){
    $.get('{{route('c.push.broadcast', ['id' => ''])}}/'+id, function(data, status){
      if(data['status'] == 'success'){
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Push Message was successfully broadcasted.</div>');
      }
    })
  }
  function delMsg(id){
    var bool = confirm("Are you sure! you want to remove Push Message ");
    if(!bool){
      return;
    }
    $.post('{{route('c.push.del.msg')}}', {"id":id,"_token":"{{csrf_token()}}"}, function(data){
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