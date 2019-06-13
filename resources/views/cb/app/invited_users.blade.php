@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('email'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('email')}}</div>@endif
  <div class="row">
    <div class="col-md-6">
      Invited Users | for app id: {{$id}} 
    </div>
    <div class="col-md-6">
      <div class="btn-group" style="float:right"> 
        <button class="btn btn-default" data-toggle="modal" data-target="#inviteNewUser">Invite New User</button>
        <a class="btn btn-default" href="{{route('c.app.list.view')}}">Back</a>
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr</th>
						<th>Invited User Name</th>
            <th>Invited User Email</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($invited_users as $user)
          <tr id="r{{$user->id}}">
            <td>{{ ($loop->index + 1) }}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td><a href="JavaScript:void(0);" onclick="deleteUser('{{$user->id}}')">Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
  function deleteUser(id){
    $.post("{{route('c.invited.delete.user')}}",{"_method":"delete","_token":"{{csrf_token()}}","app_id":"{{$id}}","user_id":id}, function(data, status){
      if(status == 'success'){
        $('#r'+id).remove();
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> User was successfully removed.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> User was not removed.</div>');
      }
    });
  }
</script>


<!-- Modal -->
<div id="inviteNewUser" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form method="post" action="{{ route('c.invited.new.user.submit') }}" >
      <input type="hidden" name="_token" value="{{csrf_token()}}" />
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter email address of your invitee</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="user@example.com">
            <input type="hidden" name="app_id" value="{{$id}}">
          </div>
          <p id="waiting"></p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default">Invite</button>
      </div>
      </form>
    </div>

  </div>
</div>

@endsection