@extends("cm.layouts.app")

@section("content")
<div class="row">
  <div class="col s12 m2">
    App List | 
  </div>
  <div class="col s12 m2">
    selected app - id: {{$active_app->id}} 
  </div>
  <div class="col s12 m2">
    name: {{$active_app->name}}
  </div>
  <div class="col s12 m4 ths">
    secret: {{$active_app->secret}}
  </div>
  <div class="col s12 m2">
    <div class="btn-group" style="float:right">
        <div class="btn-group" style="float:right"> <button class="waves-effect waves-light btn blue darken-2 modal-trigger" href="#createNewApp">Create New App</button></div>
  </div>
</div>
<div class="row">
	<div class="col s12">
		<table class="responsive-table">
			<thead>
				<tr>
					<th>Sr</th>
					<th>App Id</th>
          <th>App Name</th>
          <th>App Secret</th>
					<th colspan="5">Actions</th>
				</tr>
			</thead>
			<tbody>
        @foreach($apps as $app)
        <tr>
          <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
          <td>{{$app->id}}</td>
          <td>{{$app->name}}</td>
          <td class="ths">{{$app->secret}}</td>
          <td><a href="JavaScript:void(0);" onclick="activate({{$app->id}}, {{$loop->index}})">Activate</a></td>
          <td><a href="JavaScript:void(0);" onclick="updateApp({{$app->id}}, {{$loop->index}})">Update App</a></td>
          <td><a href="{{ route('c.app.roles.view', ['id' => $app->id]) }}">Roles</a></td>
          <td><a href="{{ route('c.app.filters.view', ['id' => $app->id]) }}">Filters</a></td>
          <td><a href="{{ route('c.app.permissions.view', ['id' => $app->id]) }}">Permissions</a></td>
          <td><a href="{{ route('c.app.origins.view', ['id' => $app->id]) }}">Origins</a></td>
        </tr>
        @endforeach
			</tbody>
		</table>
    {{$apps->appends(request()->input())->links()}}
	</div>
</div>
<script>
  var app_id = 0; var app_name = ""; var app_secret = "";
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
    $("#updateMyApp").modal('open');
  }
</script>

<!-- Modal -->
<div id="createNewApp" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
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
        <button type="submit" class="waves-effect waves-light btn blue darken-2">Create</button>
        <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
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
          <h4 class="modal-title">Change My App Details</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>New app name</label>
            <input type="text" name="new_app_name" value="" class="form-control">
          </div>
          <div class="form-group">
            <label><input type="checkbox" name="request_new_secret" ><span>Request New Secret</span></label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="waves-effect waves-light btn blue darken-2">Update</button>
          <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
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
        <h4 class="modal-title">Confirm App Deletion</h4>
      </div>
      <div class="modal-body">
        <p>Note that this action will delete all app database tables and settings permanently.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="waves-effect waves-light btn blue darken-2" onclick="deleteMyApp()">Delete</button>
        <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
      </div>
    </div>

  </div>
</div>

@endsection