@extends('layouts.app')

@section('content')
<br>
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-content">
              <span class="card-title">Dashboard</span>
              <p>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                You are logged in!
            </p>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- <script>  
  // function showMessage(messageHTML) {
  //   $('#chat-box').append(messageHTML);
  // }

  // $(document).ready(function(){
  //   var websocket = new WebSocket("ws://localhost:8090/demo/php-socket.php"); 
  //   websocket.onopen = function(event) { 
  //     showMessage("<div class='chat-connection-ack'>Connection is established!</div>");   
  //   }
  //   websocket.onmessage = function(event) {
  //     var Data = JSON.parse(event.data);
  //     showMessage("<div class='"+Data.message_type+"'>"+Data.message+"</div>");
  //     $('#chat-message').val('');
  //   };
    
  //   websocket.onerror = function(event){
  //     showMessage("<div class='error'>Problem due to some Error</div>");
  //   };
  //   websocket.onclose = function(event){
  //     showMessage("<div class='chat-connection-ack'>Connection Closed</div>");
  //   }; 
    
  //   $('#frmChat').on("submit",function(event){
  //     event.preventDefault();
  //     $('#chat-user').attr("type","hidden");    
  //     var messageJSON = {
  //       chat_user: $('#chat-user').val(),
  //       chat_message: $('#chat-message').val()
  //     };
  //     websocket.send(JSON.stringify(messageJSON));
  //   });
  // });
  var conn = new WebSocket('ws://localhost:8080/echo');
    conn.onmessage = function(e) { console.log(e.data); };
    conn.onopen = function(e) { conn.send('Hello Me!'); };
</script> --}}