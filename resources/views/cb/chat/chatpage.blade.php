<!DOCTYPE html>
<html>
<head>
  <title>CustomerCare | Honeyweb.org</title>
  <meta name="viewport" content="width=device-width , initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="http://localhost:8003/public/js/websocket.js"></script>
  <style>
  .hover_icons:hover{
    color:black !important;
  }
  .chats:hover{
    background: #f1f1f1;
  }
  .main_bar{
    display: none;
  }
  @media screen and (max-width: 992px) {
    .side_bar{
      display: none;
    }
  }
  @media screen and (min-width: 992px) {
    .main_bar {
      display: block;
    }
  }
</style>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a><svg height="45" width="250"><text x="0" y="28" fill="grey" style="font-size:27px; font-weight:bold; font-family:Arial, Helvetica, sans-serif">HoneyWeb.Org</text><text x="0" y="42" fill="lightgrey" style="font-size:9px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; letter-spacing: .38rem;">Delightful Web Creations</text>HoneyWeb.Org</svg></a>
      </div>
    </div>
</nav>
<div class="container-fluid">
  <div class="row">
    <div id="sb" class="col-md-3">
      <div class="row">
        <div class="col-md-12">
          <div class="input-group">
            <input id="cterm" type="text" class="form-control" placeholder="Search in chats" onkeypress="return searchChatMembers(event)"><div class="input-group-btn"><button class="btn btn-info" onclick="getMyChats()"><i class="fa fa-search"></i></button></div>
          </div>
        </div>
        <div class="col-md-12">
          <button class="btn btn-info" style="margin-top: 10px; margin-bottom: 10px; width: 100%" onclick="startNewChat()">Start New Conversation</button>
        </div>
      </div>
      <div class="row" id="my_chats"></div>
    </div>
    <div id="mb" class="col-md-9 main_bar" style="height: calc(100vh - 80px);border-left: 1px solid lightblue">
      <div class="row">
        <div class="col-md-12" style="position: relative;">
          <div style="padding-top: 0px">
            <i class="fa fa-angle-left" style="font-size:48px;color:lightblue" onclick="tS()"></i>
          </div>
          <div style="position: absolute;top:-8px;left:50px;">
              <h3 id="chat_name"></h3>
              <!-- <p>seen day ago | gallery | Find</p> -->
          </div>
          <!-- <div style="position: absolute;top:0px;right:-50px; width: 200px;padding-top: 9px;">
            <button class="btn btn-info"><i class="fa fa-video-camera"></i></button>
            <button class="btn btn-info"><i class="fa fa-phone"></i></button>
            <button class="btn btn-info"><i class="fa fa-user-plus"></i></button>
          </div> -->
        </div>
      </div>
      <div class="row">
        <div class="col-md-1" style="width: 8.33%"></div>
        <div class="col-md-10" id="message_space" onscroll="scrolltopcapture(this)" style="height: calc(100vh - 185px); overflow-y: auto; margin-bottom: 5px;">
        </div>
      </div>
      <div class="row">
        <div class="col-md-1" ></div>
        <div class="col-md-10" style="background: lightblue; padding: 10px; border-radius: 10px; display: flex">
          <div style="width: 30px;"><i class="fa fa-smile-o hover_icons" style="font-size:30px;color:grey"></i></div>
          <div style="width: 100%;"><textarea rows="1" id="message" name="message" onkeyup="textAreaAdjust(this)" style="overflow:auto; background: none; border:0px;font-size:15px;width:100%;box-shadow: none;outline: none;" placeholder="Type a message here"></textarea></div>
          <div style="width: 30px;"><i class="fa fa-send-o hover_icons" style="font-size:30px;color:grey" onclick="sendMessage()"></i></div>
        </div>  
      </div>
    </div>
  </div>
</div>
<script>
  $("#user_name").html(sessionStorage.getItem("name"));
  function logout(){
    sessionStorage.clear();
    location.replace("/");
  }
