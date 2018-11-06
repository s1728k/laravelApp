@extends("cm.layouts.app")

@section("content")
<div class="row">
  <div class="col s12 m4">
    CRUD Table "{{$table}}"
  </div>
  <div class="col s12 m8">
    <div class="input-group" style="float:right;">
		<a class="waves-effect waves-light btn blue darken-2" href="{{route('c.db.add.record')}}?table={{$table}}">Add New Record</a>
		<a class="waves-effect waves-light btn blue darken-2" href="{{route('c.my.table.list')}}">Back</a>
	</div>
  </div>
</div>
<div class="row">
	<div class="col s12">
		<table class="responsive-table">
			<thead>
				<form method="get" action="{{route('c.db.crud.table')}}">
					<input type='hidden' class="form-control" name="table" value="{{$table}}">
				<tr>
					@foreach($td as $k => $v)
						@if(strpos($v->Type,'enum')!==false)
						<th>
							<select class="form-control" name="{{$v->Field}}">
								@foreach(explode(',', str_replace(['enum(',')',"'",' '],['','','',''],$v->Type)) as $value)
								<option>{{$value}}</option>
								@endforeach
							</select>
						</th>
						@else
						<th><input type='{{$inpTyp[$v->Type]}}' class="form-control" name="{{$v->Field}}" placeholder="{{$v->Field}}"></th>
						@endif
					@endforeach
					<th colspan="2"><button type="submit" class="waves-effect waves-light btn blue darken-2">Search</button></th>
				</tr>
				</form>
			</thead>
			<tbody>
				@foreach($records as $record)
				<tr>
					@foreach($td as $k => $v)
					<td>{{$record[$v->Field]}}</td>
					@endforeach
					<td><a href="{{route('c.db.edit.record')}}?table={{$table}}&id={{$record->id??''}}" >Edit</a></td>
					<td><a href="{{ route('c.db.delete.record') }}" onclick="event.preventDefault();
                         document.getElementById('delete-form').submit();">Delete</a>
                         <form id="delete-form" action="{{ route('c.db.delete.record') }}" method="POST" style="display: none;">
			                    <input type="hidden" name="_token" value="{{csrf_token()}}">
			                    <input type="hidden" name="table" value="{{$table}}">
			                    <input type="hidden" name="id" value="{{$record['id']}}">
			                </form>
                     </td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{$records->appends(request()->except('page'))->links()}}
	</div>
</div>
@endsection