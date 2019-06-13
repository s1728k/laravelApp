@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6">
      Licenses List | for the app id: {{\Auth::user()->active_app_id}}
    </div>
    <div class="col-md-6">
      <div class="btn-group" style="float:right">
        <button class="btn btn-default" data-toggle="modal" data-target="#createNewLicense">Create New License</button>
        <a class="btn btn-default" href="{{ route('l.test.bench.view') }}" target="_blank">Test Bench</a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive" style="padding-bottom: 50px;">
      <table class="table">
        <thead>
          <tr>
            <th>Sr.</th>
            <th>Serial No</th>
            <th>License Key</th>
            <th>Total Licenses</th>
            <th>Activated Licenses</th>
            <th>Expiry Date</th>
            <th colspan="2">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($licenses as $license)
          <form id="frm{{$loop->index}}" method="post" action="{{ route('l.update.license', ['id' => $license->id]) }}">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <tr>
              <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
              <td>{{$license->id}}</td>
              <td>{{$license->license_key}}</td>
              <td id="tl{{$loop->index}}">{{$license->total_licenses}}</td>
              <td id="tle{{$loop->index}}" style="display: none">
                <input type="number" name="total_licenses" class="form-control" value="{{$license->total_licenses}}" />
              </td>
              <td>{{$license->activated_licenses}}</td>
              <td id="ed{{$loop->index}}">{{$license->expiry_date}}</td>
              <td id="ede{{$loop->index}}" style="display: none">
                <input type="date" name="expiry_date" class="form-control" value="{{$license->expiry_date}}" />
              </td>
              <td>
                <a href="JavaScript:void(0);" id="edb{{$loop->index}}" onclick="ri({{$loop->index}})" data-toggle="modal" data-target="#editLicense">Edit</a>
              </td>
              <td>
                <a href="{{ route('l.license.details.view', ['id' => $license->id]) }}">License Details</a>
              </td>
            </tr>
            </form>
            <form id="dbf{{$loop->index}}" method="post" action="{{ route('l.delete.license', ['id' => $license->id]) }}" style="display: none;"><input type="hidden" name="_token" value="{{csrf_token()}}"></form>
          @endforeach
        </tbody>
      </table>
      </div>
      {{$licenses->appends(request()->input())->links()}}
    </div>
  </div>
</div>
<script>
  var edit = {!! $edit !!};
  function ri(id){
    $("#tl" + String(id)).css(edit[id]);
    $("#ed" + String(id)).css(edit[id]);
    if(edit[id]['display'] == "block"){
      edit[id]['display'] = "none";
      $("#edb" + String(id)).html('Edit');
      $('#frm' + String(id)).submit();
    }else{
      edit[id]['display'] = "block";
      $("#edb" + String(id)).html('Update');
    }
    $("#tle" + String(id)).css(edit[id]);
    $("#ede" + String(id)).css(edit[id]);
  }
  function di(id){
    document.getElementById('dbf' + String(id)).submit();
  }

</script>

<!-- Modal -->
<div id="createNewLicense" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">New License</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('l.create.new.license') }}">
          <input type="hidden" name="_token" value="{{csrf_token()}}" />

          <div class="form-group">
            <label for="total_licenses">Total number of licenses</label>
            <input type="number" name="total_licenses" class="form-control{{ $errors->has('total_licenses') ? ' is-invalid' : '' }}" value="{{ old('total_licenses') }}" required autofocus>
            @if ($errors->has('total_licenses'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('total_licenses') }}</strong>
                </span>
            @endif
          </div>

          <div class="form-group">
            <label for="expiry_date">Expiry date</label>
            <input type="date" name="expiry_date" class="form-control{{ $errors->has('expiry_date') ? ' is-invalid' : '' }}" value="{{ old('expiry_date') }}" required autofocus>
            @if ($errors->has('expiry_date'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('expiry_date') }}</strong>
                </span>
            @endif
          </div>
          <div class="form-group"><button type="submit" class="btn btn-primary">Create</button></div>
        </form>
      </div>
      <div class="modal-footer">
        {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
      </div>
    </div>

  </div>
</div>

@endsection