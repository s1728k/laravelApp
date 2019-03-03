@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>Query List | for the app id: {{\Auth::user()->active_app_id}}<div class="btn-group" style="float:right"> <a class="btn btn-default" href="{{route('c.create.new.query')}}">Create New Query</a></div></caption>
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
          <tr>
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
            <td><a href="{{route('c.query.list.view')}}" onclick="event.preventDefault();
                               document.getElementById('delete_query').submit();">Delete</a>
            <form id="delete_query" method="post" action="{{route('c.delete.query', ['id' => $query->id])}}" style="display: none;">
            	<input type="hidden" name="_token" value="{{ csrf_token() }}">
            	{{ method_field('DELETE') }}
            </form>
	        </td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$queries->appends(request()->input())->links()}}
		</div>
	</div>
</div>
@endsection