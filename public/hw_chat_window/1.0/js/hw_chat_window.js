var hwc = {
  'routes':"api",
  'logo':'<svg height="45" width="250"><text id="_hwc_first" x="0" y="28" fill="grey">Customer Care</text><text id="_hwc_second" x="0" y="42" fill="lightgrey">Delightful Web Creations</text>Customer Care</svg>',
  'template':'<div id="_hwc_chat_app_base" class="container-fluid _hwc_hidden"><div class="row _hwc_heading"><div class="col-md-12" id="app_name"><a >{{logo}}</a></div></div><div class="row"><div id="sb" class="col-md-12"><div class="row"><div class="col-md-12"><div class="input-group"><input id="cterm" type="text" class="form-control" placeholder="Search in chats" onkeypress="return hwc.searchChatMembers(event)"><div class="input-group-btn"><button class="btn btn-info" onclick="hwc.getMyChats()"><i class="fa fa-search"></i></button></div></div></div><div class="col-md-12"><button class="btn btn-info _hwc_new_conversation_button" onclick="hwc.startNewChat()">Start New Conversation</button></div></div><div class="row" id="my_chats"></div></div><div id="mb" class="col-md-12 _hwc_main_bar _hwc_main_bar1"><div class="row"><div class="col-md-12 _hwc_name_heading"><div class="_hwc_back_button_div"><i class="fa fa-angle-left _hwc_back_button" onclick="hwc.tS()"></i></div><div class="_hwc_name_display"><h3 id="chat_name">Your Name Here</h3></div></div></div><div class="row"><div class="col-md-12"></div><div class="col-md-12" id="_hwc_message_space" onscroll="hwc.scrolltopcapture(this)"></div></div><div class="row"><div class="col-md-12" ></div><div class="col-md-12 _hwc_msg_bar_div"><div class="_hwc_smile_icon_div"><i class="fa fa-smile-o _hwc_hover_icons"></i></div><div class="_hwc_msg_div"><textarea rows="1" id="_hwc_message" name="message" onkeyup="hwc.textAreaAdjust(this)" placeholder="Type a message here"></textarea></div><div class="_hwc_smile_icon_div"><i class="fa fa-send-o _hwc_hover_icons" onclick="hwc.sendMessage()"></i></div></div></div></div></div></div><button class="btn btn-info" id="_hwc_chat_app_buttton" onclick="hwc.toggleChat()">Chat</button>',
  'ws':{
      'conn':"",
      'host':"ws://honeyweb.org:8080",
      'url':"",
      '_token':"",
      'onMessage': function(data){},
      'execute':function(){
          try{
              hwc.ws.conn = new WebSocket(hwc.ws.host);
              hwc.ws.conn.onopen    = function(msg){ console.log("Connection established!"); };
              hwc.ws.conn.onmessage = function(msg){
                  data = JSON.parse(msg.data);
                  console.log(data);
                  if(data['type'] == 'crid'){
                      $.post(hwc.ws.url, {"chat_resource_id": data.data, "_token": hwc.ws._token, "_method":"PUT"}, function(res, status){
                          if(status == 'success'){
                              console.log('chat_resource_id '+data.data+' saved');
                              hwc.getMyChats();
                          }
                      });
                  }else if(data['type'] == 'get_chats'){
                      hwc.ws.onMessage(data.data);
                  }else if(data['type'] == 'get_messages'){
                      hwc.socketMessages(data.data);
                  }else if(data['type'] == 'chat_message'){
                      hwc.socketMessage(data.data);
                  }
              }
              hwc.ws.conn.onclose   = function(msg){ console.log("Connection closed!");  };
          }
          catch(e) {
              console.log(e); 
          }
      }
  },
  'urls':{
    "request_token":"http://localhost:8003/api/chat/request_token",
    "save_crid":"http://localhost:8003/api/chat/save_resource_id",
    "start_chat":"http://localhost:8003/api/chat/start_chat",
    "my_chats":"http://localhost:8003/api/chat/my_chats",
    "messages":"http://localhost:8003/api/chat/messages",
    "save_message":"http://localhost:8003/api/chat/save_message",
  },
  'app':{
    '_token':sessionStorage.getItem('_hwc__token'),
  },
  'csrf_token':"",
  'mcs':15,
  'bls':50,
  'istc':"",
  'scroll_pos':"",
  'scroll_b':{},
  'requestToken':function(){
    $.post(hwc.urls.request_token, hwc.app, function(data, status){
      if(status == 'success'){
        hwc.ws._token = data;
        hwc.app._token = data;
        sessionStorage.setItem('_hwc__token', data);
        hwc.ws.execute();
      }
    });
  },
  'startNewChat':function(){
    if(hwc.app._token){
      console.log(hwc.app._token);
      hwc.ws.onMessage = hwc.getMyChats;
      hwc.ws.execute();
      $.post(hwc.urls.start_chat, hwc.app, function(data, status){}).fail(function(e){
        if(e.status == 401){
          if(e.responseJSON['message'] == 'token expired'){
            sessionStorage.clear();hwc.app._token = 0;hwc.ws._token = 0;
          }
        }
      });
    }else if(hwc.routes == 'web'){
      hwc.app._token = hwc.csrf_token;
      hwc.requestToken();
    }else if(hwc.app['fap'] == 'guest'){
      hwc.requestToken();
    }
  },
  'getMyChats':function(){
    hwc.app['term'] = $("#cterm").val();
    hwc.app['command'] = 'get_chats';
    hwc.ws.onMessage = function(data){
      const template = '<div class="col-md-12 _hwc_chats" style="cursor: pointer;" onclick="hwc.msgView({{tid}},{{tap}},{{tname}})"><div style="display: inline-flex;width: 100%">  <image src="https://ui-avatars.com/api/?name={{urlname}}" style="border-radius: 20px;height: 40px; width: 40px;margin-top: 8px;"/>  <div style="position: relative; padding-left: 10px;width: 100%">    <h4>{{tname}}</h4>    <p style="position: absolute;top:30px;color:grey">{{lmsg}}</p>    <p style="position: absolute;top:15px;right:0px;color:grey">{{date}}</p>  </div></div></div>';
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
    if(hwc.ws.conn){hwc.ws.conn.send(JSON.stringify(hwc.app));}
  },
  'messages':[],
  'dateline':'<div class="row"><div class="col-md-12"><div style="display: flex;"><div style="width: calc(50% - 75px); height: 10px; border-bottom: 1px solid lightblue"></div><div style="width:150px; text-align: center; color:lightblue;">{{date}}</div><div style="width: calc(50% - 75px); height: 10px; border-bottom: 1px solid lightblue"></div></div></div></div><br>',
  'timeline_right':'<div class="row" id="m{{id}}"><div class="col-md-12"><div class="well well-sm" style="position: relative; display: inline-block;float: right; color:grey;margin-bottom:2px;margin-top:20px"><p style="position: absolute;bottom: 27px;right: 0px">{{time}}</p>{{message}}</div></div></div>',
  'timeline_left':'<div class="row" id="m{{id}}"><div class="col-md-12"><image src="https://ui-avatars.com/api/?name={{urlname}}" style="border-radius: 20px;height: 40px; width: 40px;"/><p style="position: absolute;bottom: 30px;left: 56px;color:grey">{{time}}</p><div class="well well-sm" style="position: relative; display: inline-block;color:grey;margin-bottom:2px;margin-top:20px">{{message}}</div></div></div>',
  'message_right':'<div class="row" id="m{{id}}"><div class="col-md-12"><div class="well well-sm" style="position: relative; display: inline-block;float:right;color:grey;margin-bottom:2px;">{{message}}</div></div></div>',
  'message_left':'<div class="row" id="m{{id}}"><div class="col-md-12"><div class="well well-sm" style="position: relative; display: inline-block;color:grey;margin-bottom:2px;margin-left:40px;">{{message}}</div></div></div>',
  'parseMessages':function(data){
    let t1 = ""; let t2 = ""; let html = "";
    Object.keys(data).forEach(function(date) {
      if(date == 'eom'){
        localStorage.setItem('_hwc_eom', 'eom');
        t1 = hwc.dateline.replace("{{date}}", "Here it ends");
      }else{
        t1 = hwc.dateline.replace("{{date}}", date);
        Object.keys(data[date]).forEach(function(si) {
          Object.keys(data[date][si]).forEach(function(time) {
            if(time.indexOf(localStorage.getItem('_hwc_tname'))!=-1){
              t2 = t2 + hwc.timeline_left.replace("{{time}}", time);
              t2 = t2.replace("{{urlname}}", time.split(' ').slice(0, -1).join('+'))
              t2 = t2.replace("{{message}}", data[date][si][time][0]['msg']);
              t2 = t2.replace("{{id}}", data[date][si][time][0]['id']);
              for (var i = 1; i < data[date][si][time].length; i++) {
                t2 = t2 + hwc.message_left.replace("{{message}}",data[date][si][time][i]['msg']);
                t2 = t2.replace("{{id}}", data[date][si][time][i]['id']);
              };
            }else{
              t2 = t2 + hwc.timeline_right.replace("{{time}}", time);
              t2 = t2.replace("{{message}}", data[date][si][time][0]['msg']);
              t2 = t2.replace("{{id}}", data[date][si][time][0]['id']);
              for (var i = 1; i < data[date][si][time].length; i++) {
                t2 = t2 + hwc.message_right.replace("{{message}}",data[date][si][time][i]['msg']);
                t2 = t2.replace("{{id}}", data[date][si][time][i]['id']);
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
  },
  'formatMessages':function(data=[]){
    let ar = []; let si = 1; let ph = 0; let t = 0;
    for(var i=data.length-1; i>=0; i--){
      const d = new Date(data[i]['created_at']);
      const month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October",  "November",  "December"]
      const date = d.getDate() + '-' + month[d.getMonth()] + '-' + d.getFullYear();
      const time = d.getHours() + ':' + ((d.getMinutes() > 9)?d.getMinutes():('0'+d.getMinutes()));
        ar[date] = ar[date]||[];
        if(data[i]['tid'] == localStorage.getItem('_hwc_tid') && data[i]['tap'] == localStorage.getItem('_hwc_tap')){
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
  },
  'scrollPos':function(offset){
    var objDiv = document.getElementById("_hwc_message_space");
    objDiv.scrollTop = objDiv.scrollHeight - offset ;
    if(hwc.stc>hwc.istc){
      hwc.stc = hwc.istc;
      localStorage.setItem('_hwc_stc',hwc.stc);
    }
  },
  'socketMessage':function(data){
    if((data['tap'] == hwc.tap && data['tid'] == hwc.tid)||(data['fap'] == hwc.tap && data['fid'] == hwc.tid)){
      hwc.messages.unshift(data);
      let tdata = hwc.formatMessages(hwc.messages);
      let html = hwc.parseMessages(tdata);
      $("#_hwc_message_space").html(html);
      hwc.scrollPos(0);
    }
  },
  'socketMessages':function(data){
    hwc.messages = data;
    let tdata = hwc.formatMessages(data);
    let html = hwc.parseMessages(tdata);
    $("#_hwc_message_space").html(html);
    hwc.scrollPos(hwc.scroll_pos);
  },
  'socketScrollMessages':function(data){
    if(data.length > hwc.bl){
      localStorage.setItem('_hwc_eom', 'eom');
    }else{
      localStorage.setItem('_hwc_eom', '');
    }
    hwc.messages = data;
    let tdata = hwc.formatMessages(data);
    let html = hwc.parseMessages(tdata);
    $("#_hwc_message_space").html(html);
    hwc.scrollPos(hwc.scroll_pos);
  },
  'getMyMessages':function(fid, fap, fname, stc = 0, bl = 10, scroll_triger = 0){
    hwc.app['tid'] = fid;
    hwc.app['tap'] = fap;
    hwc.app['tname'] = fname;
    hwc.app['stc'] = stc;
    hwc.app['bl'] = bl;
    hwc.app['mcs'] = this.mcs;
    hwc.app['term'] = "";
    hwc.app['command'] = "get_messages";
    if(scroll_triger){
      hwc.ws.onMessage = hwc.socketScrollMessages;
    }else{
      hwc.ws.onMessage = hwc.socketMessages;
    }
    if(hwc.ws.conn){hwc.ws.conn.send(JSON.stringify(hwc.app));}
  },
  'stc':0,'bl':"",'tid':"",'tap':"",'tname':"",
  'tS':function(){
    if(localStorage.getItem('_hwc_view') == 'chats'){
      localStorage.setItem('_hwc_view', 'messages');
    }else{
      localStorage.setItem('_hwc_view',  'chats');
    }
    $("#sb").toggleClass("_hwc_side_bar");
    $("#mb").toggleClass("_hwc_main_bar");
  },
  'msgView':function(tid, tap, tname){
    hwc.tid = tid;hwc.tap = tap;hwc.tname = tname;hwc.stc = 0;hwc.bl = hwc.mcs;
    localStorage.setItem('_hwc_tid',tid);localStorage.setItem('_hwc_tap',tap);localStorage.setItem('_hwc_tname',tname);
    localStorage.setItem('_hwc_stc',hwc.stc);localStorage.setItem('_hwc_bl',hwc.bl);
    hwc.tS();
    $("#chat_name").html(tname);
    hwc.getMyMessages(tid, tap, tname, hwc.stc, hwc.bl, 0);
  },
  'textAreaAdjust':function(o) {
    if(o.scrollHeight < 100){
      o.style.height = "1px";
      o.style.height = (o.scrollHeight)+"px";
    }
  },
  'searchChatMembers':function(e){
    if (e.keyCode == 13) {
        hwc.getMyChats();
        return false;
    }
  },
  'sendMessage':function(){
    hwc.app['message'] = $("#_hwc_message").val();
    hwc.app['tid']=hwc.tid; hwc.app['tap']=hwc.tap;hwc.app['command']='chat_message';
    hwc.ws.onMessage = hwc.socketMessage;
    if(hwc.ws.conn){hwc.ws.conn.send(JSON.stringify(hwc.app));}
  },
  'scrolltopcapture':function(o){
    if($(o).scrollTop() === 0){
      if(localStorage.getItem('_hwc_eom')!='eom'){
        hwc.stc = hwc.stc + 1;
        localStorage.setItem('_hwc_stc',hwc.stc);
        hwc.bl = hwc.bl + hwc.mcs;
        localStorage.setItem('_hwc_bl',hwc.bl);
        hwc.scroll_pos = $(o)[0].scrollHeight;
        hwc.getMyMessages(hwc.tid,hwc.tap,hwc.tname,hwc.stc,hwc.bl,1);
      }
    }
  },
  'toggleChat':function toggleChat(){
    $("#_hwc_chat_app_base").toggleClass("_hwc_hidden");
  },
  'execute':function(){
    if(hwc.routes == 'web'){
      hwc.urls['request_token'] = hwc.urls['request_token'].replace("/api/","/");
    }
    $("#hw_chat_app").html(hwc.template.replace("{{logo}}",hwc.logo));
    hwc.bl = hwc.mcs;
    hwc.istc = (hwc.bls % hwc.mcs?parseInt(hwc.bls / hwc.mcs)-1:(hwc.bls - hwc.mcs)/hwc.mcs);
    if(localStorage.getItem('_hwc_view') == 'messages'){
      $("#sb").toggleClass("_hwc_side_bar");
      $("#mb").toggleClass("_hwc_main_bar");
      hwc.tid = localStorage.getItem('_hwc_tid');
      hwc.tap = localStorage.getItem('_hwc_tap');
      hwc.tname = localStorage.getItem('_hwc_tname');
      hwc.stc = parseInt(localStorage.getItem('_hwc_stc')); 
      hwc.bl = parseInt(localStorage.getItem('_hwc_bl'));
      $("#chat_name").html(hwc.tname);
      hwc.getMyMessages(hwc.tid, hwc.tap, hwc.tname,hwc.stc,hwc.bl,1,0);
    }else{
      localStorage.setItem('_hwc_view',  'chats');
    }
    hwc.ws.url = hwc.urls.save_crid;
    hwc.app._token = sessionStorage.getItem('_hwc__token');
    hwc.ws._token = hwc.app._token;
  }
}