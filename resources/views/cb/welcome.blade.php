@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10 login text-center align-middle">
      <p>Welcome</p>
      <p>To</p>
      <h1 style="font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:grey">
        HoneyWeb.Org
      </h1>
      <p style="font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:lightgrey; letter-spacing: 4px;">Delightful Web Creations</p>
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 312.85 312.85" style="width: 100px; height:100px;">
<g>
  <path d="M146.216,0.146c-3.314-0.681-6.636,1.07-7.96,4.177l-48.22,113.148c-14.866,32.859-1.147,72.203,4.714,86.19l-57.86,47.961   c-1.478,1.225-2.397,2.996-2.549,4.909c-0.152,1.914,0.477,3.807,1.743,5.25l42.698,48.649c1.354,1.542,3.302,2.419,5.341,2.419   c0.12,0,0.24-0.003,0.36-0.009c2.169-0.11,4.168-1.205,5.428-2.974l60.574-85.014c0.857-1.203,1.318-2.644,1.319-4.121   l0.004-10.139c0.02-43.193,0.092-195.469,0.092-203.485C151.901,3.732,149.525,0.821,146.216,0.146z" style="fill: wheat;"></path>
  <path d="M278.509,256.531c-0.151-1.914-1.071-3.685-2.549-4.909l-57.861-47.961c5.862-13.987,19.581-53.331,4.714-86.19   L174.593,4.323c-1.323-3.107-4.651-4.857-7.96-4.177c-3.309,0.675-5.685,3.586-5.685,6.963c0,8.016,0.072,160.292,0.092,203.485   l0.004,10.139c0.001,1.477,0.461,2.917,1.319,4.121l60.574,85.014c1.26,1.768,3.259,2.864,5.428,2.974   c0.121,0.006,0.241,0.009,0.36,0.009c2.04,0,3.988-0.877,5.341-2.419l42.698-48.649   C278.032,260.338,278.661,258.445,278.509,256.531z" style="fill: wheat;"></path>
</g>
</svg>
      <h5>Backend Solutions</h5>
      <p>for</p>
      <p>Single Page Websites and Mobile Applications</p>
      <p>Licenses | Session | Database | Email | Assets Store | CDN | Chat | Cloud Messaging | Push Notifications | Code Obfuscation</p>
      <p>also</p>
      <p>Prebuild Applications</p>
      <a class="btn btn-primary" href="{{route('c.auth.signup')}}">Get Started</a><br><br>
      <form method="post" action="{{route("theme")}}">
        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
        <input type="hidden" name="uuid" id="uuid" value="fds"/>
        <input type="submit" class="btn btn-primary" name="theme" value="Bootstrap Theme" onclick="uuid()">
        <input type="submit" class="btn btn-primary" name="theme" value="Materialize Theme" onclick="uuid()">
      </form>
    </div>
  </div>
</div>
<script src="public/js/service-worker.js"></script>
<script>
  function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
      .replace(/\-/g, '+')
      .replace(/_/g, '/')
    ;
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
  }

  service_worker = "public/js/service-worker.js";
  save_subscription_url = "http://localhost:8003/push/save-subscription";
  function isSupported() {
    if (!('serviceWorker' in navigator)) {
      console.log("Service Worker isn't supported on this browser, disable or hide UI");
      return false;
    }
    if (!('PushManager' in window)) {
      console.log("Push isn't supported on this browser, disable or hide UI.");
      return false;
    }
    return true;
  };

  function askPermission() {
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
  };

  function registerServiceWorker() {
    return navigator.serviceWorker.register(service_worker)
    .then(function(registration) {
      console.log('Service worker successfully registered.');
      return registration;
    })
    .catch(function(err) {
      console.error('Unable to register service worker.', err);
    });
  };

  function subscribeUserToPush() {
    return navigator.serviceWorker.register(service_worker)
    .then(function(registration) {
      const subscribeOptions = {
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(
          'BORchmQjyQU0pSccjF6W0GsiPshuodMAzrkztfIq9HxaBU-uns-Bc7pw60E18bBYVP7RYKFviDaUqFBig9k-1zc'
        )
      };
      return registration.pushManager.subscribe(subscribeOptions);
    })
    .then(function(pushSubscription) {
      console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
      return pushSubscription;
    });
  };

  function sendSubscriptionToBackEnd(subscription) {
    subscription = JSON.stringify(subscription);
    subscription = JSON.parse(subscription);
    subscription['_token'] = "{{csrf_token()}}";
    console.log(subscription);
    $.post("{{route('c.push.save_subscription')}}", subscription, function(data, status){
      if(status == "success"){
        if(data.status == "success"){
          console.log("subscription saved");
        }else{
          console.log("subscription failed");
        }
      }else{
        console.log(status);
      }
    });
  };


  // if(isSupported()){
  //   registerServiceWorker();
  //   // var r = Notification.permission;
  //   askPermission().then(function() {
  //       alert("permission granted");
  //       subscribeUserToPush().then(function(result){
  //         console.log(JSON.stringify(result));
  //         sendSubscriptionToBackEnd(result);
  //       });
  //   });
  // }
  function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  }
  function uuid(){
    var uuid = uuidv4();
    $("#uuid").val(uuid);
    console.log(uuid);
  }
</script>
@endsection