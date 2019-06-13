<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    
    {{-- bootstrap css --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style type="text/css" media="screen">
        body{
            background: grey;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header" style="width: 100%;">
          <a class="navbar-brand">{{ config('app.name', 'Laravel') }}</a>
          <button class="btn btn-default" style="float: right; margin-top: 8px;" onclick="quit()">StopChat</button>
        </div>
      </div>
    </nav>
    <div style="height:10vh"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 panel panel-default" style="margin-left: 10px; margin-right: 10px;">
                <p>Caution:  Refreshing this window will terminate the conversation</p>
                <hr/>
                <button class="btn btn-primary" onclick="go_online()" style="width: 100%;" id="go_online">Go Online</button>
                <div id="now_online">
                  <h3>I am now Online !</h3>
                  <h4>Your chat resource id:- <span id="mycid"></span></h4>
                </div>
                <hr/>
                <h4>You are chatting with:-</h4>
                <div class="input-group">
                  <input type="number" class="form-control" placeholder="Receiver Chat Resource Id" id="rcid" name="rcid" onkeypress="onkey2(event)" />
                  <div class="input-group-btn">
                    <button class="btn btn-default" type="submit" onclick="add_rcid()">Add</button>
                  </div>
                </div>
                <ul id="rcid_list"></ul>
            </div>
            <div class="col-md-6" style="margin-left: 10px; margin-right: 10px;">
                <div class="row">
                    <div class="col-md-12 panel panel-default text-right" style="padding-top: 8px; padding-bottom: 8px;" id="chat_area">

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 panel panel-default text-right" style="padding-top: 8px; padding-bottom: 8px;">
                        <label>You are sending message to:</label><select id="rcid_menu" onchange="chat_area_refresh()"></select>
                        <textarea rows="3" type="text" onkeypress="onkey(event)" class="form-control" id="message" name="message" placeholder="Type your message here"></textarea>
                        <button class="btn btn-primary" onclick="send()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <script>
        var conn;
        var mycid = 0;
        var arrr = [];
        init_chat();
        $("#now_online").hide();
        function init_chat(){
          try{
            conn = new WebSocket('ws://localhost:8080');
            console.log('WebSocket - status '+conn.readyState);
            conn.onopen    = function(msg){ console.log("Connection established!"); };
            conn.onmessage = function(msg){ 
              var data = msg.data;
              var appchild = "";
              if(mycid == 0){
                $("#mycid").html(data);
                mycid = data;
              }else{
                data = JSON.parse(data);
                if(data['from'] == mycid){
                  appchild = appchild + '<p class="c'+data['to']+'" style="color:blue;">' + data['message'] + ' <label>:You</label></p>';
                }else{
                  appchild = appchild + '<p class="c'+data['from']+'" style="text-align: left; color:red;"><label>'+data['from']+':</label> ' + data['message'] + '</p>';
                }
                $("#chat_area").append(appchild);
                chat_area_refresh();
              }
              console.log(data); 
            };
            conn.onclose   = function(msg){ console.log("Connection closed!");  };
          }
          catch(e) {
              console.log(e); 
          }
          $("#message").focus();
        }

        function add_rcid(){
          if($("#rcid").val() == ""){return;}
          arrr.push($("#rcid").val());
          $("#rcid").val("")
          var rcid_list = "";
          for(var i=0; i<arrr.length; i++){
            rcid_list = rcid_list + "<li>" + arrr[i] + "</li>";
          }
          $("#rcid_list").html(rcid_list);
          var rcid_menu = "";
          for(var i=0; i<arrr.length; i++){
            rcid_menu = rcid_menu + "<option>" + arrr[i] + "</option>";
          }
          $("#rcid_menu").html(rcid_menu);
          chat_area_refresh();
        }

        function send(){
          var txt = $("#message");
          var msg = {};
          msg['message'] = txt.val();
          msg['to'] = $("#rcid_menu").val();
          msg['from'] = mycid;
          if(!msg['message']){ alert("Message can not be empty"); return; }
          if(!msg['to']){ alert("You should select To Chat reference Id"); return; }
          txt.val("");
          txt.focus();
          try{ 
            conn.send(JSON.stringify(msg)); 
            console.log(msg); 
          } 
          catch(ex){ 
            console.log(ex); 
          }
        }

        function quit(){
          console.log("Goodbye!");
          conn.close();
          conn=null;
        }

        function onkey(event){ if(event.keyCode==13){ send(); } }
        function onkey2(event){ if(event.keyCode==13){ add_rcid(); } }

        function chat_area_refresh(){
          for(var i=0; i<arrr.length; i++){
            if($("#rcid_menu").val() !== arrr[i]){
              $(".c"+arrr[i]).hide();
            }else{
              $(".c"+arrr[i]).show();
            }
          }
        }

        function go_online(){
          $.post("{{ route($rtype.'.online_status') }}", 
          {
            chat_resource_id: mycid,
            _token: '{{csrf_token()}}'
          },
          function(data){
            if(data['online_status']=='online'){
              $("#go_online").hide();
              $("#now_online").show();
            }
            console.log(data);
          });
        }
    </script>
</body>
</html>