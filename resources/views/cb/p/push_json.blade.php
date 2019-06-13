@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  <div class="row">
    <div class="col-md-12 text-center">
      @empty($id) New Push Message @else Update Push Message @endempty <div class="btn-group" style="float:right;">
        <a id="back" class="btn btn-default" href="{{route('c.push.messages')}}">Back</a></div>
    </div>
  </div><hr>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <textarea rows="45" id="push_obj" class="form-control" onfocusout="setheight()">Push Message Object</textarea>
    </div>
  </div><br><br>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <div class="btn-group">
      <button class="send_mail btn btn-primary" onclick="sendPushMessage()">Send Push Message</button>
      @empty($id) <button class="send_mail btn btn-primary" onclick="savePushMessage()">Save Push Message</button> 
      @else <button class="send_mail btn btn-primary" onclick="updatePushMessage()">Update Push Message</button> @endempty </div>
    </div>
  </div><br><br>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <p>Notes:-</p>
      <p>1. Send this json object with post method using url https://honeyweb.org/api/push</p>
    </div>
  </div><br><br><br><br><br><br><br><br>
</div>

<script>
  String.prototype.capitalize = function() {
      return this.charAt(0).toUpperCase() + this.slice(1);
  }
  var push_obj = {!! $push !!};

  console.log(JSON.parse(JSON.stringify(push_obj)));

  $("#push_obj").val(JSON.stringify(push_obj, undefined, 4));

  function setheight(){
    $("#push_obj").prop('rows',10);
    var s_height = document.getElementById('push_obj').scrollHeight + 20;
    $("#push_obj").prop('rows',Math.ceil(s_height/20));
  }

  function isValidJson(json) {
    try {
      JSON.parse(json);
      return true;
    } catch (e) {
      return false;
    }
  }

  function sendPushMessage(){
    try {
      let postBody = JSON.parse($("#push_obj").val());
      $(".send_mail").prop("disabled",true);

      $.post("{{env('APP_URL')}}/api/push", postBody, function(data, status){
        if(status='success'){
          $('#alrt').html('<div class="alert alert-'+data['status']+'"><strong>'+data['status'].capitalize()+'!</strong> '+data['message']+'</div>');
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
  function savePushMessage(){
    try {
      let postBody = JSON.parse($("#push_obj").val());
      $(".send_mail").prop("disabled",true);

      $.post("{{ route('c.push.new.msg.submit') }}", {'push':$("#push_obj").val(),'_token':'{{csrf_token()}}'}, function(data, status){
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
  function updatePushMessage(){
    try {
      let postBody = JSON.parse($("#push_obj").val());
      $(".send_mail").prop("disabled",true);

      $.post("{{ route('c.push.update.msg.submit') }}", {'_method':'put','id':'{{$id}}','push':$("#push_obj").val(),'_token':'{{csrf_token()}}'}, function(data, status){
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