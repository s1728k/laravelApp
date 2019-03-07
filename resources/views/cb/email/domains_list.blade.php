@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>My Domains List | for the user id: {{\Auth::user()->id}}
          <form method="post" action="{{route('c.domain.add.new')}}">
          <div class="input-group" style="float:right;position: relative;">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
              <input style="width:300px;" type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="ex:- example.com" />@if($errors->has('name'))<p style="color:red;position: absolute;bottom:auto;left:0px;top:30px;right:auto;"> {{$errors->first('name')}} </p>@endif
          <button class="btn btn-default">Add New Domain Name</button>
          <a class="btn btn-default" href="{{route('c.email.list.view')}}">Back</a></div></form></div></caption>
				<thead>
					<tr>
						<th>Sr</th>
						<th>Domain Name</th>
            <th>Verification Status</th>
            <th>Expiry Date</th>
            <th>Delete</th>
					</tr>
				</thead>
				<tbody>
          @foreach($domains as $key => $domain)
          <tr id="r{{$domain->id}}">
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{ $domain->name }}</td>
            @if($domain->verified == 'done')
            <td>verified</td>
            @else
            <td><a href="{{ route('c.domain.verify', ['id' => $domain->id]) }}">verify now</a></td>
            @endif
            <td>{{ $domain->expiry_date }}</td>
            <td><a style="cursor:pointer" onclick="d('{{$domain->id}}')">Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{-- {{$domains->appends(request()->input())->links()}} --}}
      {{$domains->links()}}
		</div>
	</div>
</div>

<script>
  function d(id) {
    $.post("{{ route('c.domain.delete') }}", {'_token':"{{csrf_token()}}", "id":id, '_method':"DELETE"}, function (data) {
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