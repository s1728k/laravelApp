@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('email'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('email')}}</div>@endif
  <div class="row">
    <div class="col-md-12 text-center">
      @empty($id) New Mail @else Update Mail @endempty <div class="btn-group" style="float:right;">
        <a id="back" class="btn btn-default" href="{{route('c.mail.list.view')}}">Back</a></div>
    </div>
  </div><hr>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <textarea rows="56" id="mail_obj" class="form-control" onfocusout="setheight()">Mail Object</textarea>
    </div>
  </div><br><br>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <div class="btn-group">
      <button class="send_mail btn btn-primary" onclick="sendMail()">Send Mail</button>
      @empty($id) <button class="send_mail btn btn-primary" onclick="saveMail()">Save Mail</button> 
      @else <button class="send_mail btn btn-primary" onclick="updateMail()">Update Mail</button> @endempty </div>
    </div>
  </div><br><br>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <p>Notes:-</p>
      <p>1. Send this json object with post method using url https://honeyweb.org/api/mail</p>
      <p>2. Available template names are "common_mail".</p>
    </div>
  </div><br><br><br><br><br><br><br><br>
</div>

<script>
  String.prototype.capitalize = function() {
      return this.charAt(0).toUpperCase() + this.slice(1);
  }
  var mail_obj = {!! json_encode($email) !!};

  $("#mail_obj").val(JSON.stringify(mail_obj, undefined, 4));

  function setheight(){
    $("#mail_obj").prop('rows',10);
    var s_height = document.getElementById('mail_obj').scrollHeight + 20;
    $("#mail_obj").prop('rows',Math.ceil(s_height/20));
  }

  function isValidJson(json) {
    try {
      JSON.parse(json);
      return true;
    } catch (e) {
      return false;
    }
  }

  function sendMail(){
    try {
      let postBody = JSON.parse($("#mail_obj").val());
      $(".send_mail").prop("disabled",true);

      $.post("{{env('APP_URL')}}/api/email", postBody, function(data, status){
        if(status='success'){
          $('#alrt').html('<div class="alert alert-'+data['status']+'"><strong>'+data['status'].capitalize()+'!</strong> '+data['message']+'.</div>');
          $(".send_mail").prop("disabled",false);
          document.getElementById("alrt").scrollIntoView();
        }
      });
    } catch (e) {
      $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Invalid Json.</div>');
      $(".send_mail").prop("disabled",false);
      document.getElementById("alrt").scrollIntoView();
    }
  }

  @empty($id) 
  function saveMail(){
    try {
      // let postBody = JSON.parse($("#mail_obj").val());
      $(".send_mail").prop("disabled",true);

      $.post("{{ route('c.mail.add.new') }}", {'email':$("#mail_obj").val(),'_token':'{{csrf_token()}}'}, function(data, status){
        if(status='success'){
          $('#alrt').html('<div class="alert alert-'+data['status']+'"><strong>'+data['status']+'!</strong> '+data['message']+'</div>');
          $(".send_mail").prop("disabled",false);
          document.getElementById("alrt").scrollIntoView();
        }
      });
    } catch (e) {
      $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Invalid Json.</div>');
      $(".send_mail").prop("disabled",false);
      document.getElementById("alrt").scrollIntoView();
    }
  }
  @else
  function updateMail(){
    try {
      let postBody = JSON.parse($("#mail_obj").val());
      $(".send_mail").prop("disabled",true);

      $.post("{{ route('c.mail.update') }}", {'_method':'put','id':'{{$id}}','email':$("#mail_obj").val(),'_token':'{{csrf_token()}}'}, function(data, status){
        if(status='success'){
          $('#alrt').html('<div class="alert alert-'+data['status']+'"><strong>'+data['status']+'!</strong> '+data['message']+'</div>');
          $(".send_mail").prop("disabled",false);
          document.getElementById("alrt").scrollIntoView();
        }
      });
    } catch (e) {
      $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Invalid Json.</div>');
      $(".send_mail").prop("disabled",false);
      document.getElementById("alrt").scrollIntoView();
    }
  }
  @endempty
</script>
@endsection