</script>
<script>
  var urls = {
    "signup":"http://localhost:8003/api/14",
    "login":"http://localhost:8003/api/15",
    "email_verify":"http://localhost:8003/api/17",
    "request_token":"http://localhost:8003/api/chat/request_token",
    "save_crid":"http://localhost:8003/api/chat/save_resource_id",
    "start_chat":"http://localhost:8003/api/chat/start_chat",
    "my_chats":"http://localhost:8003/api/chat/my_chats",
    "messages":"http://localhost:8003/api/chat/messages",
    "save_message":"http://localhost:8003/api/chat/save_message",
  }
  var app = {
    '_token':sessionStorage.getItem('_token'),
  }
  var routes="api";
  var csrf_token = ""; var mcs = 15; var bls = 50;
  const istc = (this.bls % this.mcs?parseInt(this.bls / this.mcs)-1:(this.bls - this.mcs)/this.mcs);
  var scroll_pos; var scroll_b = {}; var messages = [];
  var dateline = '<div class="row"><div class="col-md-12"><div style="display: flex;"><div style="width: calc(50% - 75px); height: 10px; border-bottom: 1px solid lightblue"></div><div style="width:150px; text-align: center; color:lightblue;">%date%</div><div style="width: calc(50% - 75px); height: 10px; border-bottom: 1px solid lightblue"></div></div></div></div><br>';
  var timeline_right = '<div class="row" id="m%id%"><div class="col-md-12"><div class="well well-sm" style="position: relative; display: inline-block;float: right; color:grey;margin-bottom:2px;margin-top:20px"><p style="position: absolute;bottom: 27px;right: 0px">%time%</p>%message%</div></div></div>';
  var timeline_left = '<div class="row" id="m%id%"><div class="col-md-12"><image src="https://ui-avatars.com/api/?name=%urlname%" style="border-radius: 20px;height: 40px; width: 40px;"/><p style="position: absolute;bottom: 30px;left: 56px;color:grey">%time%</p><div class="well well-sm" style="position: relative; display: inline-block;color:grey;margin-bottom:2px;margin-top:20px">%message%</div></div></div>';
  var message_right = '<div class="row" id="m%id%"><div class="col-md-12"><div class="well well-sm" style="position: relative; display: inline-block;float:right;color:grey;margin-bottom:2px;">%message%</div></div></div>';
  var message_left = '<div class="row" id="m%id%"><div class="col-md-12"><div class="well well-sm" style="position: relative; display: inline-block;color:grey;margin-bottom:2px;margin-left:40px;">%message%</div></div></div>';
  function requestToken(){
    $.post(this.urls.request_token, this.app, function(data, status){
      if(status == 'success'){
        this.ws._token = data;
        this.app._token = data;
        sessionStorage.setItem('_token', data);
        this.ws.execute();
      }
    });
  }
  function startNewChat(){
    if(this.app._token){
      console.log(this.app._token);
      this.ws.onMessage = this.getMyChats;
      this.ws.execute();
      $.post(this.urls.start_chat, this.app, function(data, status){}).fail(function(e){
        if(e.status == 401){
          if(e.responseJSON['message'] == 'token expired'){
            sessionStorage.clear();this.app._token = 0;this.ws._token = 0;
          }
        }
      });
    }else if(this.routes == 'web'){
      this.app._token = this.csrf_token;
      this.requestToken();
    }else if(this.app['fap'] == 'guest'){
      this.requestToken();
    }
  }
  function getMyChats(){
    this.app['term'] = $("#cterm").val();
    this.app['command'] = 'get_chats';
    this.ws.onMessage = function(data){
      const template = '<div class="col-md-12 chats" style="cursor: pointer;" onclick="this.msgView({{tid}},{{tap}},{{tname}})"><div style="display: inline-flex;width: 100%">  <image src="https://ui-avatars.com/api/?name={{urlname}}" style="border-radius: 20px;height: 40px; width: 40px;margin-top: 8px;"/>  <div style="position: relative; padding-left: 10px;width: 100%">    <h4>{{tname}}</h4>    <p style="position: absolute;top:30px;color:grey">{{lmsg}}</p>    <p style="position: absolute;top:15px;right:0px;color:grey">{{date}}</p>  </div></div></div>';
      let html = "";
      for (var i = 0; i < data.length; i++) {
        t = template.replace("{{tname}}", "'"+data[i].tname+"'");
        t = t.replace("{{tname}}", data[i].tname);
        t = t.replace("{{urlname}}", data[i].tname.replace(/ /g, "+"));
        t = t.replace("{{tid}}", data[i].tid);
        t = t.replace("{{tap}}", "'"+data[i].tap+"'");
        t = t.replace("{{lmsg}}", data[i].message);
        t = t.replace("{{date}}", data[i].updated_at);
        html = html + t;
      };
      $("#my_chats").html(html);
    };
    if(this.ws.conn){this.ws.conn.send(JSON.stringify(this.app));}
  },
  function parseMessages(data){
    let t1 = ""; let t2 = ""; let html = "";
    Object.keys(data).forEach(function(date) {
      if(date == 'eom'){
        localStorage.setItem('eom', 'eom');
        t1 = this.dateline.replace("%date%", "Here it ends");
      }else{
        t1 = this.dateline.replace("%date%", date);
        Object.keys(data[date]).forEach(function(si) {
          Object.keys(data[date][si]).forEach(function(time) {
            if(time.indexOf(localStorage.getItem('tname'))!=-1){
              t2 = t2 + this.timeline_left.replace("%time%", time);
              t2 = t2.replace("%urlname%", time.split(' ').slice(0, -1).join('+'))
              t2 = t2.replace("%message%", data[date][si][time][0]['msg']);
              t2 = t2.replace("%id%", data[date][si][time][0]['id']);
              for (var i = 1; i < data[date][si][time].length; i++) {
                t2 = t2 + this.message_left.replace("%message%",data[date][si][time][i]['msg']);
                t2 = t2.replace("%id%", data[date][si][time][i]['id']);
              };
            }else{
              t2 = t2 + this.timeline_right.replace("%time%", time);
              t2 = t2.replace("%message%", data[date][si][time][0]['msg']);
              t2 = t2.replace("%id%", data[date][si][time][0]['id']);
              for (var i = 1; i < data[date][si][time].length; i++) {
                t2 = t2 + this.message_right.replace("%message%",data[date][si][time][i]['msg']);
                t2 = t2.replace("%id%", data[date][si][time][i]['id']);
              };
            }
          });
        });
        html = html + t1 + t2;
        t1 = "";t2 = "";
      }
      html = t1 + html;
    });
    return html;
  }
  function formatMessages(data=[]){
    let ar = []; let si = 1; let ph = 0; let t = 0;
    for(var i=data.length-1; i>=0; i--){
      const d = new Date(data[i]['created_at']);
      const month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October",  "November",  "December"];
      const date = d.getDate() + '-' + month[d.getMonth()] + '-' + d.getFullYear();
      const time = d.getHours() + ':' + ((d.getMinutes() > 9)?d.getMinutes():('0'+d.getMinutes()));
        ar[date] = ar[date]||[];
        if(data[i]['tid'] == localStorage.getItem('tid') && data[i]['tap'] == localStorage.getItem('tap')){
            t = time;
            if(ph == 1){si++;}
            ph = 0;
            ar[date][si] = ar[date][si]||[];
        }else{
            t = data[i]['fname']+' '+time;
            if(ph == 0){si++;}
            ph = 1;
            ar[date][si] = ar[date][si]||[];
        }
        ar[date][si][t] = ar[date][si][t]||[];
        ar[date][si][t].push({'id':data[i]['id'],'msg':data[i]['message']});
    }
    return ar;
  }
  function scrollPos(offset){
    var objDiv = document.getElementById("message_space");
    objDiv.scrollTop = objDiv.scrollHeight - offset ;
    if(this.stc>this.istc){
      this.stc = this.istc;
      localStorage.setItem('stc',this.stc);
    }
  }
  var socketMessage = function(data){
    if((data['tap'] == self.tap && data['tid'] == self.tid)||(data['fap'] == self.tap && data['fid'] == self.tid)){
      self.messages.unshift(data);
      let tdata = self.formatMessages(self.messages);
      let html = self.parseMessages(tdata);
      $("#message_space").html(html);
      self.scrollPos(0);
    }
  }
  var socketMessages = function(data){
    self.messages = data;
    let tdata = self.formatMessages(data);
    let html = self.parseMessages(tdata);
    $("#message_space").html(html);
    self.scrollPos(0);
  }
  var socketScrollMessages = function(data){
    self.messages = data;
    let tdata = self.formatMessages(data);
    let html = self.parseMessages(tdata);
    $("#message_space").html(html);
    self.scrollPos(self.scroll_pos);
  }
  function getMyMessages(fid, fap, fname, stc = 0, bl = 10, scroll_triger = 0){
    this.app['tid'] = fid;
    this.app['tap'] = fap;
    this.app['tname'] = fname;
    this.app['stc'] = stc;
    this.app['bl'] = bl;
    this.app['mcs'] = this.mcs;
    this.app['term'] = "";
    this.app['command'] = "get_messages";
    if(scroll_triger){
      this.ws.onMessage = this.socketScrollMessages;
    }else{
      this.ws.onMessage = this.socketMessages;
    }
    if(this.ws.conn){this.ws.conn.send(JSON.stringify(this.app));}
  }
