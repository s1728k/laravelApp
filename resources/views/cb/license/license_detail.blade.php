@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6">
      License Usage Detail 
    </div>
    <div class="col-md-6">
      <a class="btn btn-default" style="float:right" href="{{route('l.license.list.view')}}">Back</a> 
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Sr.</th>
            <th>Serial Number</th>
            <th>License Key</th>
            <th>Hardware Code</th>
            <th>Computer Name</th>
            <th>Computer User</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($licenseDetails as $licenseDetail)
            <tr>
              <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
              <td>{{$license_id}}</td>
              <td>{{$license_key}}</td>
              <td>{{$licenseDetail->hardware_code}}</td>
              <td>{{$licenseDetail->computer_name}}</td>
              <td>{{$licenseDetail->computer_user}}</td>
              @if($licenseDetail->hardware_code == "Empty")
              <td><a href="JavaScript:void(0);" onclick="sr({{$loop->index}})" data-toggle="modal" data-target="#manualActivation">Activate</a></td>
              @else
              <td><a href="JavaScript:void(0);" onclick="sr({{$loop->index}})" >De-Activate</a></td>
              @endif
            </tr>
          @endforeach
        </tbody>
      </table>
      {{$licenseDetails->appends(request()->input())->links()}}
      </div>
    </div>
  </div>
</div>
<script>
  var tri = 0; var license_key = ""; var serial_no = 0; var ainp = {}; var dinp = {}; var btn = "";

  function sr(i){
    tri = i;
    license_key = $('tr:nth-child('+String(i+1)+')  td:nth-child(3)').html();
    serial_no = $('tr:nth-child('+String(i+1)+')  td:nth-child(2)').html();
    $('#hardware_code1').val($('tr:nth-child('+String(i+1)+')  td:nth-child(4)').html());
    if($('tr:nth-child('+String(i+1)+')  td:nth-child(7) a').html() == "De-Activate"){
      de_activate();
    }else{
      $('#computer_name1').val($('tr:nth-child('+String(i+1)+')  td:nth-child(5)').html());
      $('#computer_user1').val($('tr:nth-child('+String(i+1)+')  td:nth-child(6)').html());
    }
  }

  function activate(){
    ainp['serial_no'] = serial_no;
    ainp['license_key'] = license_key;
    ainp['hardware_code'] = $('#hardware_code1').val();
    ainp['computer_name'] = $('#computer_name1').val();
    ainp['computer_user'] = $('#computer_user1').val();
    if($('#expiry_date1').val()){ainp['expiry_date'] = $('#expiry_date1').val();}
    ainp['license_no'] = $('tr:nth-child('+String(tri+1)+')  td:nth-child(1)').html();

    $.post("{{route('l.activate.licnese')}}", ainp, function(data){
      $('#status1').html('"' + String(data['status']) + '"');
      $('#expiry_date1').html('"' + String(data['expiry_date']) + '"');
      $('#license_no1').html('"' + String(data['license_no']) + '"');
      $('#available_licenses1').html('"' + String(data['available_licenses']) + '"');
      if(data['status'] == "Activated"){
        $('tr:nth-child('+String(tri+1)+')  td:nth-child(4)').html($('#hardware_code1').val());
        $('tr:nth-child('+String(tri+1)+')  td:nth-child(5)').html($('#computer_name1').val());
        $('tr:nth-child('+String(tri+1)+')  td:nth-child(6)').html($('#computer_user1').val());
        btn = '<a href="JavaScript:void(0);" onclick="sr('+String(tri)+')">De-Activate</a>';
        $('tr:nth-child('+String(tri+1)+')  td:nth-child(7)').html(btn);
      }
      console.log(data['status']);
    });
  }

  function de_activate(){
    dinp['serial_no'] = serial_no;
    dinp['license_key'] = license_key;
    dinp['hardware_code'] = $('#hardware_code1').val();
    dinp['license_no'] = $('tr:nth-child('+String(tri+1)+')  td:nth-child(1)').html();

    $.post("{{route('l.deactivate.licnese')}}", dinp, function(data){
      if(data['status'] == "De-Activated"){
        $('tr:nth-child('+String(tri+1)+')  td:nth-child(4)').html("Empty");
        btn = '<a href="JavaScript:void(0);" onclick="sr('+String(tri)+')" data-toggle="modal" data-target="#manualActivation">Activate</a>';
        $('tr:nth-child('+String(tri+1)+')  td:nth-child(7)').html(btn);
      }
    });
  } 
</script>

<!-- Modal -->
<div id="manualActivation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activate License</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label for="hardware_code1">Hardware Code</label>
            <input type="text" id="hardware_code1" class="form-control" value="" placeholder="Hardware Code" required autofocus>
          </div>
          <div class="form-group">
            <label for="computer_name1">Computer Name (Optional)</label>
            <input type="text" id="computer_name1" class="form-control" value="" placeholder="Computer Name (optional)">
          </div>
          <div class="form-group">
            <label for="computer_user1">Computer User (Optional)</label>
            <input type="text" id="computer_user1" class="form-control" value="" placeholder="Computer User (optional)">
          </div>
          <div class="form-group">
            <label for="expiry_date1">New Expiry Date (Optional - Required when expiry date completes)</label>
            <input type="date" id="expiry_date1" class="form-control" value="" placeholder="New Expiry Date (optional)">
          </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal" onclick="activate()">Activate</button>
        {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
      </div>
    </div>

  </div>
</div>

@endsection