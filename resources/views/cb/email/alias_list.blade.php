@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('email'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('email')}}</div>@endif
  <div class="row">
    <div class="col-md-6">
      My Alias List | for the user id: {{\Auth::user()->id}}
    </div>
    <div class="col-md-6">
        <div class="btn-group" style="float:right;position: relative;">
          <button class="btn btn-default" data-toggle="modal" data-target="#newAlias">Add New Alias Email</button>
          <a class="btn btn-default" href="{{route('c.mail.list.view')}}">Back</a>
        </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr</th>
						<th>Alias Email</th>
            <th>Verification Status</th>
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
            <td><a href="JavaScript:void(0);" onclick="d('{{$alias->id}}')">Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$aliases->appends(request()->input())->links()}}
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


<!-- Modal -->
<div id="newAlias" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form method="post" action="{{route('c.alias.add.new')}}">
      <input type="hidden" name="_token" value="{{csrf_token()}}" />
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter alias name</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="user@gmail.com">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default">Add</button>
      </div>
      </form>
    </div>

  </div>
</div>
@endsection