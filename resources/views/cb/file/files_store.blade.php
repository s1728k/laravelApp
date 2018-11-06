@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>Files | for the app id: {{\Auth::user()->active_app_id}}<div class="btn-group" style="float:right">
          <a class="btn btn-default" onclick="uploadFile()">Upload File</a>
          <a class="btn btn-default" onclick="uploadFiles()">Upload Files</a></caption>
				<thead>
					<tr>
						<th>Sr</th>
						<th>File Name</th>
            <th>File Type</th>
            <th>File Size</th>
            <th>Pivot Table</th>
            <th>Pivot Column</th>
            <th>Pivot Id</th>
            <th>Sr No</th>
            <th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($files as $key => $file)
          <tr id="r{{($key + 1)}}">
            <td>{{ ($key + 1) }}</td>
            <td>{{ $file->name }}</td>
            <td>{{ $file->mime }}</td>
            <td>{{ $file->size }}</td>
            <td>{{ $file->pivot_table }}</td>
            <td>{{ $file->pivot_field }}</td>
            <td>{{ $file->pivot_id }}</td>
            <td>{{ $file->sr_no }}</td>
            <td><a>Preview</a></td>
            <td><a style="cursor:pointer" href="/files/{{$file->pivot_table}}/{{$file->pivot_field}}/{{$file->pivot_id}}/{{$file->sr_no}}">Download</a></td>
            <td><form id="replaceFile{{($key + 1)}}" method="post" action="{{route('c.files.replace')}}" enctype="multipart/form-data" style="display: none;">
                          <input type="hidden" name="_token" value="{{csrf_token()}}">
                          <input type="hidden" name="pivot_table" value="{{$file->pivot_table}}">
                          <input type="hidden" name="pivot_field" value="{{$file->pivot_field}}">
                          <input type="hidden" name="pivot_id" value="{{$file->pivot_id}}">
                          <input type="hidden" name="sr_no" value="{{$file->sr_no}}">
                          <input type="hidden" name="success" />
                          <input type="file" name="file" id="file{{($key + 1)}}" onchange="$('#replaceFile{{($key + 1)}}').submit()">
                      </form>
              <label for="file{{($key + 1)}}" class="link"><a>Replace</a></label></td>
            <td><a>Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$files->links()}}
		</div>
	</div>
</div>

<script>
  function uploadFile(){
    $("#mt").html("Upload File For Reference");
    $("#file_div").html($("#file_div_s").html());
    $("#uploadFile").modal();
  }
  function uploadFiles(){
    $("#mt").html("Upload Files For Reference");
    $("#files_div").html($("#files_div_s").html());
    $("#uploadFileForm").attr('action', "{{route('c.files.upload.files')}}");
    $("#uploadFile").modal();
  }
  function getFields(){
    $.get("{{route('c.db.get.columns')}}", {"table":$("#pivot_table").val()}, function(data, status){
      if(status = "success"){
        $("#pivot_field").html(data);
      }
    });
  }
</script>

<!-- Modal -->
<div id="uploadFile" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="uploadFileForm" method="post" action="{{route('c.files.upload.file')}}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="mt">Upload File For Reference </h4>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-md-4">
              <label for="pivot_table">Pivot Table</label>
            </div>
            <div class="col-md-6">
              <select id="pivot_table" name="pivot_table" class="form-control" onchange="getFields()" autofocus>
                @foreach($tables as $table)
                <option>{{$table}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-4">
              <label for="pivot_field">Pivot Field</label>
            </div>
            <div class="col-md-6">
              <select id="pivot_field" name="pivot_field" class="form-control">
                @foreach($fields as $field)
                <option>{{$field}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-4">
              <label for="pivot_id">Pivot Id</label>
            </div>
            <div class="col-md-6">
              <input id="pivot_id" type="number" class="form-control" name="pivot_id" required/>
            </div>
          </div>

          <div class="form-group row" id="file_div"></div>
          <div class="form-group row" id="files_div"></div>
          <input type="hidden" name="success" />
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default">Upload</button>
        </div>
      </form>
    </div>

  </div>
</div>

<div style="display: none">
  <div class="form-group row" id="file_div_s">
    <div class="col-md-4">
      <label for="file">File Selected</label>
    </div>
    <div class="col-md-8">
      <input id="file" type="file" class="form-control" name="file" required/>
    </div>
  </div>

  <div class="form-group row" id="files_div_s">
    <div class="col-md-4">
      <label for="files">Files Selected</label>
    </div>
    <div class="col-md-8">
      <input id="files" type="file" class="form-control" name="files[]" multiple required/>
    </div>
  </div>
</div>
@endsection