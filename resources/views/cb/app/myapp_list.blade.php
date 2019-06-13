@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('name'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('name')}}</div>@endif
  @if($errors->has('id'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('id')}}</div>@endif
  <div class="row">
    <div class="col-md-9">
      <div class="well well-sm"> My App List | <span id="active_app" style="text-align: center; word-break: break-all;">active app - id: {{$active_app->id}} name: {{$active_app->name}} secret: {{$active_app->secret}}</span> </div>
    </div>
    <div class="col-md-3">
      <div class="btn-group" style="float:right;">
        <a class="btn btn-default" href="{{ route('c.invited.app.list.view') }}">Invited Apps</a>
        <a class="btn btn-default" href="{{ route('c.public.app.list.view') }}">Public Apps</a>
        <button class="btn btn-default" data-toggle="modal" data-target="#createNewApp">Create New App</button>
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12">
      <div class="well well-sm table-responsive">
  			<table class="table">
  				<thead>
  					<tr>
  						<th>Sr</th>
  						<th>App Id</th>
              <th>App Name</th>
              {{-- <th>App Secret</th> --}}
              <th>Token Lifetime</th>
              <th>Availability</th>
  						<th colspan="5">Actions</th>
  					</tr>
  				</thead>
  				<tbody>
            @foreach($apps as $app)
            <tr id="r{{$app->id}}">
              <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
              <td>{{$app->id}}</td>
              <td>{{$app->name}}</td>
              {{-- <td style="word-break: break-word">{{$app->secret}}</td> --}}
              <td>{{$app->token_lifetime}}</td>
              <td>{{$app->availability}}</td>
              <td><a href="JavaScript:void(0);" onclick="activate({{$app->id}}, {{$loop->index}})">Activate</a></td>
              <td><a href="JavaScript:void(0);" onclick="updateApp({{$app->id}}, {{$loop->index}})">Update</a></td>
              <td><a href="{{route('c.app.user.name.fields.view', ['id'=>$app->id]) }}">User Fields</a></td>
              <td><a href="{{route('c.app.origins.view', ['id' => $app->id]) }}">Origins</a></td>
              <td><a href="{{route('c.invited.users.view', ['id'=>$app->id]) }}">Invited Users</a></td>
              <td><a href="{{route('c.app.sql.export', ['id' => $app->id]) }}">ExportDB</a></td>
              <td><a href="{{route('c.app.desc.view', ['id' => $app->id]) }}">Description</a></td>
              <td><a href="JavaScript:void(0);" onclick="copyApp({{$app->id}})">Copy</a></td>
              <td><a href="JavaScript:void(0);" onclick="deleteApp({{$app->id}})">Delete</a></td>
            </tr>
            @endforeach
  				</tbody>
  			</table>
      </div>
      {{$apps->appends(request()->input())->links('cb.layouts.pagination')}}
		</div>
	</div>
</div>
<script>
  var app_id = 0; var app_name = ""; var app_secret = "";var holdon = false;
  function activate(id, sr){
    $.post("{{route('c.app.activate')}}", {"_token":"{{csrf_token()}}", "active_app_id":id}, function(data){
      if(data['status'] == "success"){
        app_id = $("tr:nth-child("+String(sr + 1)+") td:nth-child(2)").html();
        app_name = $("tr:nth-child("+String(sr + 1)+") td:nth-child(3)").html();
        app_secret = $("tr:nth-child("+String(sr + 1)+") td:nth-child(4)").html();
        $("#active_app").html("active app:- id=" + String(app_id) + " name=" + app_name + " secret=" + app_secret);
      }
    });
  }
  function updateApp(id, sr){
    $(".app_id").val(id);
    $("input[name='new_app_name']").val($("tr:nth-child("+String(sr + 1)+") td:nth-child(3)").html());
    $("input[name='token_lifetime']").val($("tr:nth-child("+String(sr + 1)+") td:nth-child(5)").html());
    $("#updateMyApp").modal();
  }
  function copyApp(id) {
    if(holdon){return;}
    holdon = true;
    $.post("{{ route('c.app.copy') }}",{'_token':'{{csrf_token()}}','id':id},function(data){
      if(data['status'] == 'success'){
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> App was successfully copied.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> App was not copied.</div>');
      }
      document.getElementById("alrt").scrollIntoView();
      location.replace(window.location.href);
    });
  }
  function deleteApp(id){
    if(!confirm("Deleting app will delete all its assosiated tables and queries. Please confirm!")){
      return;
    }
    $.post("{{ route('c.app.delete') }}",{'_token':'{{csrf_token()}}','id':id,'_method':'delete'},function(data){
      if(data['status'] == 'success'){
        $('#r'+id).remove();
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> App was successfully deleted.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> App was not deleted.</div>');
      }
      document.getElementById("alrt").scrollIntoView();
    });
  }
</script>

<!-- Modal -->
<div id="createNewApp" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create New App</h4>
      </div>
      <form method="post" action="{{ route('c.create.new.app') }}" >
      <div class="modal-body">
          <input type="hidden" name="_token" value="{{csrf_token()}}" />
          <div class="form-group">
            <label>App Name</label>
            <input type="text" name="name" class="form-control" placeholder="App Name">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default">Create</button>
      </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="updateMyApp" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form method="post" action="{{route('c.update.app')}}">
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="id" class="app_id" />
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change My App Details</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>New app name</label>
            <input type="text" name="new_app_name" class="form-control">
          </div>
          <div class="form-group">
            <label>New token liftime (seconds)</label>
            <input type="number" name="token_lifetime" class="form-control">
          </div>
          <div class="form-group">
            <label>Availability</label>
            <select name="availability" class="form-control"><option>Private</option><option>Public</option></select>
          </div>
          <div class="form-group">
            <input type="checkbox" name="request_new_secret" ><label>Request New Secret</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default">Update</button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="deleteMyApp" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirm App Deletion</h4>
      </div>
      <div class="modal-body">
        <p>Note that this action will delete all app database tables and settings permanently.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="deleteMyApp()">Delete</button>
      </div>
    </div>

  </div>
</div>
@endsection