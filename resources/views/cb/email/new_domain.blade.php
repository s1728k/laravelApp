@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 text-center">
			<caption class="">
			<div class="input-group" style="float:right;">
				<a class="btn btn-default" href="{{route('c.email.list.view')}}">Back</a></div></caption>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<form method="post" action="{{route('c.email.new.domain.submit')}}" >
		        <input type="hidden" name="_token" value="{{csrf_token()}}" />

				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="name">Domain Name:</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="example.com">
						@if($errors->has('name'))
							<p style="color:red">{{$errors->first('name')}}</p> 
						@endif
					</div>			
				</div>

				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4"></div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-primary">Add New Domain</button>
					</div>			
				</div>
		    </form>
		</div>
	</div>
</div>
@endsection