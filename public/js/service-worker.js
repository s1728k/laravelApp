'use strict';

self.addEventListener('push', function(event) {
  var push = event.data.json();
  var options = {};
  for(var p in push){
    if(p !== 'title'){
      if(push[p]){options[p] = push[p];}
    }
  }
  if(push['vibrate']){options['vibrate'] = push['vibrate'].split(',').map(x => Number(x));}
  if(push['actions']){
    options['actions'] = [];
    var temp = push['actions'].split('|');
    for (var i = 0; i < temp.length; i++) {
      var t = temp[i].split(',');
      var ob = {};
      ob['action'] = t[0];ob['title'] = t[1];ob['icon'] = t[2];
      options['actions'].push(ob);
    };
  }
  event.waitUntil(
    self.registration.showNotification(push['title'], options)
  );
});

self.addEventListener('notificationclick', function(event) {
  if (!event.action) {
    event.notification.close();
    return;
  }

  // event.waitUntil(clients.matchAll().then(all => all.map(client => client.postMessage(event.action))));

  event.waitUntil(clients.matchAll({
    type: 'window'
  }).then(function(clientList) {
    for (var i = 0; i < clientList.length; i++) {
      var client = clientList[i];
      console.log(client);
      if (client.url === event.action && 'focus' in client) {
        return client.focus();
      }
    }
    if (clients.openWindow) {
      return clients.openWindow(event.action);
    }
  }));
});