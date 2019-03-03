@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>App List | <span id="active_app" style="text-align: center;">active app - id: {{$active_app->id}} name: {{$active_app->name}} secret: {{$active_app->secret}}</span><div class="btn-group" style="float:right"> <button class="btn btn-default" data-toggle="modal" data-target="#createNewApp">Create New App</button></caption>
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
            <td>{{$app->secret}}</td>
            <td><a style="cursor:pointer" onclick="activate({{$app->id}}, {{$loop->index}})">Activate</a></td>
            <td><a style="cursor:pointer" onclick="updateApp({{$app->id}}, {{$loop->index}})">Update App</a></td>
            <td><a style="cursor:pointer" href="{{ route('c.app.origins.view', ['id' => $app->id]) }}">Origins</a></td>
            <td><a style="cursor:pointer" href="{{ route('c.app.sql.export', ['id' => $app->id]) }}">ExportDB</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$apps->appends(request()->input())->links()}}
		</div>
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
    $("#updateMyApp").modal();
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
            <input type="text" name="new_app_name" value="" class="form-control">
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

<!-- Modal -->
<div id="goToPage" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Goto Page</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
        	<input type="number" name="goto" value="1" class="form-control"> of Total 100 Pages
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default">Go</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="perPageCount" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter The Number Of Records Per Page</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
        	<input type="number" name="perpage" value="100" class="form-control"> of Total 1000 Records
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default">Save</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="importFromExcel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload File</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
        	<input type="file" id="input-file" onclick="fileChange(event)" placeholder="Upload file" accept=".png,.jpg,.jpeg">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default">Upload</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="exportToExcel" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload File</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
        	<input type="file" id="input-file" onclick="fileChange(event)" placeholder="Upload file" accept=".png,.jpg,.jpeg">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default">Upload</button>
      </div>
    </div>

  </div>
</div>
@endsection