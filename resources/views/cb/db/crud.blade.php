@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div id="alrt"></div>
	<div class="row">
		<div class="col-md-6">
			CRUD Table "{{$table}}"
		</div>
		<div class="col-md-6">
			<div class="btn-group" style="float:right;">
				<a class="btn btn-default" href="{{route('c.db.add.record')}}?table={{$table}}">Add New Record</a>
				<a class="btn btn-default" href="{{route('c.table.list.view')}}">Back</a>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive" style="padding-bottom: 100px;">
				<table class="table">
					<thead>
						<tr>
							@foreach($td as $k => $v)
							<th>{{$v->Field}}</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach($records as $record)
						<tr id="r{{$record->id}}">
							@foreach($td as $k => $v)
							<td style="word-break: break-all;">{{$record[$v->Field]}}</td>
							@endforeach
							<td><a href="{{route('c.db.edit.record')}}?table={{$table}}&id={{$record->id??''}}" >Edit</a></td>
							<td><a style="cursor: pointer;" onclick="d('{{$record->id}}', '{{$table}}')">Delete</a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
				{{$records->appends(request()->input())->links()}}
			</div>
		</div>
	</div>
</div>
<script>
  function d(id, table){
    $.post("{{ route('c.db.delete.record') }}", {"_token":"{{csrf_token()}}", "id":id, "table":table, "_method":"DELETE"}, function(data) {
      if(data['status'] == 'success'){
        $('#r'+id).remove();
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Record was successfully removed.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Record was not removed.</div>');
      }
    })
  }
</script>
@endsection