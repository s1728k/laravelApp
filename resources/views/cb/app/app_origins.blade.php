@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('name'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('name')}}</div>@endif
  <div class="row">
    <div class="col-md-6">
      App Origins | for app id: {{$id}} 
    </div>
    <div class="col-md-6">
      <div class="btn-group" style="float:right"> 
        <button class="btn btn-default" data-toggle="modal" data-target="#addNewOrigin">Add New Origin</button>
        <a class="btn btn-default" href="{{route('c.app.list.view')}}">Back</a>
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>For website application add origin website name / for web servers add ip address / for all applications add * </caption>
				<thead>
					<tr>
						<th>Sr</th>
						<th>Origin</th>
						<th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($origins as $origin)
          <tr id="r{{$loop->index+1}}">
            <td>{{ ($loop->index + 1) }}</td>
            <td>{{$origin}}</td>
            <td><a href="JavaScript:void(0);" onclick="deleteOrigin('{{$origin}}', '{{$loop->index+1}}')">Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
  function deleteOrigin(name, i){
    $.post("{{route('c.app.delete.origin', ['id'=>$id])}}",{"_token":"{{csrf_token()}}","name":name,"_method":"delete"}, function(data){
      if(data['status'] == 'success'){
        $('#r'+i).remove();
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Origin was successfully removed.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Origin was not removed.</div>');
      }
    });
  }
</script>
<!-- Modal -->
<div id="addNewOrigin" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Origin</h4>
      </div>
      <form method="post" action="{{ route('c.app.new.origin.submit', ['id'=>$id]) }}" >
      <input type="hidden" name="_token" value="{{csrf_token()}}" />
      <div class="modal-body">
          <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="Website Address / IP Address">
          </div>
      </div>
      <div class="modal-footer">
        <div class="form-group"><button type="submit" class="btn btn-default">Add</button></div>
      </div>
      </form>
    </div>

  </div>
</div>

@endsection