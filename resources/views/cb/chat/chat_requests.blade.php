@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  <div class="row">
    <div class="col-md-6">
      Can Requests | for the app id: {{\Auth::user()->active_app_id}}
    </div>
    <div class="col-md-6">
      <div class="btn-group" style="float:right"> 
        <a class="btn btn-default" href="{{route('c.chat.messages')}}">Back</a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Sr</th>
            <th>fid</th>
            <th>fap</th>
            <th>fname</th>
            <th>tid</th>
            <th>tap</th>
            <th>tname</th>
            <th>status</th>
            <th>updated_at</th>
            <th colspan="2">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($requests as $request)
          <tr id="r{{$request->id}}">
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{$request->fid}}</td>
            <td>{{$request->fap}}</td>
            <td>{{$request->fname}}</td>
            <td>{{$request->tid}}</td>
            <td>{{$request->tap}}</td>
            <td>{{$request->tname}}</td>
            <td>{{$request->status}}</td>
            <td>{{$request->updated_at}}</td>
            <td><a style="cursor: pointer;" onclick="updateMsgDialog('{{$request->id}}','','','{{$request->status}}')">Update</a></td>
            <td><a style="cursor: pointer;" onclick="delMsg('{{$request->id}}')">Delete</a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{$requests->appends(request()->input())->links()}}
    </div>
  </div>
</div>
<script>
  function updateMsgDialog(id, msg, style, status){
  	$("#chat_id").val(id);
  	$("#message").val(msg);
  	$("#style").val(style);
  	$("#status").val(status);
  	$("#updateChat").modal();
  }
  function updateMsg(){
  	$.post('{{ route('c.chat.message.update') }}',{"_token":"{{csrf_token()}}","id":$("#chat_id").val(),"_method":"put","message":$("#message").val(),"style":$("#style").val(),"status":$("#status").val(), 'cmd':'status_only' },function(data, status){
  		if(status == 'success'){
  			location.replace(window.location.href);
  		}
  	});
  }
  function delMsg(id){
    var bool = confirm("Are you sure! you want to remove Chat Message ");
    if(!bool){
      return;
    }
    $.post('{{route('c.chat.message.delete')}}', {"id":id,"_token":"{{csrf_token()}}","_method":"delete"}, function(data, status){
      if(status == 'success'){
        $('#r'+id).remove();
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Chat Message was successfully removed.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Chat Message was not removed.</div>');
      }
    })
  }
</script>

<!-- Modal -->
<div id="updateChat" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Status</h4>
      </div>
      <div class="modal-body">
      	<input type="hidden" name="_token" value="{{csrf_token()}}" />
				<input type="hidden" name="_method" value="PUT" />
				<input type="hidden" name="id" id="chat_id" />
        <div class="form-group">
          <label>Status</label>
          <select class="form-control" id="status">
          	<option>waiting</option><option>chatting</option><option>closed</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="updateMsg()">Update</button>
      </div>
    </div>

  </div>
</div>
@endsection