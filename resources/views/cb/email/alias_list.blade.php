@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>My Alias List | for the user id: {{\Auth::user()->id}}
          <form method="post" action="{{route('c.alias.add.new')}}">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
          <div class="input-group" style="float:right;position: relative;">
              <input style="width:300px;" type="text" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="Alias Email Address" />@if($errors->has('email'))<p style="color:red;position: absolute;bottom:auto;left:0px;top:30px;right:auto;"> {{$errors->first('email')}} </p>@endif
          <button class="btn btn-default">Add New Alias Email</button>
          <a class="btn btn-default" href="{{route('c.email.list.view')}}">Back</a></div></form></caption>
				<thead>
					<tr>
						<th>Sr</th>
						<th>Alias Email</th>
            <th>Verification Status</th>
            <th>Expiry Date</th>
            <th>Delete</th>
					</tr>
				</thead>
				<tbody>
          @foreach($aliases as $key => $alias)
          <tr id="r{{$alias->id}}">
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{ $alias->email }}</td>
            @if($alias->verified == 'done')
            <td>verified</td>
            @else
            <td id="v{{$alias->id}}"><a style="cursor: pointer;" onclick="vc('{{$alias->id}}')">verify code</a></td>
            @endif
            <td>{{ $alias->expiry_date }}</td>
            <td><a style="cursor:pointer" onclick="d('{{$alias->id}}')">Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{-- {{$aliases->appends(request()->input())->links()}} --}}
      {{$aliases->links()}}
		</div>
	</div>
</div>

<script>
  function vc(id){
    var code = prompt("Enter the 6 digit varification code");
    $.post("{{ route('c.alias.verify') }}", {"_token":"{{csrf_token()}}", "id":id, "code":code}, function (data) {
      if(data['status'] == 'success'){
        $('#v'+id).html('verified');
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Alias email address was successfully verified.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Alias email address was not verified.</div>');
      }
    })
  }
  function d(id) {
    $.post("{{ route('c.alias.delete') }}", {'_token':"{{csrf_token()}}", "id":id, '_method':"DELETE"}, function (data) {
      if(data['status'] == 'success'){
        $('#r'+id).remove();
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Alias email address was successfully deleted.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Alias email address was not deleted.</div>');
      }
    })
  }
</script>
@endsection