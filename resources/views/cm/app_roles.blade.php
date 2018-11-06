@extends("cm.layouts.app")

@section("content")
<div class="row">
	<div class="col s12 m2">
		App Roles | 
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
		<div style="float:right"><a class="waves-effect waves-light btn blue darken-2" onclick="save_roles()">Save Roles</a><a class="waves-effect waves-light btn blue darken-2" href="{{route('c.app.list.view')}}">Back</a></div>
	</div>
</div>
<div class="row">
	<div class="col s12">
		<table class="responsive-table" >
			<thead>
				<tr>
					<th>Sr</th>
					<th>Auth Provider</th>
					<th>Role Field Name</th>
					<th>Enter comma separated roles for each user type</th>
				</tr>
			</thead>
			<tbody style="min-width: 200px;">
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
            	M.toast({html: ht})
			}else{
				var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> Auth Roles have not been saved successfully!</div>';
				console.log(status);
            	M.toast({html: ht})
			}
			$(".loader").css({"display":"none"});
		})
	}
</script>
@endsection