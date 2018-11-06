@extends("cm.layouts.app")

@section("content")
	<div class="row">
		<div class="col s12 m2">
			App Permissions | 
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
			<div class="btn-group" style="float:right">
					<button class="waves-effect waves-light btn blue darken-2" onclick="save_permissions()">Save Permissions</button><a class="waves-effect waves-light btn blue darken-2" href="{{route('c.app.list.view')}}">Back</a></div>
		</div>
	</div>
	<div class="row">
		<div class="col s12">
			<table class="responsive-table">
				<thead>
					<tr>
						<th>Sr</th>
						<th>Auth Provider</th>
						<th>Role</th>
						<th>Table Name</th>
						<th>Filter Name</th>
						<th>User Type Field</th>
						<th>User Role Field</th>
						<th>User Id Field</th>
						<th>Updatable Fields</th>
						<th colspan="4">Permissions</th>
					</tr>
				</thead>
				<tbody>
					@php
						$index = 0;
						$uh = 0;
						$rh = 0;
						$th = 0;
					@endphp
					@foreach($p as $u => $rs)
					@php
					$uh = 1;
					@endphp
					@foreach($rs as $r => $ts)
					@php
					$rh = 1;
					@endphp
					@foreach($ts as $t => $fs)
					@php
					$th = 1;
					@endphp
					@foreach($fs as $f => $purd)
					@php
					$index = 1+$index;
					$idname = str_replace(" ","",$u.$r.$t.$f);
					@endphp
					<tr>
						<td>{{$index}}</td>
						<td>@if($uh) {{$u}} @endif</td>
						<td>@if($rh) {{$r}} @endif</td>
						<td>@if($th) {{$t}} @endif</td>
						@php
						$uh = 0;
						$rh = 0;
						$th = 0;
						@endphp
						<td>{{$f}}</td>
						<td><select class="form-control" id="u{{$idname}}" 
							onchange="ud('u','{{$u}}','{{$r}}','{{$t}}','{{$f}}')">
							<option>none</option>
			            	@foreach($user_type_fields[$t] as $field)
			            	<option>{{$field}}</option>
			            	@endforeach
			            </select></td>
			            <td><select class="form-control" id="r{{$idname}}" 
			            	onchange="ud('r','{{$u}}','{{$r}}','{{$t}}','{{$f}}')">
							<option>none</option>
			            	@foreach($user_role_fields[$t] as $field)
			            	<option>{{$field}}</option>
			            	@endforeach
			            </select></td>
			            <td><select class="form-control" id="d{{$idname}}" 
			            	onchange="ud('d','{{$u}}','{{$r}}','{{$t}}','{{$f}}')">
							<option>none</option>
			            	@foreach($user_id_fields[$t] as $field)
			            	<option>{{$field}}</option>
			            	@endforeach
			            </select></td>
			            <td onclick="changeDataTarget()"><select multiple="multiple">
						    @foreach($fields[$t] as $field)
						    <option>{{$field}}</option>
						    @endforeach
						</select></td>
						<td><label><input type="checkbox" id="cc{{$idname}}" onclick="curd('c', '{{$u}}','{{$r}}','{{$t}}','{{$f}}')"><span>Create</span></label></td>
						<td><label><input type="checkbox" id="cr{{$idname}}" onclick="curd('r', '{{$u}}','{{$r}}','{{$t}}','{{$f}}')"><span>Read</span></label></td>
						<td><label><input type="checkbox" id="cu{{$idname}}" onclick="curd('u', '{{$u}}','{{$r}}','{{$t}}','{{$f}}')"><span>Update</span></label></td>
						<td><label><input type="checkbox" id="cd{{$idname}}" onclick="curd('d', '{{$u}}','{{$r}}','{{$t}}','{{$f}}')"><span>Delete</span></label></td>
					</tr>
					@endforeach
					@endforeach
					@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
<script>
	$("td input").css({"width":"20px", "height":"20px"});

	

	var p={!! json_encode($p) !!};

	var tb="";
	Object.keys(p).forEach(function(u){
		Object.keys(p[u]).forEach(function(r){
			Object.keys(p[u][r]).forEach(function(t){
				Object.keys(p[u][r][t]).forEach(function(f){
					var idname = (u + r + t + f).replace(/ /g,"");
					$('#u' + idname).val(p[u][r][t][f]['u']);
					$('#r' + idname).val(p[u][r][t][f]['r']);
					$('#d' + idname).val(p[u][r][t][f]['d']);
					$('#cc' + idname).attr("checked", p[u][r][t][f]['p'].indexOf("c")!=-1);
					$('#cr' + idname).attr("checked", p[u][r][t][f]['p'].indexOf("r")!=-1);
					$('#cu' + idname).attr("checked", p[u][r][t][f]['p'].indexOf("u")!=-1);
					$('#cd' + idname).attr("checked", p[u][r][t][f]['p'].indexOf("d")!=-1);
				})
			})
		})
	})

	function curd(c, u, r, t, f){
		var v = p[u][r][t][f]['p'];
		p[u][r][t][f]['p'] = v.indexOf(c)!=-1?v.replace(c,""):v+c;
		console.log(p[u][r][t][f]['p']);
	}

	function ud(ud, u, r, t, f){
		p[u][r][t][f][ud] = $('#'+ ud + u + r + t + f).val();
	}

	function save_permissions(){
		$(".loader").css({"display":"block"});
		$.post("{{route('c.app.permissions.save',['id'=>$selected_app->id])}}", {"_token":"{{csrf_token()}}", "p":p}, function (data, status) {
			if(status=="success"){
				var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> App Permissions have been saved successfully!</div>';
            	M.toast({html: ht})
			}else{
				var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> App Permissions have not been saved successfully!</div>';
				console.log(status);
            	M.toast({html: ht})
			}
			$(".loader").css({"display":"none"});
		})
	}

</script>
@endsection
