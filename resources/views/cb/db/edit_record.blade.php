@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 text-center">
			Edit record for table "{{$table}}"
			<div class="input-group" style="float:right;">
				<a class="btn btn-default" href="{{route('c.db.crud.table')}}?table={{$table}}">Back</a></div>
		</div>
	</div><hr>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<form method="post" action="{{route('c.db.edit.record.submit')}}" >
		        <input type="hidden" name="_token" value="{{csrf_token()}}" />
		        <input type="hidden" name="table" value="{{$table}}" />
		        <input type="hidden" name="id" value="{{$record->id}}" />
		        @foreach($td as $k => $v)
		        	@if($v->Key=='PRI')
					<div class="form-group row">
						<div class="col-md-1"></div>
						<div class="col-md-4">
							<label>{{$v->Field}}:</label>
						</div>
						<div class="col-md-6">
							{{$record[$v->Field]}}
						</div>			
					</div>
					@continue
					@endif
					@if(strpos($v->Type,'enum')!==false)
					<div class="form-group row">
						<div class="col-md-1"></div>
						<div class="col-md-4">
							<label for="{{$v->Field}}">{{$v->Field}}:</label>
						</div>
						<div class="col-md-6">
							<select class="form-control" name="{{$v->Field}}">
								@foreach(explode(',', str_replace(['enum(',')',"'",' '],['','','',''],$v->Type)) as $value)
								<option>{{$value}}</option>
								@endforeach
							</select>
							@if($errors->has($v->Field))
									<p style="color:red">{{$errors->first($v->Field)}}</p> @endif
						</div>			
					</div>
					@else
					<div class="form-group row">
						<div class="col-md-1"></div>
						<div class="col-md-4">
							<label for="{{$v->Field}}">{{$v->Field}}:</label>
						</div>
						<div class="col-md-6">
							<input type="{{$inpTyp[$v->Type]}}" class="form-control" name="{{$v->Field}}" value="{{$record[$v->Field]}}">
							@if($errors->has($v->Field))
									<p style="color:red">{{$errors->first($v->Field)}}</p> @endif
						</div>			
					</div>
					@endif
				@endforeach
		        <div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4"></div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-primary">Edit Record</button>
					</div>			
				</div>
		      </form>
		</div>
	</div>
</div>
@endsection