</script>
<script>
  var stc = 0; var bl = this.mcs; var tid; var tap; var tname;
  if(localStorage.getItem('view') == 'messages'){
    $("#sb").toggleClass("side_bar");
    $("#mb").toggleClass("main_bar");
    this.tid = localStorage.getItem('tid');
    this.tap = localStorage.getItem('tap');
    this.tname = localStorage.getItem('tname');
    this.stc = parseInt(localStorage.getItem('stc')); 
    this.bl = parseInt(localStorage.getItem('bl'));
    $("#chat_name").html(tname);
    getMyMessages(tid, tap, tname,stc,bl,1,0);
  }else{
    localStorage.setItem('view',  'chats');
  }
  function tS(){
    if(localStorage.getItem('view') == 'chats'){
      localStorage.setItem('view', 'messages');
    }else{
      localStorage.setItem('view',  'chats');
    }
    $("#sb").toggleClass("side_bar");
    $("#mb").toggleClass("main_bar");
  }
  function msgView(tid, tap, tname){
    this.tid = tid;this.tap = tap;this.tname = tname;this.stc = 0;this.bl = this.mcs;
    localStorage.setItem('tid',tid);localStorage.setItem('tap',tap);localStorage.setItem('tname',tname);
    localStorage.setItem('stc',stc);localStorage.setItem('bl',bl);
    tS();
    $("#chat_name").html(tname);
    getMyMessages(tid, tap, tname, stc, bl, 0);
  }
  function textAreaAdjust(o) {
    if(o.scrollHeight < 100){
      o.style.height = "1px";
      o.style.height = (o.scrollHeight)+"px";
    }
  }
  function searchChatMembers(e){
    if (e.keyCode == 13) {
        this.getMyChats();
        return false;
    }
  }
  function sendMessage(){
    this.app['message'] = $("#message").val();
    this.app['tid'] = tid;
    this.app['tap'] = tap;
    this.app['tname'] = tname;
    this.app['command'] = 'chat_message';
    ws.onMessage = this.socketMessage;
    if(this.ws.conn){this.ws.conn.send(JSON.stringify(this.app));}
  }
  function scrolltopcapture(o){
    if($(o).scrollTop() === 0){
      if(localStorage.getItem('eom')!='eom'){
        this.stc = this.stc + 1;
        localStorage.setItem('stc',this.stc);
        this.bl = this.bl + this.mcs;
        localStorage.setItem('bl',this.bl);
        this.scroll_pos = $(o)[0].scrollHeight;
        this.getMyMessages(this.tid,this.tap,this.tname,this.stc,this.bl,1);
      }
    }
  }
  function execute(){
    // if(this.routes == 'web'){
    //   this.urls['request_token'] = this.urls['request_token'].replace("/api/","/");
    // }
    // $("#hw_chat_app").html(this.template.replace("%logo%",this.logo));
    // this.bl = this.mcs;
    // this.istc = (this.bls % this.mcs?parseInt(this.bls / this.mcs)-1:(this.bls - this.mcs)/this.mcs);
    if(localStorage.getItem('view') == 'messages'){
      $("#sb").toggleClass("side_bar");
      $("#mb").toggleClass("main_bar");
      this.tid = localStorage.getItem('tid');
      this.tap = localStorage.getItem('tap');
      this.tname = localStorage.getItem('tname');
      this.stc = parseInt(localStorage.getItem('stc')); 
      this.bl = parseInt(localStorage.getItem('bl'));
      $("#chat_name").html(this.tname);
      this.getMyMessages(this.tid, this.tap, this.tname,this.stc,this.bl,1,0);
    }else{
      localStorage.setItem('view',  'chats');
    }
    this.ws.url = this.urls.save_crid;
    this.app._token = sessionStorage.getItem('_token');
    this.ws._token = this.app._token;
    if(this.app._token){
      this.getMyChats();
    }
  }
  
  this.ws.host = "ws://localhost:8080";//temp
  // this.app['tap'] = "users";
  this.execute();
  // $("#message_space").scroll(function(){
  //     if($(this).scrollTop() === 0){
  //       if(localStorage.getItem('eom')!='eom'){
  //         stc = stc + 1;
  //         localStorage.setItem('stc',stc);
  //         // if(bl < bls){
  //           bl = bl + mcs
  //           // bl = Math.min(bl + mcs, bls);
  //           localStorage.setItem('bl',bl);
  //         // }
  //         scroll_pos = $(this)[0].scrollHeight;
  //         // scroll_b[stc]=scroll_pos;
  //         getMyMessages(tid,tap,tname,stc,bl,2);
  //       }
  //     }
  //     // if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
  //     //   if(stc > istc){
  //     //     stc = stc - 1;
  //     //     localStorage.setItem('stc',stc);
  //     //     scroll_pos = $(this).scrollTop()-$(this)[0].scrollHeight;
  //     //     getMyMessages(tid,tap,tname,stc,bl,0);
  //     //   }
  //     // }
  // });
</script>

<!-- <script>
  ws.host = "ws://localhost:8080";
  ws.url = urls.save_crid;
  ws.execute();
  function new_window(){
    window.open('http://localhost:8018/chat.html','_blank','width=400,height=500,menubar=no,toolbar=no');
  }
</script> -->
</body>
</html>