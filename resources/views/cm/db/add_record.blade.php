@extends("cm.layouts.app")

@section("content")
<div class="row">
  <div class="col s12 m4">
    Add new record for table | "{{$table}}"
  </div>
  <div class="col s12 m8">
    <div class="input-group" style="float:right;">
	<a class="waves-effect waves-light btn blue darken-2" href="{{route('c.db.crud.table')}}?table={{$table}}">Back</a></div>
  </div>
</div>
<div class="row">
	<div class="col s12 m4 offset-m4">
		<form method="post" action="{{route('c.db.add.record.submit')}}" >
	        <input type="hidden" name="_token" value="{{csrf_token()}}" />
	        <input type="hidden" name="table" value="{{$table}}" />
	        @foreach($td as $k => $v)
	        @empty($isTA[$v->Type])
				@if(strpos($v->Type,'enum')!==false)
				<div class="row">
                    <div class="input-field col s12">
						<select id="{{$v->Field}}" class="validate" name="{{$v->Field}}">
							@foreach(explode(',', str_replace(['enum(',')',"'",' '],['','','',''],$v->Type)) as $value)
							<option>{{$value}}</option>
							@endforeach
						</select>
						<label for="{{$v->Field}}">{{$v->Field}}:</label>
						{{-- <input id="{{$v->Field}}" type="text" class="validate" name="{{$v->Field}}" value="{{ old($v->Field) }}" > --}}
						@if($errors->has($v->Field))
								<p style="color:red">{{$errors->first($v->Field)}}</p> @endif
					</div>			
				</div>
				<script>
	                $("#{{$v->Field}}").val('{{ old($v->Field) }}');
	            </script>
				@else
					@empty($step[$v->Type])
					<div class="row">
                    	<div class="input-field col s12">
							<input id="{{$v->Field}}" type="{{$inpTyp[$v->Type]}}" class="validate" name="{{$v->Field}}" value="{{ old($v->Field) }}" >
							<label for="{{$v->Field}}">{{$v->Field}}:</label>
							@if($errors->has($v->Field))
									<p style="color:red">{{$errors->first($v->Field)}}</p> @endif
						</div>			
					</div>
						@if($v->Field == 'password')
						<div class="row">
                    		<div class="input-field col s12">
								<input id="confirm_password" type="{{$inpTyp[$v->Type]}}" class="validate" name="confirm_password" value="{{ old($v->Field) }}" >
								<label for="confirm_password">confirm_password:</label>
							</div>			
						</div>
						@endif
					@else
					<div class="row">
                    	<div class="input-field col s12">
							<input id="{{$v->Field}}" type="{{$inpTyp[$v->Type]}}" class="validate" name="{{$v->Field}}" value="{{ old($v->Field) }}"   step="{{$step[$v->Type]}}">
							<label for="{{$v->Field}}">{{$v->Field}}:</label>
							@if($errors->has($v->Field))
									<p style="color:red">{{$errors->first($v->Field)}}</p> @endif
						</div>			
					</div>
					@endempty
				@endif
			@else
				<div class="row">
                    <div class="input-field col s12">
						<textarea id="{{$v->Field}}" type="text" rows="{{$isTA[$v->Type]}}" class="validate" name="{{$v->Field}}"></textarea>
						<label for="{{$v->Field}}">{{$v->Field}}:</label>
						@if($errors->has($v->Field))
								<p style="color:red">{{$errors->first($v->Field)}}</p> @endif
					</div>			
				</div>
				<script>
	                $("#{{$v->Field}}").val('{{ str_replace(array("\r", "\n", '\n\n'), '\n', old($v->Field)) }}');
	            </script>
			@endempty
			@endforeach
			<div class="row">
                <div class="input-field col s12">
					<button type="submit" class="waves-effect waves-light btn blue darken-2">Add New Record</button>
				</div>			
			</div>
	    </form>
	</div>
</div>
@endsection