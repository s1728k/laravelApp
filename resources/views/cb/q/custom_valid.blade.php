@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  	<div id="alrt"></div>
  	@if($errors->has('field'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('field')}}</div>@endif
  	@if($errors->has('rule'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('rule')}}</div>@endif
	<div class="row">
		<div class="col-md-6">
			Validation | for the app id = {{\Auth::user()->active_app_id}}
		</div>
		<div class="col-md-6">
			<div class="btn-group" style="float:right;">
				<a class="btn btn-default" onclick="addRule()">Add Validation Rule</a>
				<a class="btn btn-default" href="{{route('c.query.list.view')}}">Back</a></div>
		</div>
	</div><hr>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr.No.</th>
						<th>Field</th>
						<th>Validation</th>
						<th colspan="2">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($frules as $frule)
					<tr id="r{{$frule->id}}">
						<td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
						<td>{{$frule->field}}</td>
						<td>{{$frule->rule}}</td>
						<td><a style="cursor: pointer;" onclick="editRule('{{$frule->field}}','{{$frule->rule}}','{{$frule->id}}')">edit</a></td>
						<td><a style="cursor: pointer;" onclick="deleteRule('{{$frule->id}}')">delete</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
			{{$frules->appends(request()->input())->links()}}
		</div>
	</div>
</div>

<script>
	String.prototype.capitalize = function() {
	    return this.charAt(0).toUpperCase() + this.slice(1);
	}
	var arr = [];
	function addRule() {
		// $("#field").val('');
		$("#rfields").html('none');
		$("#rid").val('');
		arr = [];
		$("#edit_cmd").hide();$("#add_cmd").show();$("#rule_id").hide();
		$("#addValidationRule").modal();
	}
	function editRule(field,rule,id) {
		$("#field").val(field);
		$("#rfields").html(rule);
		$("#rid").val(rule);
		arr = rule.split('|');
		$("#edit_cmd").show();$("#add_cmd").hide();$("#rule_id").show();
		$("#rule_id").val(id);
		$("#rule_title").html('Edit Validation Rule');
		$("#addValidationRule").modal();
	}
	function toggleRule(){
		let srule = $("#srule").val();
		rule = srule.split('[')[0];
		rule_check = rule;

		if(rule != srule){
			let addi = srule.split('[')[1];
			rule = addi.replace(']','')+'|'+rule;
		}

		val = "";
		if(['after','after_or_equal','before','before_or_equal'].indexOf(rule_check)!=-1){
			if($("#date_field").val() == 'Date Value'){
				val = $("#date_value").val() ? rule + ':'+ $("#date_value").val() : '';
			}else{
				val = $("#date_field").val() ? rule + ':'+ $("#date_field").val() : '';
			}
		}else if(['different','same'].indexOf(rule_check)!=-1){
			fieldsParam();
			val = $("#param_field").val() ? rule + ':'+ $("#param_field").val() : '';
		}else if(['in_array'].indexOf(rule_check)!=-1){
			fieldsParam([$("#field").val()]);
			val = $("#param_field").val() ? rule + ':'+ $("#param_field").val() : '';
		}else if(['date_format','in','mimetypes','mimes','not_in','regex'].indexOf(rule_check)!=-1){
			val = $("#param").val() ? rule + ':'+ $("#param").val() : '';
		}else if(['between','digits_between'].indexOf(rule_check)!=-1){
			val = ($("#min").val() && $("#max").val() && ($("#max").val() >= $("#min").val()) ) ? rule + ':'+ $("#min").val() + ',' + $("#max").val() : '';
			$(".input-group-addon").css({"width":"55px", "text-align":"left"});
		}else if(['digits','max','min','size'].indexOf(rule_check)!=-1 ){
			val = $("#n_param").val() ? rule + ':'+ $("#n_param").val() : '';
		}else if(['dimensions'].indexOf(rule_check)!=-1){
			let darr = [];
			if($("#min_width").val()){
				darr.push('min_width='+$("#min_width").val());
			}
			if($("#max_width").val()){
				darr.push('max_width='+$("#max_width").val());
			}
			if($("#min_height").val()){
				darr.push('min_height='+$("#min_height").val());
			}
			if($("#max_height").val()){
				darr.push('max_height='+$("#max_height").val());
			}
			if($("#width").val()){
				darr.push('width='+$("#width").val());
			}
			if($("#height").val()){
				darr.push('height='+$("#height").val());
			}
			if($("#ratio").val()){
				darr.push('ratio='+$("#ratio").val());
			}
			val = (darr.length) ? rule + ':'+ darr.join(',') : '';
			$(".input-group-addon").css({"width":"100px", "text-align":"left"});
		}else if(['required_if','required_unless'].indexOf(rule_check)!=-1){
			val = ($("#param_field").val()) ? rule + ':'+ $("#param_field").val() + ',' + $("#param2").val() : '';
		}else if(['required_with','required_with_all','required_without','required_without_all'].indexOf(rule_check)!=-1){
			val = ($("#rpfields").html() && $("#rpfields").html()!='none') ? rule + ':'+ $("#rpfields").html() : '';
		}else if(['unique'].indexOf(rule_check)!=-1){
			if($("#param_table").val() && $("#param_field3").val()){
				val = rule + ':'+ $("#param_table").val()+','+$("#param_field3").val() + ($("#except").val() ? ',except,'+$("#except").val() : '');
			}else if($("#param_table").val()){
				val = rule + ':'+ $("#param_table").val() + ($("#except").val() ? ',except,'+$("#except").val() : '');
			}else{
				val = "";
			}
		}else if(['exists'].indexOf(rule_check)!=-1){
			val = rule + ':'+ $("#param_table").val()+','+$("#param_field3").val()
		}else{
			val = rule;
		}

		if(val){
			let index = arr.findIndex(x => x.indexOf(rule)!=-1);
		
			if(index==-1){
				arr.push(val);
			}else{
				if(arr[index] !== val){
					arr.push(val);
				}
				arr.splice(index,1);
			}
			$("#rfields").html(arr.join('|'));
			$("#rid").val(arr.join('|'));
			$("#rperror").hide();
		}else{
			$("#rperror").show();
		}
	}
	var arr2=[];
	function toggleFields(){
		let field = $("#param_field2").val();
		if(arr2.indexOf(field)==-1){
			arr2.push(field);
		}else{
			arr2.splice(arr2.indexOf(field),1);
		}
		$("#rpfields").html(arr2.join(','));
	}
	function deleteRule(id){
		$.post('{{ route('c.query.valid.delete') }}',{'_method':'delete','id':id,'_token':'{{csrf_token()}}'},function (data, status) {
			if(status=='success'){
				$('#r'+id).remove();
				$('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Validation rule was successfully removed.</div>');
			}else{
				$('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Validation rule was not removed.</div>');
			}
		})
	}
