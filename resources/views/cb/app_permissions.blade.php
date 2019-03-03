@extends("cb.layouts.app")

@section("content")
	<div id="alrt"></div>
	<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<caption>App Permissions | <span id="selected_app">selected app - id: {{$selected_app->id}} name: {{$selected_app->name}} secret: {{$selected_app->secret}}</span><div class="btn-group" style="float:right">
					<button class="btn btn-default" onclick="save_permissions()">Save Permissions</button><a class="btn btn-default" href="{{route('c.app.list.view')}}">Back</a></caption>
				<thead>
					<tr>
						<th colspan="3">Create</th>
						<th colspan="3">Read</th>
						<th colspan="3">Update</th>
						<th colspan="3">Delete</th>
					</tr>
					<tr>
						<th>Auth Provider</th>
						<th>Table Name</th>
						<th>Permissions</th>
						<th>Auth Provider</th>
						<th>Table Name</th>
						<th>Permissions</th>
						<th>Auth Provider</th>
						<th>Table Name</th>
						<th>Permissions</th>
						<th>Auth Provider</th>
						<th>Table Name</th>
						<th>Permissions</th>
					</tr>
				</thead>
				<tbody>
					@foreach($p['c'] as $k1 => $p1)
						@foreach($p1 as $k2 => $p2)
						<tr>
							<td>{{$k1}}</td>
							<td>{{$k2}}</td>
							<td><input type="checkbox" id="{{'c'.$k1.$k2}}" onclick="sc('c', '{{$k1}}', '{{$k2}}')"></td>
							<td>{{$k1}}</td>
							<td>{{$k2}}</td>
							<td><input type="checkbox" id="{{'r'.$k1.$k2}}" onclick="sc('r', '{{$k1}}', '{{$k2}}')"></td>
							<td>{{$k1}}</td>
							<td>{{$k2}}</td>
							<td><input type="checkbox" id="{{'u'.$k1.$k2}}" onclick="sc('u', '{{$k1}}', '{{$k2}}')"></td>
							<td>{{$k1}}</td>
							<td>{{$k2}}</td>
							<td><input type="checkbox" id="{{'d'.$k1.$k2}}" onclick="sc('d', '{{$k1}}', '{{$k2}}')"></td>
						</tr>
						@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
<script>
	$("td input").css({"width":"20px", "height":"20px"});

	var p={!! json_encode($p) !!};

	Object.keys(p).forEach(function(m){
		Object.keys(p[m]).forEach(function(a){
			Object.keys(p[m][a]).forEach(function(t){
				$('#' + m + a + t).attr("checked", p[m][a][t]=='true');
			})
		})
	})

	function sc(m,a,t){
		p[m][a][t]= !p[m][a][t];
	}

	function save_permissions(){
		$(".loader").css({"display":"block"});
		$.post("{{route('c.app.permissions.save',['id'=>$selected_app->id])}}", {"_token":"{{csrf_token()}}", "p":p}, function (data, status) {
			if(status=="success"){
				var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> App Permissions have been saved successfully!</div>';
            	$('#alrt').html(ht);
			}else{
				var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> App Permissions have not been saved successfully!</div>';
            	$('#alrt').html(ht);
			}
			$(".loader").css({"display":"none"});
		})
	}

</script>
@endsection
