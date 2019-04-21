@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>App Origins | for app id: {{$id}} <div class="btn-group" style="float:right"> <button class="btn btn-default" data-toggle="modal" data-target="#addNewOrigin">Add New Origin</button><a class="btn btn-default" href="{{route('c.app.list.view')}}">Back</a></caption>
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
            <td><a style="cursor:pointer" onclick="deleteOrigin('{{$origin}}', '{{$loop->index+1}}')">Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
  function deleteOrigin(name, i){
    $.post("{{route('c.app.delete.origin', ['id'=>$id])}}",{"_token":"{{csrf_token()}}","name":name}, function(data){
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
      <div class="modal-body">
        <form method="post" action="{{ route('c.app.new.origin.submit', ['id'=>$id]) }}" >
          <input type="hidden" name="_token" value="{{csrf_token()}}" />
          <div class="form-group">
            <label>Domain Name</label>
            <input type="text" name="name" class="form-control" placeholder="Domain Name">
          </div>
          <div class="form-group"><button type="submit" class="btn btn-primary">Add</button></div>
        </form>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>
@endsection