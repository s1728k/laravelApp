@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>Files | for the app id: {{\Auth::user()->active_app_id}}<div class="btn-group" style="float:right">
          <form id="uploadFiles" method="post" action="{{route('c.files.upload.files')}}" enctype="multipart/form-data" style="display: none;">
              <input type="hidden" name="_token" value="{{csrf_token()}}">
              <input type="hidden" name="success" />
              <input type="file" name="files[]" id="filesUpload" multiple onchange="$('#uploadFiles').submit()">
          </form><label for="filesUpload"><a class="btn btn-default">Upload Files</a></label></caption>
				<thead>
					<tr>
						<th>Sr</th>
            <th>Id</th>
						<th>File Name</th>
            <th>File Type</th>
            <th>File Size</th>
            <th>File Path</th>
            <th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($files as $key => $file)
          <tr id="r{{$file->id}}">
            <td>{{ ($key + 1) }}</td>
            <td>{{ $file->id }}</td>
            <td>{{ $file->name }}</td>
            <td>{{ $file->mime }}</td>
            <td>{{ $file->size }}</td>
            <td>{{ str_replace(env('APP_URL'), '', $file->path) }}</td>
            <td><a style="cursor:pointer" href="{{$file->path}}">Preview</a></td>
            <td><a style="cursor:pointer" href="{{route('c.files.download',['id'=>$file->id])}}">Download</a></td>
            <td><a style="cursor:pointer" onclick="deleteFile('{{$file->id}}','{{$file->name}}')">Delete</a></td>
            @if(false)
            <td><label for="file" class="link"><a onclick="replaceFile('{{$file->id}}','{{$file->name}}')">Replace</a></label></td>
            <td><form id="replaceFile{{($key + 1)}}" method="post" action="{{route('c.files.replace')}}" enctype="multipart/form-data" style="display: none;">
                          <input type="hidden" name="_token" value="{{csrf_token()}}">
                          <input type="hidden" name="id" value="{{$file->id}}">
                          <input type="hidden" name="success" />
                          <input type="file" name="file" id="file{{($key + 1)}}" onchange="$('#replaceFile{{($key + 1)}}').submit()">
                      </form>
              <label for="file{{($key + 1)}}" class="link"><a>Replace</a></label></td>
            <td><form id="delfile{{($key + 1)}}" method="post" action="{{route('c.files.delete')}}" style="display: none;">
                          <input type="hidden" name="_token" value="{{csrf_token()}}">
                          <input type="hidden" name="id" value="{{$file->id}}">
                          <input type="hidden" name="success" />
                      </form>
              <label class="link"><a onclick="$('#delfile{{($key + 1)}}').submit()">Delete</a></label></td>
            @endif
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$files->links()}}
      @if(false)
      <form id="replaceFile" method="post" action="{{route('c.files.replace')}}" enctype="multipart/form-data" style="display: none;">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <input type="hidden" name="id" id="id">
          <input type="hidden" name="success" />
          <input type="file" name="file" id="file" onchange="$('#replaceFile').submit()">
      </form>
      @endif
		</div>
	</div>
</div>
<script>
  @if(false)
  function replaceFile(id, file_name){
    // var bool = confirm("Are you sure! you want to replace file " + file_name);
    // if(!bool){
    //   return;
    // }
    $('#id').val(id);
  }
  @endif
  function deleteFile(id, file_name){
    var bool = confirm("Are you sure! you want to remove file " + file_name);
    if(!bool){
      return;
    }
    $.post("{{route('c.files.delete')}}", {'_token':'{{csrf_token()}}', 'id':id}, function(data){
      console.log(data);
      if(data['status'] == 'success'){
        $('#r'+id).remove();
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> File '+file_name+' was successfully removed.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> File '+file_name+' was not removed.</div>');
      }
    });
  }
</script>

@endsection