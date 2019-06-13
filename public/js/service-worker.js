'use strict';

self.addEventListener('push', function(event) {
  var push = event.data.json();
  var title = push['title'];
  var options = push;
  delete options['title'];
  if(options['vibrate']){options['vibrate'] = options['vibrate'].map(x => Number(x));}
  event.waitUntil(
    self.registration.showNotification(title, options)
  );
});

self.addEventListener('notificationclick', function(event) {
  if (!event.action) {
    clients.openWindow('/');
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