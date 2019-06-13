@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  <div class="row">
    <div class="col-md-12 text-center">
      Customer Care App Configuration Table |  for the app id: {{\Auth::user()->active_app_id}}
      <div class="btn-group" style="float:right"> 
        <a class="btn btn-default" href="{{route('c.chat.messages')}}">Back</a>
      </div>
    </div>
  </div><hr>
	<div class="row">
    <div class="col-md-4"></div>
		<div class="col-md-4 table-responsive">
			<table class="table">
        <thead>
          <tr>
            <th>Api Call Name</th>
            <th></th>
            <th>Query Id</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>signup</td>
            <td>:</td>
            <td><input type="number" id="signup" class="form-control" placeholder="signup query Id" /></td>
          </tr>
          <tr>
            <td>login</td>
            <td>:</td>
            <td><input type="number" id="login" class="form-control" placeholder="login query Id" /></td>
          </tr>
          <tr>
            <td>Send Email Verification Code(sevc)</td>
            <td>:</td>
            <td><input type="number" id="sevc" class="form-control" placeholder="sevc query Id" /></td>
          </tr>
          <tr>
            <td>verify_email</td>
            <td>:</td>
            <td><input type="number" id="ve" class="form-control" placeholder="verify_email query Id" /></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td><button class="btn btn-primary" onclick="saveConfig()">Save</button></td>
          </tr>
        </tbody>
      </table>
		</div>
	</div>
</div>
<script>
  $("#signup").val('{{$ccac['signup']??''}}');
  $("#login").val('{{$ccac['login']??''}}');
  $("#sevc").val('{{$ccac['sevc']??''}}');
  $("#ve").val('{{$ccac['ve']??''}}');
  function saveConfig(){
  	$.post('{{ route('c.chat.ccac.submit') }}',{"_token":"{{csrf_token()}}","_method":"put","signup":$("#signup").val(),"login":$("#login").val(),"sevc":$("#sevc").val(),"ve":$("#ve").val() },function(data, status){
  		if(status == 'success'){
  			$('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Customer care app configuration was successfully saved.</div>');
  		}else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Customer care app configuration was not saved.</div>');
      }
  	});
  }
</script>
@endsection