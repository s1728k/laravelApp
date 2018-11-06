@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive" style="padding-bottom: 100px;">
				<table class="table">
					<caption>
						CRUD Table "{{$table}}"
						<div class="input-group" style="float:right;">
							<a class="btn btn-default" href="{{route('c.db.add.record')}}?table={{$table}}">Add New Record</a>
							<a class="btn btn-default" href="{{route('c.my.table.list')}}">Back</a>
						</div></caption>
					<thead>
						<form method="get" action="{{route('c.db.crud.table')}}">
							<input type='hidden' class="form-control" name="table" value="{{$table}}">
						<tr>
							@foreach($td as $k => $v)
								@if(strpos($v->Type,'enum')!==false)
								<th style="min-width: 100px;">
									<select class="form-control" name="{{$v->Field}}">
										@foreach(explode(',', str_replace(['enum(',')',"'",' '],['','','',''],$v->Type)) as $value)
										<option>{{$value}}</option>
										@endforeach
									</select>
								</th>
								@else
								<th style="min-width: 100px;"><input type='{{$inpTyp[$v->Type]}}' class="form-control" name="{{$v->Field}}" placeholder="{{$v->Field}}"></th>
								@endif
							@endforeach
							<th colspan="2"><button type="submit" class="btn btn-primary">Search</button></th>
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
	</div>
</div>
@endsection