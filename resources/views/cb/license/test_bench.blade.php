@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <h4 class="heading text-center">License Test Bench</h4>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <h5 class="heading">For Activation</h5>
      <div class="table-responsive">
      <table width="100%" width="100%">
        <caption>Input Json : <p>Send as post body to route:- http://honeyweb.org/api/license/activate</p></caption>
        <tr>
          <td>{{ '{' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>"serial_no"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="serial_no1" class="form-control" value="" placeholder="Serial Number"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"license_key"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="license_key1" class="form-control" value="" placeholder="License Key"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"hardware_code"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="hardware_code1" class="form-control" value="" placeholder="Hardware Code"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"computer_user"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="computer_user1" class="form-control" value="" placeholder="Computer User (optional)"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"computer_name"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="computer_name1" class="form-control" value="" placeholder="Computer Name (optional)"></td>
          <td>"</td>
          <td></td>
        </tr>
        <tr>
          <td>"license_no"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="license_no0" class="form-control" value="" placeholder="License No. (optional)"></td>
          <td>"</td>
          <td></td>
        </tr>
        <tr>
          <td>{{ '}' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </table>
      </div>
      <div class="table-responsive">
      <table width="100%">
        <caption>Output Json</caption>
        <tr>
          <td>{{ '{' }}</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>"status"</td>
          <td style="width:20px; text-align:center">:</td>
          <td id="status1">"",</td>
        </tr>
        <tr>
          <td>"expiry_date"</td>
          <td style="width:20px; text-align:center">:</td>
          <td id="expiry_date1">"",</td>
        </tr>
        <tr>
          <td>"license_no"</td>
          <td style="width:20px; text-align:center">:</td>
          <td id="license_no1">"",</td>
        </tr>
        <tr>
          <td>"available_licenses"</td>
          <td style="width:20px; text-align:center">:</td>
          <td id="available_licenses1">""</td>
        </tr>
        <tr>
          <td>{{ '}' }}</td>
          <td></td>
          <td></td>
        </tr>
      </table>
      </div>
      <button type="submit" class="btn btn-primary" onclick="activate()">Activate</button>
      <hr>
    </div>
    <div class="col-md-6">
      <h5 class="heading">For De-activation</h5>
      <div class="table-responsive">
      <table width="100%">
        <caption>Input Json <p>Send as post body to route:- http://honeyweb.org/api/license/deactivate</p></caption>
        <tr>
          <td>{{ '{' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>"serial_no"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="serial_no2" class="form-control" value="" placeholder="Serial Number"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"license_key"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="license_key2" class="form-control" value="" placeholder="License Key"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"hardware_code"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" class="form-control" id="hardware_code2"  value="" placeholder="Hardware Code" ></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"license_no"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" class="form-control" id="license_no2" value="" placeholder="License Number"></td>
          <td>"</td>
          <td></td>
        </tr>
        <tr>
          <td>{{ '}' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </table>
      </div>
      <div class="table-responsive">
      <table width="100%">
        <caption>Output Json</caption>
        <tr>
          <td>{{ '{' }}</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>"status"</td>
          <td style="width:20px; text-align:center">:</td>
          <td id="status2">"",</td>
        </tr>
        <tr>
          <td>"available_licenses"</td>
          <td style="width:20px; text-align:center">:</td>
          <td id="available_licenses2">""</td>
        </tr>
        <tr>
          <td>{{ '}' }}</td>
          <td></td>
        </tr>
      </table>
      </div>
      <button class="btn btn-primary" onclick="de_activate()">De-activate</button>
      <hr>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <h5 class="heading">Remote License Key Generation When Software Purchased From Your Website</h5>
      <div class="table-responsive">
      <table width="100%">
        <caption>Input Json <p>Send as post body to route:- http://honeyweb.org/api/get-license-key</p></caption>
        <tr>
          <td>{{ '{' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>"app_id"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="app_id3" class="form-control" value="" placeholder="App ID"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"app_secret"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="app_secret3" class="form-control" value="" placeholder="App Secret"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"total_licenses"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="total_licenses3" class="form-control" value="" placeholder="Total Licenses (server type)"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"expiry_date"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="date" id="expiry_date3" class="form-control" value="" placeholder="Expiry Date"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>{{ '}' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </table>
      </div>
      <div class="table-responsive">
      <table width="100%">
        <caption>Output Json</caption>
        <tr>
          <td>{{ '{' }}</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>"serial_no"</td>
          <td style="width:20px; text-align:center">:</td>
          <td id="serial_no3">"",</td>
        </tr>
        <tr>
          <td>"license_key"</td>
          <td style="width:20px; text-align:center">:</td>
          <td id="license_key3">""</td>
        </tr>
        <tr>
          <td>{{ '}' }}</td>
          <td></td>
        </tr>
      </table>
      </div>
      <button class="btn btn-primary" onclick="getLicenseKey()">Get License Key</button>
      <hr>
    </div>
  </div>

</div>
<script>
  var ainp = {}; var dinp = {}; var spinp = {};

  function activate(){
    ainp['serial_no'] = $('#serial_no1').val();
    ainp['license_key'] = $('#license_key1').val();
    ainp['hardware_code'] = $('#hardware_code1').val();
    ainp['computer_user'] = $('#computer_user1').val();
    ainp['computer_name'] = $('#computer_name1').val();
    ainp['license_no'] = $('#license_no0').val();

    $.post("{{route('l.activate.licnese')}}", ainp, function(data){
      $('#status1').html('"' + String(data['status']) + '"');
      $('#expiry_date1').html('"' + String(data['expiry_date']) + '"');
      $('#license_no1').html('"' + String(data['license_no']) + '"');
      $('#available_licenses1').html('"' + String(data['available_licenses']) + '"');
    });
  }

  function de_activate(){
    dinp['serial_no'] = $('#serial_no2').val();
    dinp['license_key'] = $('#license_key2').val();
    dinp['hardware_code'] = $('#hardware_code2').val();
    dinp['license_no'] = $('#license_no2').val();

    $.post("{{route('l.deactivate.licnese')}}", dinp, function(data){
      $('#status2').html('"' + String(data['status']) + '"');
      $('#available_licenses2').html('"' + String(data['available_licenses']) + '"');
    });
  }

  function getLicenseKey(){
    spinp['app_id'] = $('#app_id3').val();
    spinp['app_secret'] = $('#app_secret3').val();
    spinp['total_licenses'] = $('#total_licenses3').val();
    spinp['expiry_date'] = $('#expiry_date3').val();

    $.post("{{route('l.get.licnese.key')}}", spinp, function(data){
      $('#serial_no3').html('"' + String(data['serial_no']) + '"');
      $('#license_key3').html('"' + String(data['license_key']) + '"');
    });
  }

</script>
@endsection