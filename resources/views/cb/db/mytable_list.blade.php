@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('table'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('table')}}</div>@endif
  @if($errors->has('createCSV'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('createCSV')}}</div>@endif
  @if($errors->has('updateCSV'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('updateCSV')}}</div>@endif
  @if($errors->has('_token'))<div class="alert alert-success"><strong>Success!</strong> {{$errors->first('_token')}}</div>@endif
  <div class="row">
    <div class="col-md-6">
      <div class="well well-sm"> Table List | for the app id: {{\Auth::user()->active_app_id}}, Space Used: {{$size}} MB</div>
    </div>
    <div class="col-md-6">
      <div class="btn-group" style="float:right">
        <a class="btn btn-default" onclick="createTable()">Create New Table</a>
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12">
      <div class="well well-sm table-responsive">
  			<table class="table">
  				<thead>
  					<tr>
  						<th>Sr</th>
  						<th>Table Name</th>
              <th>Size (MB)</th>
              <th colspan="9">Actions</th>
              <th colspan="2">Export</th>
              <th colspan="2">Import - Create</th>
              <th colspan="2">Import - Update</th>
  					</tr>
  				</thead>
  				<tbody>
            @foreach($tables as $key => $table)
            <tr id="r{{($key + 1)}}">
              <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
              <td>{{ $table['name'] }}</td>
              <td>{{ $table['size'] }}</td>
              <td><a href="JavaScript:void(0);" onclick="addFields('{{$table['name']}}')">Add Fields</a></td>
              <td><a href="JavaScript:void(0);" onclick="renameField('{{$table['name']}}')">Rename Field</a></td>
              <td><a href="JavaScript:void(0);" onclick="deleteField('{{$table['name']}}')">Delete Field</a></td>
              <td><a href="JavaScript:void(0);" onclick="addIndex('{{$table['name']}}')">Add Index</a></td>
              <td><a href="JavaScript:void(0);" onclick="removeIndex('{{$table['name']}}')">Remove Index</a></td>
              <td><a href="{{route('c.db.crud.table')}}?table={{$table['name']}}">CRUD</a></td>
              <td><a href="JavaScript:void(0);" onclick="renameTable('{{$table['name']}}', {{$key}})" >Rename Table</a></td>
              <td><a href="JavaScript:void(0);" onclick="truncate('{{$table['name']}}', {{$key}})" >Truncate Table</a></td>
              <td><a href="JavaScript:void(0);" onclick="deleteTable('{{$table['name']}}', {{$key}})" >Delete Table</a></td>
              <td><a href="{{route('c.csv.export', ['table'=>$table['name']])}}">CSV</a></td>
              <td><a href="{{route('c.json.export', ['table'=>$table['name']])}}">JSON</a></td>

              <td>
                <form id="createCSV{{($key + 1)}}" method="post" action="{{route('c.csv.import.create')}}" enctype="multipart/form-data" autocomplete="off" style="display: none;">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="table" value="{{$table['name']}}">
                            <input type="file" name="createCSV" id="ccf{{($key + 1)}}" onchange="$('#createCSV{{($key + 1)}}').submit()">
                        </form>
                <label for="ccf{{($key + 1)}}" class="link"><a>CSV</a></label>
              </td>
              <td>
                <form id="createJSON{{($key + 1)}}" method="post" action="{{route('c.json.import.create')}}" enctype="multipart/form-data" autocomplete="off" style="display: none;">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="table" value="{{$table['name']}}">
                            <input type="file" name="createJSON" id="cjf{{($key + 1)}}" onchange="$('#createJSON{{($key + 1)}}').submit()">
                        </form>
                <label for="cjf{{($key + 1)}}" class="link"><a>JSON</a></label>
              </td>

              <td>
                <form id="updateCSV{{($key + 1)}}" method="post" action="{{route('c.csv.import.update')}}" enctype="multipart/form-data" autocomplete="off" style="display: none;">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="table" value="{{$table['name']}}">
                            <input type="file" name="updateCSV" id="ucf{{($key + 1)}}" onchange="$('#updateCSV{{($key + 1)}}').submit()">
                        </form>
                <label for="ucf{{($key + 1)}}" class="link"><a>CSV</a></label>
              </td>
              <td>
                <form id="updateJSON{{($key + 1)}}" method="post" action="{{route('c.json.import.update')}}" enctype="multipart/form-data" autocomplete="off" style="display: none;">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="table" value="{{$table['name']}}">
                            <input type="file" name="updateJSON" id="ujf{{($key + 1)}}" onchange="$('#updateJSON{{($key + 1)}}').submit()">
                        </form>
                <label for="ujf{{($key + 1)}}" class="link"><a>JSON</a></label>
              </td>
            </tr>
            @endforeach
  				</tbody>
  			</table>
      </div>
      @if($np>1)
      <div class="pagination">
        @if($page==1)<a class="disabled">&laquo;</a>@endif
        @if($page!=1)<a href="{{$urls[$page-2]}}" rel="prev">&laquo;</a>@endif

        @foreach($urls as $url)
        @if($loop->index + 1 == $page)
        <a class="active">{{ $page }}</a>
        @else
        <a href="{{ $url }}">{{$loop->index+1}}</a>
        @endif
        @endforeach

        @if($page==$np)<a class="disabled">&raquo;</a>@endif
        @if($page!=$np)<a href="{{$urls[$page]}}" rel="next">&raquo;</a>@endif
      </div>
      @endif
		</div>
	</div>
