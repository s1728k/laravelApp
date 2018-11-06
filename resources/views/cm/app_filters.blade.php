@extends("cm.layouts.app")

@section("content")
<div class="row">
	<div class="col s12 m2">
		App Table Filters | 
	</div>
	<div class="col s12 m2">
		selected app - id: {{$selected_app->id}} 
	</div>
	<div class="col s12 m2">
		name: {{$selected_app->name}}
	</div>
	<div class="col s12 m4 ths">
		secret: {{$selected_app->secret}}
	</div>
	<div class="col s12 m2">
		<div class="btn-group" style="float:right"><button class="waves-effect waves-light btn blue darken-2" onclick="save_filters()">Save Filters</button><a class="waves-effect waves-light btn blue darken-2" href="{{route('c.app.list.view')}}">Back</a></div>
	</div>
</div>
<div class="row">
	<div class="col s12">
		<table class="responsive-table">
			<thead>
				<tr>
					<th>Sr</th>
					<th>Table Name</th>
					<th>Enter comma separated filters for each table name</th>
				</tr>
			</thead>
			<tbody style="min-width: 200px;">
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
            	M.toast({html: ht})
			}else{
				var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> Table filters have not been saved successfully!</div>';
				console.log(status);
            	M.toast({html: ht})
			}
			$(".loader").css({"display":"none"});
		})
	}
</script>
@endsection