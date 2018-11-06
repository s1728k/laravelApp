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
						<th>Sr</th>
						<th>Auth Provider</th>
						<th>Role</th>
						<th>Table Name</th>
						<th>Permissions</th>
						<th>Filter Name</th>
						<th>User Type Field</th>
						<th>User Role Field</th>
						<th>User Id Field</th>
						<th colspan="2">Permissions</th>
						<th>Updatable Fields</th>
						<th>Permissions</th>
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
					@foreach($fs['f'] as $f => $purd)
					@php
					$index = 1+$index;
					$idname = str_replace(" ","",$u.$r.$t);
					@endphp
					<tr>
						<td>{{$index}}</td>
						<td>@if($uh) {{$u}} @endif</td>
						<td>@if($rh) {{$r}} @endif</td>
						<td>@if($th) {{$t}} @endif</td>
						<td>@if($th)<input type="checkbox" id="cc{{$idname}}" onclick="curd('c', '{{$u}}','{{$r}}','{{$t}}')"> Create @endif</td>
						@php
						$uh = 0;
						$rh = 0;
						$th = 0;
						$idname = str_replace(" ","",$u.$r.$t.$f);
						@endphp
						<td>{{$f}}</td>
						<td><select class="form-control" id="u{{$idname}}" 
							onchange="ud('u','{{$u}}','{{$r}}','{{$t}}','{{$f}}')">
							<option>none</option>
			            	@foreach($fields[$t] as $field)
			            	<option>{{$field}}</option>
			            	@endforeach
			            </select></td>
			            <td><select class="form-control" id="r{{$idname}}" 
			            	onchange="ud('r','{{$u}}','{{$r}}','{{$t}}','{{$f}}')">
							<option>none</option>
			            	@foreach($fields[$t] as $field)
			            	<option>{{$field}}</option>
			            	@endforeach
			            </select></td>
			            <td><select class="form-control" id="d{{$idname}}" 
			            	onchange="ud('d','{{$u}}','{{$r}}','{{$t}}','{{$f}}')">
							<option>none</option>
			            	@foreach($fields[$t] as $field)
			            	<option>{{$field}}</option>
			            	@endforeach
			            </select></td>
						<td><input type="checkbox" id="cr{{$idname}}" onclick="curd('r', '{{$u}}','{{$r}}','{{$t}}','{{$f}}')"> Read</td>
						<td><input type="checkbox" id="cd{{$idname}}" onclick="curd('d', '{{$u}}','{{$r}}','{{$t}}','{{$f}}')"> Delete</td>
			            <td><select class="multi-select-demo" id="uf{{$idname}}" multiple="multiple">
			            	@foreach($fields[$t] as $field)
			            	<option value="{{$field}}" >{{$field}}</option>
			            	@endforeach
			            </select></td>
						<td><input type="checkbox" id="cu{{$idname}}" onclick="curd('u', '{{$u}}','{{$r}}','{{$t}}','{{$f}}')"> Update</td>
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
				var idname = (u + r + t).replace(/ /g,"");
				$('#cc' + idname).attr("checked", p[u][r][t]['p'] == '1');
				Object.keys(p[u][r][t]['f']).forEach(function(f){
					var idname = (u + r + t + f).replace(/ /g,"");
					$('#u' + idname).val(p[u][r][t]['f'][f]['u']);
					$('#r' + idname).val(p[u][r][t]['f'][f]['r']);
					$('#d' + idname).val(p[u][r][t]['f'][f]['d']);
					$('#uf' + idname).val(p[u][r][t]['f'][f]['uf']);
					$('#uf' + idname).multiselect({
			            selectAllValue: 'multiselect-all',
			            enableCaseInsensitiveFiltering: true,
			            enableFiltering: true,
			            onChange: function(element, checked) {
			                var brands = $('#uf' + idname + ' option:selected');
			                var selected = [];
			                $(brands).each(function(index, brand){
			                    selected.push($(this).val());
			                });
			                p[u][r][t]['f'][f]['uf'] = selected;
			            }
			        });
					$('#cr' + idname).attr("checked", p[u][r][t]['f'][f]['p'].indexOf("r")!=-1);
					$('#cu' + idname).attr("checked", p[u][r][t]['f'][f]['p'].indexOf("u")!=-1);
					$('#cd' + idname).attr("checked", p[u][r][t]['f'][f]['p'].indexOf("d")!=-1);
				})
			})
		})
	})

	function curd(c, u, r, t, f = null){
		if(f){
			var v = p[u][r][t]['f'][f]['p'];
			p[u][r][t]['f'][f]['p'] = v.indexOf(c)!=-1?v.replace(c,""):v+c;
			console.log(p[u][r][t]['f'][f]['p']);
		}else{
			var v = p[u][r][t]['p'];
			p[u][r][t]['p'] = (parseInt(v) + 1) % 2;
			console.log(p[u][r][t]['p']);
		}
	}

	function ud(ud, u, r, t, f){
		console.log($('#'+ ud + u + r + t + f).val());
		p[u][r][t]['f'][f][ud] = $('#'+ ud + u + r + t + f).val();
		console.log(p[u][r][t]['f'][f][ud]);
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
