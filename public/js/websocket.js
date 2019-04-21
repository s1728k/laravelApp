var ws = {
    'conn':"",
    'host':"ws://honeyweb.org:8080",
    'url':"http://honeyweb.org/api/chat/save_resource_id",
    '_token':"",
    'onMessage': function(data){},
    'execute':function(){
        try{
            ws.conn = new WebSocket(ws.host);
            ws.conn.onopen    = function(msg){ console.log("Connection established!"); };
            ws.conn.onmessage = function(msg){
                data = JSON.parse(msg.data);
                if(typeof data !== 'number'){
                    ws.onMessage(data);
                }else{
                    $.post(ws.url, {"chat_resource_id": msg.data, "_token": sessionStorage.getItem('_token'), "_method":"PUT"}, function(data, status){
                        if(status == 'success'){
                            console.log('chat_resource_id '+msg.data+' saved');
                            hwc.ws.onMessage(data);
                        }
                    });
                }
            }
            ws.conn.onclose   = function(msg){ console.log("Connection closed!");  };
        }
        catch(e) {
            console.log(e); 
        }
    }
};