</script>

<script>
	var fields = {!! json_encode($fields) !!};
	function fieldsParam(skips = []){
		const op = '<option>%field%</option>'; let html="";
		for (var i = 0; i < fields.length; i++) {
			if(skips.indexOf(fields[i]) == -1){
				html = html + op.replace('%field%',fields[i]);
			}
		};
		$("#param_field").html(html);
	}
	function hideParam(){
		// let hid = ['#param_table', '#date_field','#param_field','#param', '.minmax', '#n_param', '.dimensions', '#no_param', '#rpfields', '#rpf', "#date_value", "#param_field3", "#rperror"];
		let hid = ['#no_param','#date_param','#unique_param','#exists_param','#diff_param','#reqif_param','#reqwith_param','#in_param','#min_param','#mm_param','#dim_param',  "#date_value","#rperror","#param_field3",'#except'];
		for (var i = 0; i < hid.length; i++) {
			$(hid[i]).hide();
		};
	}
	function ruleChange(){
		hideParam();
		let srule = $("#srule").val();
		srule = srule.split('[')[0];
		if(['after','after_or_equal','before','before_or_equal'].indexOf(srule)!=-1){
			$("#date_param").show();
		}else if(['different','same'].indexOf(srule)!=-1){
			fieldsParam();
			$("#diff_param").show();
		}else if(['in_array'].indexOf(srule)!=-1){
			fieldsParam([$("#field").val()]);
			$("#diff_param").show();
		}else if(['date_format','in','mimetypes','mimes','not_in','regex','',].indexOf(srule)!=-1){
			$("#in_param").show();
		}else if(['between','digits_between'].indexOf(srule)!=-1){
			$("#mm_param").show();
			$(".input-group-addon").css({"width":"55px", "text-align":"left"});
		}else if(['digits','max','min','size'].indexOf(srule)!=-1 ){
			$("#min_param").show();
		}else if(['dimensions'].indexOf(srule)!=-1){
			$("#dim_param").show();
			$(".input-group-addon").css({"width":"100px", "text-align":"left"});
		}else if(['required_if','required_unless'].indexOf(srule)!=-1){
			fieldsParam([$("#field").val()]);
			$("#reqif_param").show();
		}else if(['required_with','required_with_all','required_without','required_without_all'].indexOf(srule)!=-1){
			fieldsParam();
			$("#reqwith_param").show();
		}else if(['unique','exists'].indexOf(srule)!=-1){
			$("#unique_param").show();
		}else{
			$("#no_param").show();
		}
	}
	function date_value_show() {
		if($("#date_field").val()=='Date Value'){
			$("#date_value").show();
		}else{
			$("#date_value").hide();
		}
	}
	function refreshTableFields(){
		$.get("{{route('c.db.get.columns')}}", {"table":$("#param_table").val()}, function(data){
			$("#param_field3").html('<option value="" disabled selected>Select Field Name</option>'+data);
			$("#param_field3").show();
			if($("#srule").val() == 'unique'){
				$("#except").show();
			}
		});
	}
