@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<caption>App Roles | <span id="selected_app">selected app - id: {{$selected_app->id}} name: {{$selected_app->name}} secret: {{$selected_app->secret}}</span><div class="btn-group" style="float:right"><button class="btn btn-default" onclick="save_roles()">Save Roles</button><a class="btn btn-default" href="{{route('c.app.list.view')}}">Back</a></caption>
				<thead>
					<tr>
						<th>Sr</th>
						<th>Auth Provider</th>
						<th>Role Field Name</th>
						<th>Enter comma separated roles for each user type</th>
					</tr>
				</thead>
				<tbody>
					@foreach($auth_providers as $user_type => $roles)
					<tr>
						<td>{{($loop->index+1)}}</td>
						<td>{{$user_type}}</td>
						<td><input type="text" id="f{{$user_type}}" onchange="rf('{{$user_type}}')" class="form-control" value="{{$roles['f']}}" /></td>
						<td><input type="text" id="r{{$user_type}}" onchange="rc('{{$user_type}}')" class="form-control" value="{{implode(',',$roles['r'])}}" /></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	r = {!! json_encode($auth_providers) !!};
	function rf (u) {
		r[u]['f'] = $("#f"+u).val();
	}
	function rc (u) {
		if($("#r"+u).val().indexOf(",")!=-1){
			r[u]['r'] = $("#r"+u).val().split(",");
		}else{
			r[u]['r'] = [$("#r"+u).val()];
		}
	}
	function save_roles(){
		$(".loader").css({"display":"block"});
		$.post("{{route('c.app.roles.save',['id'=>$selected_app->id])}}", {"_token":"{{csrf_token()}}", "r":r}, function (data, status) {
			if(status=="success"){
				var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Auth Roles have been saved successfully!</div>';
            	$('#alrt').html(ht);
			}else{
				var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> Auth Roles have not been saved successfully!</div>';
				console.log(status);
            	$('#alrt').html(ht);
			}
			$(".loader").css({"display":"none"});
		})
	}
</script>
@endsection