@extends("cm.layouts.app")

@section("content")
<div class="row">
  <div class="col s12 m2">
    App Origins | 
  </div>
  <div class="col s12 m2">
    selected app - id: {{$id}} 
  </div>
  <div class="col s12 m8">
    <div class="btn-group" style="float:right">
        <button class="waves-effect waves-light btn blue darken-2 modal-trigger" href="#addNewOrigin">Add New Origin</button><a class="waves-effect waves-light btn blue darken-2" href="{{route('c.app.list.view')}}">Back</a></div>
  </div>
</div>
<div class="row">
	<div class="col s12">
		<table class="responsive-table">
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
<script>
  function deleteOrigin(name, i){
    $.post("{{route('c.app.delete.origin', ['id'=>$id])}}",{"_token":"{{csrf_token()}}","name":name}, function(status){
      if(status=='success'){
        $("#r"+String(i)).remove();
        var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> App Origin has been deleted successfully!</div>';
        M.toast({html: ht})
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
        <h4 class="modal-title">Add New Origin</h4>
      </div>
      <form method="post" action="{{ route('c.app.new.origin.submit', ['id'=>$id]) }}" >
      <div class="modal-body">
          <input type="hidden" name="_token" value="{{csrf_token()}}" />
          <div class="form-group">
            <label>Domain Name</label>
            <input type="text" name="name" class="form-control" placeholder="Domain Name">
          </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="waves-effect waves-light btn blue darken-2">Add</button>
          <button type="button" class="modal-close waves-effect waves-light btn blue darken-2">Cancel</button>
      </div>
      </form>
    </div>

  </div>
</div>
@endsection