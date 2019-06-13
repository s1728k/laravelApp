@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  	<div id="alrt"></div>
	<div class="row">
		<div class="col-md-6">
			Validation Rules | for the app id = {{\Auth::user()->active_app_id}}
		</div>
		<div class="col-md-6">
			<div class="btn-group" style="float:right;">
				<a class="btn btn-default" onclick="showDialog()">Add Validation Rule</a>
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
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($frules as $frule)
					<tr id="r{{$frule->id}}">
						<td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
						<td>{{$frule->field}}</td>
						<td>{{$frule->rule}}</td>
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
	function showDialog(){
		$("#addValidationRule").modal();
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

<!-- Modal -->
<div id="addValidationRule" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Validation Rule For Field</h4>
      </div>
      <form method="get" action="{{route('c.query.valid.view')}}" >
      <div class="modal-body">
    	<div class="form-group row">
			<div class="col-md-12">
				<select id="field" class="form-control" name="field">
					@foreach($fields as $field)
					<option>{{$field}}</option>
					@endforeach
				</select>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default modal-close">Go</button>
      </div>
      </form>
    </div>

  </div>
</div>

@endsection