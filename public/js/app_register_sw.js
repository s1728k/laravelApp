var app_register_sw = {
  'service_worker':"public/js/service-worker.js",
  'save_subscription_url':"http://localhost:8003/push/save-subscription",
  'urlBase64ToUint8Array':function (base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
      .replace(/\-/g, '+')
      .replace(/_/g, '/')
    ;
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
  },
  'isSupported':function () {
    if (!('serviceWorker' in navigator)) {
      console.log("Service Worker isn't supported on this browser, disable or hide UI");
      return false;
    }
    if (!('PushManager' in window)) {
      console.log("Push isn't supported on this browser, disable or hide UI.");
      return false;
    }
    return true;
  },
  'askPermission':function () {
    return new Promise(function(resolve, reject) {
      const permissionResult = Notification.requestPermission(function(result) {
        resolve(result);
      });

      if (permissionResult) {
        permissionResult.then(resolve, reject);
      }
    })
    .then(function(permissionResult) {
      if (permissionResult !== 'granted') {
        throw new Error('We weren\'t granted permission.');
      }
    });
  },
  'registerServiceWorker':function () {
    return navigator.serviceWorker.register(app_register_sw.service_worker)
    .then(function(registration) {
      return registration;
    })
    .catch(function(err) {
      console.error('Unable to register service worker.', err);
    });
  },
  'subscribeUserToPush':function () {
    return navigator.serviceWorker.register(app_register_sw.service_worker)
    .then(function(registration) {
      const subscribeOptions = {
        userVisibleOnly: true,
        applicationServerKey: app_register_sw.urlBase64ToUint8Array(
          'BORchmQjyQU0pSccjF6W0GsiPshuodMAzrkztfIq9HxaBU-uns-Bc7pw60E18bBYVP7RYKFviDaUqFBig9k-1zc'
        )
      };
      return registration.pushManager.subscribe(subscribeOptions);
    })
    .then(function(pushSubscription) {
      return pushSubscription;
    });
  },
  'sendSubscriptionToBackEnd':function (subscription) {
    subscription = JSON.stringify(subscription);
    subscription = JSON.parse(subscription);
    var postBody = {};
    postBody['subscription'] = subscription;
    $.ajax({
      'type':'post',
      'url':app_register_sw['save_subscription_url'], 
      'data':postBody,
      'success':function(data, status){
        if(status == "success"){
        }else{
        }
      }
    });
  },
  'execute':function(){
    if(app_register_sw.isSupported()){
      app_register_sw.registerServiceWorker();
      app_register_sw.askPermission().then(function() {
          app_register_sw.subscribeUserToPush().then(function(result){
            app_register_sw.sendSubscriptionToBackEnd(result);
          });
      });
    }
  }
  
};