</div>
<script>
  var key ="";
  function renameTable(table, key){
    key = key;
    $(".selectedTable").val(table);
    $("#renameTable").modal();
  }
  function renameTableRequest(){
    $.post("{{route('c.db.rename.table')}}", $("#renameTableForm").serialize(), function(data){
       if(data.status == "success"){
          $('#renameTable').modal('toggle');
          $("#r"+String(key+1) + " td:nth(1)").html(data.new_name);
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Table name changed successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> '+data.status+'</div>';
            $('#alrt').html(ht);
        }
    });
  }
  function truncate(table, key){
    var check = confirm("Are you sure you want to truncate this table");
    if(check){
      $.post("{{route('c.truncate.table')}}", {"table":table,"_token":"{{csrf_token()}}"}, function(data){
        if(data.status == "success"){
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Table '+table+' truncated successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> Table '+table+' truncate was not successfully!</div>';
            $('#alrt').html(ht);
        }
      });
    }
  }
  function deleteTable(table, key){
    var check = confirm("Are you sure you want to delete this table");
    if(check){
      $.post("{{route('c.delete.table')}}", {"table":table,"_token":"{{csrf_token()}}"}, function(data){
        if(data.status == "success"){
          $("#r"+String(key+1)).remove();
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Table '+table+' deleted successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> Table '+table+' deletion was not successfully!</div>';
            $('#alrt').html(ht);
        }
      });
    }
  }
  function createTable(){
    $("#createTable").modal();
  }
  function addFields(table){
    $(".selectedTable").val(table);
    $("#addFields").modal();
  }
  function renameField(table){
    $(".selectedTable").val(table);
    $.get("{{route('c.db.get.columns')}}", {"table":table}, function(data){$(".field_names").html(data);});
    $("#renameField").modal();
  }
  function renameFieldRequest(){
    $.post("{{route('c.db.rename.column.submit')}}", $("#renameFieldForm").serialize(), function(data){
       if(data.status == "success"){
          $('#renameField').modal('toggle');
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Column name changed successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> '+data.status+'</div>';
            $('#alrt').html(ht);
        }
    });
  }
  function deleteField(table){
    $(".selectedTable").val(table);
    $.get("{{route('c.db.get.columns')}}", {"table":table}, function(data){$(".field_names").html(data);});
    $("#deleteField").modal();
  }
  function deleteFieldRequest(){
    $.post("{{route('c.db.delete.column.submit')}}", $("#deleteFieldForm").serialize(), function(data){
       if(data.status == "success"){
          $('#deleteField').modal('toggle');
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Column deleted successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> '+data.status+'</div>';
            $('#alrt').html(ht);
        }
    });
  }
  function addIndex(table){
    $(".selectedTable").val(table);
    $.get("{{route('c.db.get.columns')}}", {"table":table}, function(data){$(".field_names").html(data);});
    $("#addIndex").modal();
  }
  function addIndexRequest(){
    $.post("{{route('c.db.add.index.submit')}}", $("#addIndexForm").serialize(), function(data){
       if(data.status == "success"){
          $('#addIndex').modal('toggle');
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Index added successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> '+data.status+'</div>';
            $('#alrt').html(ht);
        }
    });
  }
  function removeIndex(table){
    $(".add-index").html("Remove Index");
    $("#addIndexForm").attr('action', '{{route('c.db.remove.index.submit')}}');
    $("#ais").attr('onclick', 'removeIndexRequest()');
    addIndex(table);
  }
  function removeIndexRequest(){
    $.post("{{route('c.db.remove.index.submit')}}", $("#addIndexForm").serialize(), function(data){
       if(data.status == "success"){
          $('#addIndex').modal('toggle');
          var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Index deleted successfully!</div>';
            $('#alrt').html(ht);
        }else{
          var ht = '<div class="alert alert-danger text-center"><strong>Failed!</strong> '+data.status+'</div>';
            $('#alrt').html(ht);
        }
    });
  }
  function importCreate(table){
    $(".selectedTable").val(table);
    $("#importCreate").modal();
  }
