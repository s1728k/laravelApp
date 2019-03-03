@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10 login text-center align-middle">
      <h4>Welcome</h4>
      <h4>To</h4>
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
      <h3>Backend Solutions</h3>
      <h3>for</h3>
      <h3>Single Page Websites and Mobile Applications</h3>
    </div>
  </div><hr>
  <div class="row">
    <div class="col-md-12 text-center align-middle">
      <h3><a class="btn btn-primary btn-lg" href="{{route('c.auth.signup')}}">Get Started SignUp Now Freely</a></h3>
    </div>
  </div><hr>
  <div class="row text-center align-middle">
    <div class="col-md-3">
      <h3>Applications</h3>
      <p>Unlimited Applications</p>
      <p>Easily Switchable</p>
      <p>Changing App Secret is Easy</p>
      <p>Api Call Origin is Settable</p>
      <p>MySQL Export Option</p>
    </div>
    <div class="col-md-3">
      <h3>Licenses To Softwares</h3>
      <p>Unlimited Licenses To Each App</p>
      <p>Simple Api Call To Activate and Deactivate</p>
      <p>Server License (Many User Type) Available</p>
      <p>Easy To Upgrade To Any Users</p>
      <p>Easy To Use On Digital Downloads From Your Site</p>
    </div>
    <div class="col-md-3">
      <h3>Token Based Session</h3>
      <p>Session management is easy with _token variable</p>
      <p>App secret is only used in login and signup</p>
      <p>CSRF Protection is Achieved</p>
      <p>GET, POST, PUT, DELETE methods are allowed</p>
    </div>
    <div class="col-md-3">
      <h3>Api Virtual Database</h3>
      <p>Unlimited Tables In Each App</p>
      <p>Mostly Used Field Types</p>
      <p>Auth Provider Option</p>
      <p>Many Table Modification Options</p>
      <p>Automatic Validation</p>
    </div>
  </div>
  <div class="row text-center align-middle">
    <div class="col-md-3">
      <h3>Customized Queries</h3>
      <p>Unlimited Queries</p>
      <p>Easily Managable Queries</p>
      <p>Easily Managable Queries</p>
    </div>
    <div class="col-md-3">
      <h3>Files Server</h3>
      <p>Simple Upload Url</p>
      <p>Unique Url Sent To FrontEnd</p>
      <p>Direct Upload And Delete Options</p>
    </div>
    <div class="col-md-3">
      <h3>Emails @ Your Domain</h3>
      <p>Unlimited Domain Emails</p>
      <p>Easy Domain Name Verification</p>
      <p>Only MX record Pointing</p>
      <p>Popular Roundcube Mail Client</p>
      <p>Easily Linkable to Google Mail</p>
    </div>
    <div class="col-md-3">
      <h3>Cloud Messaging</h3>
    </div>
  </div><hr>
  <div class="row text-center align-middle">
    <p>Applications Buit On This Backend</p>
    <div class="col-md-3">
      <h3>Contact Forms For Agent</h3>
    </div>
  </div><hr>
</div>

<script src="public/js/service-worker.js"></script>
<script src="public/js/register_sw.js"></script>
<script>
  register_sw.save_subscription_url = "http://localhost:8003/push/save-subscription";
  register_sw.csrf_token = "{{csrf_token()}}";
  register_sw.execute();
  // function displayNotification() {
  //   if (Notification.permission == 'granted') {
  //     navigator.serviceWorker.getRegistration().then(function(reg) {
  //       reg.showNotification('Hello world!');
  //     });
  //   }
  // }
  // displayNotification();
</script>
@endsection