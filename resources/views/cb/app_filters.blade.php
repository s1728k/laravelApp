@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<caption>App Table Filters | <span id="selected_app">selected app - id: {{$selected_app->id}} name: {{$selected_app->name}} secret: {{$selected_app->secret}}</span><div class="btn-group" style="float:right"><button class="btn btn-default" onclick="save_filters()">Save Filters</button><a class="btn btn-default" href="{{route('c.app.list.view')}}">Back</a></caption>
				<thead>
					<tr>
						<th>Sr</th>
						<th>Table Name</th>
						<th>Enter comma separated filters for each table name</th>
					</tr>
				</thead>
				<tbody>
					@foreach($table_filters as $t => $f)
					<tr>
						<td>{{($loop->index+1)}}</td>
						<td>{{$t}}</td>
						<td><input type="text" id="f{{$t}}" onchange="rc('{{$t}}')" class="form-control" value="{{implode(',',$f)}}" /></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	f = {!! json_encode($table_filters) !!};
	function rc (u) {
		if($("#f"+u).val().indexOf(",")!=-1){
			f[u] = $("#f"+u).val().split(",");
		}else{
			f[u] = [$("#f"+u).val()];
		}
	}
	function save_filters(){
		$(".loader").css({"display":"block"});
		$.post("{{route('c.app.filters.save',['id'=>$selected_app->id])}}", {"_token":"{{csrf_token()}}", "f":f}, function (data, status) {
			if(status=="success"){
				var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Table filters have been saved successfully!</div>';
            	$('#alrt').html(ht);
			}else{
				var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> Table filters have not been saved successfully!</div>';
				console.log(status);
            	$('#alrt').html(ht);
			}
			$(".loader").css({"display":"none"});
		})
	}
</script>
@endsection