</script>

<!-- Modal -->
<div id="importCreate" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="renameTableForm" method="post" action="{{route('c.db.rename.table')}}">
        <input type="hidden" name="table" class="selectedTable" />
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Import </h4>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" name="new_name" required autofocus/>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default">Update</button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="renameTable" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="renameTableForm" method="post" action="{{route('c.db.rename.table')}}">
        <input type="hidden" name="table" class="selectedTable" />
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">New Table Name</h4>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" name="new_name" required autofocus/>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default">Update</button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="createTable" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form method="get" action="{{route('c.db.new.table')}}" >
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Number of fields</h4>
        </div>
        <div class="modal-body">
          <input type="number" class="form-control" name="fn" required autofocus/>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default">Go</button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="addFields" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form method="get" action="{{route('c.db.add.columns')}}" >
        <input type="hidden" name="table" class="selectedTable" />
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Number of fields</h4>
        </div>
        <div class="modal-body">
          <input type="number" class="form-control" name="fn" required autofocus/>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default">Go</button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="renameField" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="renameFieldForm" >
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="table" class="selectedTable" />
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Rename Field</h4>
        </div>
        <div class="modal-body">
          <label for="field_names">Select the old field</label>
          <select id="field_names" name="old_field_name" class="form-control field_names" autofocus></select>
          <label for="new_field_name">New name of the field</label>
          <input type="text" class="form-control" name="new_field_name" required/>
        </div>
      </form>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default" onclick="renameFieldRequest()">Rename</button>
        </div>
    </div>

  </div>
</div>


<!-- Modal -->
<div id="deleteField" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="deleteFieldForm" >
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="table" class="selectedTable" />
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Delete Field</h4>
        </div>
        <div class="modal-body">
          <label for="field_names">Select the old field</label>
          <select id="field_names" name="field_name" class="form-control field_names" autofocus></select>
        </div>
        </form>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default" onclick="deleteFieldRequest()">Delete</button>
        </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="addIndex" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form id="addIndexForm" >
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <input type="hidden" name="table" class="selectedTable" />
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title add-index">Add Index</h4>
        </div>
        <div class="modal-body">
          <label for="field_names">Select the field</label>
          <select id="field_names" name="field_name" class="form-control field_names" autofocus></select>
          <label for="index_name" class="ri">Select the index</label>
          <select name="index_name" class="form-control ri">
            <option value="primary">PRIMARY</option>
            <option value="unique">UNIQUE</option>
            <option value="index">INDEX</option>
          </select>
        </div>
      </form>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default add-index" id="ais" onclick="addIndexRequest()">Add Index</button>
        </div>
    </div>

  </div>
</div>
@endsection