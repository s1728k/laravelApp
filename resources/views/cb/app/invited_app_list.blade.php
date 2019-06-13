@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  @if($errors->has('name'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('name')}}</div>@endif
  <div class="row">
    <div class="col-md-8">
      Invited App List | <span id="active_app" style="text-align: center; word-break: break-all;">active app - id: {{$active_app->id}} name: {{$active_app->name}} secret: {{$active_app->secret}}</span> 
    </div>
    <div class="col-md-4">
      <div class="btn-group" style="float:right">
        <a class="btn btn-default" href="{{ route('c.app.list.view') }}">My Apps</a>
        <a class="btn btn-default" href="{{ route('c.public.app.list.view') }}">Public Apps</a>
        <button class="btn btn-default" data-toggle="modal" data-target="#createNewApp">Create New App</button>
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr</th>
						<th>App Id</th>
            <th>App Name</th>
            <th>App Secret</th>
            <th>Token Lifetime</th>
						<th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($apps as $app)
          <tr>
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{$app->id}}</td>
            <td>{{$app->name}}</td>
            <td>{{$app->secret}}</td>
            <td>{{$app->token_lifetime}}</td>
            <td><a href="JavaScript:void(0);" onclick="activate({{$app->id}}, {{$loop->index}})">Activate</a></td>
            <td><a href="{{ route('c.app.origins.view', ['id' => $app->id]) }}">Origins</a></td>
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


@endsection