</script>


<!-- Modal -->
<div id="addValidationRule" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="rule_title">Add Validation Rule</h4>
      </div>
      <form method="post" action="{{route('c.query.valid.submit')}}" >
	  <input type="hidden" name="_token" value="{{csrf_token()}}" />
	  <input type="hidden" id="rule_id" name="rule_id" />
      <div class="modal-body">
    	<div class="form-group row">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="field">Field:</label>
			</div>
			<div class="col-md-6">
				<select id="field" class="form-control" name="field">
					@foreach($fields as $field)
					<option>{{$field}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Validation Rule:</label>
			</div>
			<div class="col-md-6">
				<input id="rid" type="hidden" class="form-control" name="rule">
				<div class="well well-sm" id="rfields" style="word-break: break-all;">none</div>
				<div class="row">
					<div class="col-md-12">
						<div class="input-group">
							<select class="form-control" id="srule" onchange="ruleChange()">
								@foreach($rules as $rule)
								<option>{{$rule}}</option>
								@endforeach
							</select><a class="btn btn-info input-group-addon" onclick="toggleRule()">Toggle</a>
						</div>
						<p style="color:red" id="rperror">Rule parameters are not set.</p>
					</div>
				</div><br>
			</div>
		</div>
		<div class="form-group row" id="no_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<p>None</p>
			</div>
		</div>
		<div class="form-group row" id="date_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<select id="date_field" class="form-control" onchange="date_value_show()">
					<option value="" disabled selected>Select Date Field Name</option>
					<option>Date Value</option>
					<optgroup label="Date Specifiers">
						<option>yesterday</option>
						<option>today</option>
						<option>tomorrow</option>
					</optgroup>
					<optgroup label="Date Fields">
						@foreach($date_fields as $field)
						<option>{{$field}}</option>
						@endforeach
					</optgroup>
				</select>
				<input type="date" id="date_value" class="form-control">
			</div>
		</div>
		<div class="form-group row" id="unique_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<select id="param_table" class="form-control" onchange="refreshTableFields()">
					<option value="" disabled selected>Select Table Name</option>
					@foreach($tables as $table)
					<option>{{$table}}</option>
					@endforeach
				</select><br>
				<select id="param_field3" class="form-control"></select><br>
				<input type='number' id="except" class="form-control" placeholder="Except Id">
			</div>
		</div>
		{{-- <div class="form-group row" id="exists_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<select id="param_table" class="form-control" onchange="refreshTableFields()">
					<option value="" disabled selected>Select Table Name</option>
					@foreach($tables as $table)
					<option>{{$table}}</option>
					@endforeach
				</select><br>
				<select id="param_field3" class="form-control"></select>
			</div>
		</div> --}}
		<div class="form-group row" id="diff_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<select id="param_field" class="form-control">
					<option value="" disabled selected>Select Field Name</option>
					@foreach($fields as $field)
					<option>{{$field}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group row" id="reqif_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<select id="param_field" class="form-control">
					@foreach($fields as $field)
					<option>{{$field}}</option>
					@endforeach
				</select>
				<input type="text" class="form-control" id="param2"/>
			</div>
		</div>
		<div class="form-group row" id="reqwith_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<div class="well well-sm" id="rpfields" style="word-break: break-all;">none</div>
				<div class="input-group" id="rpf">
					<select id="param_field2" class="form-control">
						@foreach($fields as $field)
						<option>{{$field}}</option>
						@endforeach
					</select><a class="btn btn-info input-group-addon" id="rpt" onclick="toggleFields()">Toggle</a>
				</div>
			</div>
		</div>
		<div class="form-group row" id="in_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<input type="text" class="form-control" id="param"/>
			</div>
		</div>
		<div class="form-group row" id="min_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<input type="number" class="form-control" id="n_param"/>
			</div>
		</div>
		<div class="form-group row" id="mm_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<div class="input-group"><a class="btn input-group-addon">min</a><input type="number" class="form-control" id="min"/></div>
				<div class="input-group"><a class="btn input-group-addon">max</a><input type="number" class="form-control" id="max"/></div>
			</div>
		</div>
		<div class="form-group row" id="dim_param">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<div class="input-group"><a class="btn input-group-addon">min_width</a><input type="number" class="form-control" id="min_width"/></div>
				<div class="input-group"><a class="btn input-group-addon">max_width</a><input type="number" class="form-control" id="max_width"/></div>
				<div class="input-group"><a class="btn input-group-addon">min_height</a><input type="number" class="form-control" id="min_height"/></div>
				<div class="input-group"><a class="btn input-group-addon">max_height</a><input type="number" class="form-control" id="max_height"/></div>
				<div class="input-group"><a class="btn input-group-addon">width</a><input type="number" class="form-control" id="width"/></div>
				<div class="input-group"><a class="btn input-group-addon">height</a><input type="number" class="form-control" id="height"/></div>
				<div class="input-group"><a class="btn input-group-addon">ratio</a><input type="text" class="form-control" id="ratio"/></div>
			</div>
		</div>
		{{-- <div class="form-group row">
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<label for="r">Rule Parameters:</label>
			</div>
			<div class="col-md-6">
				<p id="no_param">None</p>
				<select id="date_field" class="form-control" onchange="date_value_show()">
					<option value="" disabled selected>Select Date Field Name</option>
					<option>Date Value</option>
					<optgroup label="Date Specifiers">
						<option>yesterday</option>
						<option>today</option>
						<option>tomorrow</option>
					</optgroup>
					<optgroup label="Date Fields">
						@foreach($date_fields as $field)
						<option>{{$field}}</option>
						@endforeach
					</optgroup>
				</select>
				<input type="date" id="date_value" class="form-control">
				<select id="param_table" class="form-control" onchange="refreshTableFields()">
					<option value="" disabled selected>Select Table Name</option>
					@foreach($tables as $table)
					<option>{{$table}}</option>
					@endforeach
				</select>
				<select id="param_field3" class="form-control"></select>
				<select id="param_field" class="form-control">
					<option value="" disabled selected>Select Field Name</option>
					@foreach($fields as $field)
					<option>{{$field}}</option>
					@endforeach
				</select>
				<div class="well well-sm" id="rpfields" style="word-break: break-all;">none</div>
				<div class="input-group" id="rpf">
					<select id="param_field2" class="form-control">
						@foreach($fields as $field)
						<option>{{$field}}</option>
						@endforeach
					</select><a class="btn btn-info input-group-addon" id="rpt" onclick="toggleFields()">Toggle</a>
				</div>
				<input type="text" class="form-control" id="param"/>
				<input type="number" class="form-control" id="n_param"/>
				<div class="input-group minmax"><a class="btn input-group-addon">min</a><input type="number" class="form-control" id="min"/></div>
				<div class="input-group minmax"><a class="btn input-group-addon">max</a><input type="number" class="form-control" id="max"/></div>
				<div class="input-group dimensions"><a class="btn input-group-addon">min_width</a><input type="number" class="form-control" id="min_width"/></div>
				<div class="input-group dimensions"><a class="btn input-group-addon">max_width</a><input type="number" class="form-control" id="max_width"/></div>
				<div class="input-group dimensions"><a class="btn input-group-addon">min_height</a><input type="number" class="form-control" id="min_height"/></div>
				<div class="input-group dimensions"><a class="btn input-group-addon">max_height</a><input type="number" class="form-control" id="max_height"/></div>
				<div class="input-group dimensions"><a class="btn input-group-addon">width</a><input type="number" class="form-control" id="width"/></div>
				<div class="input-group dimensions"><a class="btn input-group-addon">height</a><input type="number" class="form-control" id="height"/></div>
				<div class="input-group dimensions"><a class="btn input-group-addon">ratio</a><input type="text" class="form-control" id="ratio"/></div>
			</div>
		</div> --}}
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-default modal-close" name="cmd" value="Add" id="add_cmd"/>
        <input type="submit" class="btn btn-default modal-close" name="cmd" value="Edit" id="edit_cmd"/>
      </div>
      </form>
    </div>

  </div>
</div>

<script>
ruleChange();
$("#edit_cmd").hide();
</script>
@endsection