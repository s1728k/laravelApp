@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  <div class="row">
    <div class="col-md-6">
      Push Subscribers List | for the app id: {{\Auth::user()->active_app_id}}
    </div>
    <div class="col-md-6">
      <div class="btn-group" style="float:right">
        <a id="back" class="btn btn-default" href="{{route('c.push.messages')}}">Back</a></div>
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr</th>
            <th>Auth Provider</th>
            <th>User Id</th>
					</tr>
				</thead>
				<tbody>
          @foreach($subscriptions as $subscription)
          <tr id="r{{$subscription->id}}">
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{$subscription->auth_provider}}</td>
            <td>{{$subscription->user_id}}</td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$subscriptions->appends(request()->input())->links()}}
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