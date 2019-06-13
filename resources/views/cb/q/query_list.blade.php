@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  <div class="row">
    <div class="col-md-6">
      Query List | for the app id: {{\Auth::user()->active_app_id}}
    </div>
    <div class="col-md-6">
      <div class="btn-group" style="float:right">
        <a class="btn btn-default" href="{{route('c.create.new.query')}}">Create New Query</a>
        <a class="btn btn-default" href="{{route('c.query.valid.view')}}">Validation</a>
        <a class="btn btn-default" href="{{route('c.query.valid.msg.view')}}">Customize Validation Messages</a>
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr</th>
						<th>Id</th>
						<th>Name</th>
			            <th>Author</th>
			            <th>Tables</th>
			            <th>Commands</th>
			            <th>Fillables</th>
			            <th>Hiddens</th>
                  <th>Mandatory</th>
			            <th>Joins</th>
			            <th>Filters</th>
			            <th>Special</th>
						<th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($queries as $query)
          <tr id="r{{$query->id}}">
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{$query->id}}</td>
            <td>{{$query->name}}</td>
            <td>{{$query->auth_providers}}</td>
            <td>{{$query->tables}}</td>
            <td>{{$query->commands}}</td>
            <td>{{$query->fillables}}</td>
            <td>{{$query->hiddens}}</td>
            <td>{{$query->mandatory}}</td>
            <td>{{$query->joins}}</td>
            <td>{{$query->filters}}</td>
            <td>{{$query->specials}}</td>
            <td><a href="{{route('c.query.details.view', ['id' => $query->id])}}">Update</a></td>
            <td><a style="cursor: pointer;" onclick="d('{{$query->id}}')">Delete</a>
	        </td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$queries->appends(request()->input())->links()}}
		</div>
	</div>
</div>
<script>
  function d(id){
    $.post("{{ route('c.delete.query') }}", {"_token":"{{csrf_token()}}", "id":id, "_method":"DELETE"}, function(data) {
      if(data['status'] == 'success'){
        $('#r'+id).remove();
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Query was successfully removed.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Query was not removed.</div>');
      }
    })
  }
</script>
@endsection