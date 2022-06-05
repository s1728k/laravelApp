@extends("cb.layouts.app")

@section("custom_style")
<style>
  /*body {
    margin: 0;
    overflow: hidden;
  }*/
  /*https://i.gifer.com/J4o.gif*/
  /*https://cdn.dribbble.com/users/3457/screenshots/2006659/animated-svg.gif*/
  /*https://media-public.canva.com/MAC74xZfJBQ/1/screen_2x.jpg*/
  /*https://media-public.canva.com/MADGxu9FQa4/4/screen_2x.jpg*/
  /*https://media-public.canva.com/MADGxs-46VY/4/screen_2x.jpg*/
  /*https://media-public.canva.com/MAC4GikTzIQ/1/screen_2x.jpg*/
  /*https://media-public.canva.com/MAC4GikTzIQ/1/thumbnail_large.jpg*/
  /*https://media-public.canva.com/MADQEaHRgVE/1/thumbnail_large.jpg*/
  .jumbotron{
    background-color: white;
    border-radius:0px;
    color:#31B0D5;
  }

  #first{
    background:  url("https://media-public.canva.com/MADQEaHRgVE/1/thumbnail_large.jpg");
    background-repeat: no-repeat;
    background-size: 100% 100%;
    margin-top: -20px;
    margin-bottom: 20px;
    border-radius: 0px;
  }
  body{
    /*background-image: url('https://images.pexels.com/photos/220166/pexels-photo-220166.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940');*/
    background-color: #eee;
  }

  .path {
    stroke-dasharray: 1000;
    stroke-dashoffset: 1000;
    animation: dash 5s linear alternate infinite;
  }

  @keyframes dash {
    from {
      stroke-dashoffset: 1000;
    }
    to {
      stroke-dashoffset: 0;
    }
  }
  .fa-ficon {
    /*padding: 12.5px;*/
    font-size: 25px;
    /*width: 50px;*/
    text-align: center;
    text-decoration: none;
    margin: 5px 2px;
    border-radius: 50%;
    color: #3B5998;
  }

  .fa-social {
    padding: 12.5px;
    font-size: 25px;
    width: 50px;
    text-align: center;
    text-decoration: none;
    margin: 5px 2px;
    border-radius: 50%;
  }

  /* Add a hover effect if you want */
  .fa-social:hover {
    opacity: 0.7;
    text-decoration: none;
  }

  /* Set a specific color for each brand */

  /* Facebook */
  .fa-facebook {
    background: #3B5998;
    color: white;
  }

  /* Twitter */
  .fa-twitter {
    background: #55ACEE;
    color: white;
  }

  .fa-linkedin {
    background: #007bb5;
    color: white;
  }

  .fa-pinterest {
    background: #cb2027;
    color: white;
  }

  .fa-github {
    background: #000000;
    color: white;
  }

  #fcopywrite{
    background: #606060; padding: 10px 0px; color:white
  }

  #footer{
    background: var(--nav-bg-color); padding: 50px 0px; color:white
  }

  @media only screen and (min-width: 900px) {
    #faddress {
      text-align: left;
    }
    #fmission{
      text-align: right;
    }
  }

</style>
@endsection
@section("content")
<div class="container-fluid">
  <div class="row jumbotron" id="first">
    <div class="col-md-12 login text-center" style="color:maroon">
      {{-- <div class="jumbotron"> --}}
        <h4>Welcome</h4>
        <h4>To</h4>
        <h1 style="font-weight:bold; color:red">
          HoneyWeb.Org
        </h1>
        <p style="font-weight:bold; color:blue; letter-spacing: 4px;">Delightful Web Creations</p>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 312.85 312.85" style="width: 100px; height:100px;">
        <g>
          <path d="M146.216,0.146c-3.314-0.681-6.636,1.07-7.96,4.177l-48.22,113.148c-14.866,32.859-1.147,72.203,4.714,86.19l-57.86,47.961   c-1.478,1.225-2.397,2.996-2.549,4.909c-0.152,1.914,0.477,3.807,1.743,5.25l42.698,48.649c1.354,1.542,3.302,2.419,5.341,2.419   c0.12,0,0.24-0.003,0.36-0.009c2.169-0.11,4.168-1.205,5.428-2.974l60.574-85.014c0.857-1.203,1.318-2.644,1.319-4.121   l0.004-10.139c0.02-43.193,0.092-195.469,0.092-203.485C151.901,3.732,149.525,0.821,146.216,0.146z" style="fill: green;"></path>
          <path d="M278.509,256.531c-0.151-1.914-1.071-3.685-2.549-4.909l-57.861-47.961c5.862-13.987,19.581-53.331,4.714-86.19   L174.593,4.323c-1.323-3.107-4.651-4.857-7.96-4.177c-3.309,0.675-5.685,3.586-5.685,6.963c0,8.016,0.072,160.292,0.092,203.485   l0.004,10.139c0.001,1.477,0.461,2.917,1.319,4.121l60.574,85.014c1.26,1.768,3.259,2.864,5.428,2.974   c0.121,0.006,0.241,0.009,0.36,0.009c2.04,0,3.988-0.877,5.341-2.419l42.698-48.649   C278.032,260.338,278.661,258.445,278.509,256.531z" style="fill: green;"></path>
        </g>
        </svg>
        <h3>Backend Solutions</h3>
        <h3>for</h3>
        <h3>Mobile and Web Apps</h3>
        <h3><a class="btn btn-primary btn-lg" href="{{route('register')}}">Get Started SignUp Now Freely</a></h3>
      {{-- </div> --}}
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 text-center align-middle">
      <div class="jumbotron">
        <div class="row">
          <div class="col-md-6">
            <svg width="100%" height="100%" viewBox="0 0 410 220">
              <text x="100" y="30" fill="var(--nav-bg-color)" style="font-size: 10px;">HoneyWeb.Org</text>
              <path  fill="var(--nav-bg-color)" d="M100 50 l0 140 l80 0 l0 -140 l-80 0"/>
              <path  fill="var(--nav-bg-shade-color)" d="M105 55 l0 130 l70 0 l0 -130 l-70 0"/>
              <text x="114" y="80" fill="var(--nav-bg-color)" style="font-size: 10px;">Application</text>
              <path stroke="var(--nav-bg-color)" d="M170 100 L290 100"/>
              <text x="190" y="96" fill="var(--nav-bg-color)" style="font-size: 10px;">https</text>
              <text x="190" y="110" fill="var(--nav-bg-color)" style="font-size: 10px;">json data / file upload</text>
              <path stroke="var(--nav-bg-color)" d="M170 150 L290 150"/>
              <text x="190" y="146" fill="var(--nav-bg-color)" style="font-size: 10px;">wss</text>
              <text x="190" y="160" fill="var(--nav-bg-color)" style="font-size: 10px;">chat msg (json)</text>
              <path stroke="var(--nav-bg-color)" d="M110 180 L10 200"/>
              <text x="10" y="196" fill="var(--nav-bg-color)" transform="rotate(-11 10,196)" style="font-size: 10px;">chat messages</text>
              <path stroke="var(--nav-bg-color)" d="M110 155 L10 170"/>
              <text x="10" y="166" fill="var(--nav-bg-color)" transform="rotate(-9 10,166)" style="font-size: 10px;">push notifications</text>
              <path stroke="var(--nav-bg-color)" d="M110 130 L10 140"/>
              <text x="10" y="136" fill="var(--nav-bg-color)" transform="rotate(-6 10,136)" style="font-size: 10px;">email@domain.tld</text>
              <path stroke="var(--nav-bg-color)" d="M110 105 L10 110"/>
              <text x="10" y="106" fill="var(--nav-bg-color)" transform="rotate(-3 10,106)" style="font-size: 10px;">file store & server</text>
              <path stroke="var(--nav-bg-color)" d="M110 80 L10 80"/>
              <text x="10" y="76" fill="var(--nav-bg-color)" transform="rotate(0 10,76)" style="font-size: 10px;">json (Api endpoint)</text>
              <text x="10" y="30" fill="var(--nav-bg-color)" style="font-size: 10px;">To your clients</text>
              <path stroke="var(--nav-bg-color)" d="M250 10 L250 210"/>
              <text x="270" y="30" fill="var(--nav-bg-color)" style="font-size: 10px;">Your Front End Appplication</text>
              <path  fill="var(--nav-bg-color)" d="M300 60 l0 50 q0 10 10 10 l10 0 q10 0 10 -10 l0 -50 q0 -10 -10 -10 l-10 0 q-10 0 -10 10"/>
              <path  fill="var(--nav-bg-shade-color)" d="M305 60 l0 50 l20 0 l0 -50 l-20 0"/>
              <path  fill="var(--nav-bg-shade-color)" d="M310 55 q-1 1 0 2 l10 0 q1 -1 0 -2 l-10 0"/>
              <circle cx="315" fill="var(--nav-bg-shade-color)" cy="115" r="2" />
              <path  fill="var(--nav-bg-color)" d="M300 150 l0 30 q0 10 10 10 l50 0 q10 0 10 -10 l0 -30 q0 -10 -10 -10 l-50 0 q-10 0 -10 10"/>
              <path  fill="var(--nav-bg-shade-color)" d="M305 145 l0 35 l60 0 l0 -35 l-60 0"/>
              <path  fill="var(--nav-bg-color)" d="M323 186 l-3 10 l30 0 l-3 -10 l-30 0"/>
              <path  fill="var(--nav-bg-color)" d="M340 60 l0 50 q0 10 10 10 l30 0 q10 0 10 -10 l0 -50 q0 -10 -10 -10 l-30 0 q-10 0 -10 10"/>
              <path  fill="var(--nav-bg-shade-color)" d="M345 60 l0 50 l40 0 l0 -50 l-40 0"/>
              <path  fill="var(--nav-bg-shade-color)" d="M360 55 q-1 1 0 2 l10 0 q1 -1 0 -2 l-10 0"/>
              <circle cx="365" fill="var(--nav-bg-shade-color)" cy="115" r="2" />
            </svg>
          </div>
          <div class="col-md-6">
            <h1>Applications</h1>
            <p>Application represent <strong>backend application</strong> setup for your front end application. You can <strong>create any number</strong> of applications each identified by application <strong>secret</strong> and application <strong>ID</strong>. You <strong>can set origins</strong> for api requests. <strong>MySQL Export Option</strong> is provided for app data portability.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center align-middle">
      <div class="jumbotron">
        <div class="row">
          <div class="col-md-6 top_img">
            <svg width="100%" height="100%" fill="var(--nav-bg-color)" viewBox="0 0 410 220">
              <text x="20" y="20" fill="var(--nav-bg-color)" style="font-size: 10px;">HoneyWeb.Org</text>
              <path stroke="var(--nav-bg-color)" d="M50 30 L50 210 Z"/>
              <text x="320" y="20" fill="var(--nav-bg-color)" style="font-size: 10px;">Your Frontend App</text>
              <path stroke="var(--nav-bg-color)" d="M360 30 L360 210 Z"/>
              <text x="70" y="46" fill="var(--nav-bg-color)" style="font-size: 10px;">For all your non public guest routes like signup etc.</text>
              <text x="70" y="59" fill="var(--nav-bg-color)" style="font-size: 10px;">use app id and app secret</text>
              <path stroke="var(--nav-bg-color)" d="M360 50 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="86" fill="var(--nav-bg-color)" style="font-size: 10px;">For login use app id and secret</text>
              <path stroke="var(--nav-bg-color)" d="M360 90 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="106" fill="var(--nav-bg-color)" style="font-size: 10px;">login success get you _token value save it in session storage</text>
              <path stroke="var(--nav-bg-color)" d="M50 110 l310 0 l-10 -5 l0 10 l10 -5 Z"/>
              <text x="70" y="146" fill="var(--nav-bg-color)" style="font-size: 10px;">For all auth routes use _token</text>
              <path stroke="var(--nav-bg-color)" d="M360 150 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="186" fill="var(--nav-bg-color)" style="font-size: 10px;">Request a refresh token</text>
              <path stroke="var(--nav-bg-color)" d="M360 190 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="206" fill="var(--nav-bg-color)" style="font-size: 10px;">save new _token in session storage</text>
              <path stroke="var(--nav-bg-color)" d="M50 210 l310 0 l-10 -5 l0 10 l10 -5 Z"/>
            </svg>
          </div>
          <div class="col-md-6">
            <h1>Token Based Session</h1>
            <p>Session management is achieved with <strong>_token</strong> variable. _token hash is changed on <strong>refresh token</strong> request by user. App secret and app id is used only on login and signup. <strong>CSRF protection</strong> is also achieved. <strong>GET, POST</strong> methods are allowed. <strong>PUT and DELETE</strong> methods are allowed with <strong>method spoofing</strong>.</p>
          </div>
          <div class="col-md-6 btm_img">
            <svg width="100%" height="100%" fill="var(--nav-bg-color)" viewBox="0 0 410 220">
              <text x="20" y="20" fill="var(--nav-bg-color)" style="font-size: 10px;">HoneyWeb.Org</text>
              <path stroke="var(--nav-bg-color)" d="M50 30 L50 210 Z"/>
              <text x="320" y="20" fill="var(--nav-bg-color)" style="font-size: 10px;">Your Frontend App</text>
              <path stroke="var(--nav-bg-color)" d="M360 30 L360 210 Z"/>
              <text x="70" y="46" fill="var(--nav-bg-color)" style="font-size: 10px;">For all your non public guest routes like signup etc.</text>
              <text x="70" y="59" fill="var(--nav-bg-color)" style="font-size: 10px;">use app id and app secret</text>
              <path stroke="var(--nav-bg-color)" d="M360 50 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="86" fill="var(--nav-bg-color)" style="font-size: 10px;">For login use app id and secret</text>
              <path stroke="var(--nav-bg-color)" d="M360 90 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="106" fill="var(--nav-bg-color)" style="font-size: 10px;">login success get you _token value save it in session storage</text>
              <path stroke="var(--nav-bg-color)" d="M50 110 l310 0 l-10 -5 l0 10 l10 -5 Z"/>
              <text x="70" y="146" fill="var(--nav-bg-color)" style="font-size: 10px;">For all auth routes use _token</text>
              <path stroke="var(--nav-bg-color)" d="M360 150 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="186" fill="var(--nav-bg-color)" style="font-size: 10px;">Request a refresh token</text>
              <path stroke="var(--nav-bg-color)" d="M360 190 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="206" fill="var(--nav-bg-color)" style="font-size: 10px;">save new _token in session storage</text>
              <path stroke="var(--nav-bg-color)" d="M50 210 l310 0 l-10 -5 l0 10 l10 -5 Z"/>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center align-middle">
      <div class="jumbotron">
        <div class="row">
          <div class="col-md-6">
            <svg width="100%" height="100%" viewBox="0 0 410 220">
              <text x="8" y="198" fill="var(--nav-bg-color)" transform="rotate(-90 10,200)" style="font-size: 10px;">HoneyWeb.Org Managed Databases</text>
              <ellipse cx="30" cy="40" rx="20" ry="10" style="fill:var(--nav-bg-color);stroke:purple;stroke-width:0" />
              <path  fill="var(--nav-bg-color)" stroke="var(--nav-bg-color)" d="M10 46 l0 10 q20 12 40 0 l0 -10 q-20 12 -40 0"/>
              <path  fill="var(--nav-bg-color)" stroke="var(--nav-bg-color)" d="M10 58 l0 10 q20 12 40 0 l0 -10 q-20 12 -40 0"/>
              <path  fill="var(--nav-bg-color)" stroke="var(--nav-bg-color)" d="M10 70 l0 10 q20 12 40 0 l0 -10 q-20 12 -40 0"/>
              <polyline points="50,63 75,63 75,93 100,93"  style="fill:none;stroke:var(--nav-bg-color);stroke-width:1" />
              <ellipse cx="30" cy="100" rx="20" ry="10" style="fill:var(--nav-bg-color);stroke:purple;stroke-width:0" />
              <path  fill="var(--nav-bg-color)" stroke="var(--nav-bg-color)" d="M10 106 l0 10 q20 12 40 0 l0 -10 q-20 12 -40 0"/>
              <path  fill="var(--nav-bg-color)" stroke="var(--nav-bg-color)" d="M10 118 l0 10 q20 12 40 0 l0 -10 q-20 12 -40 0"/>
              <path  fill="var(--nav-bg-color)" stroke="var(--nav-bg-color)" d="M10 130 l0 10 q20 12 40 0 l0 -10 q-20 12 -40 0"/>
              <polyline points="50,123 100,123"  style="fill:none;stroke:var(--nav-bg-color);stroke-width:1" />
              <ellipse cx="30" cy="160" rx="20" ry="10" style="fill:var(--nav-bg-color);stroke:purple;stroke-width:0" />
              <path  fill="var(--nav-bg-color)" stroke="var(--nav-bg-color)" d="M10 166 l0 10 q20 12 40 0 l0 -10 q-20 12 -40 0"/>
              <path  fill="var(--nav-bg-color)" stroke="var(--nav-bg-color)" d="M10 178 l0 10 q20 12 40 0 l0 -10 q-20 12 -40 0"/>
              <path  fill="var(--nav-bg-color)" stroke="var(--nav-bg-color)" d="M10 190 l0 10 q20 12 40 0 l0 -10 q-20 12 -40 0"/>
              <polyline points="50,183 75,183 75,153 100,153"  style="fill:none;stroke:var(--nav-bg-color);stroke-width:1" />
              <text x="100" y="30" fill="var(--nav-bg-color)" style="font-size: 10px;">HoneyWeb.Org</text>
              <path  fill="var(--nav-bg-color)" d="M100 50 l0 140 l80 0 l0 -140 l-80 0"/>
              <path  fill="var(--nav-bg-shade-color)" d="M105 55 l0 130 l70 0 l0 -130 l-70 0"/>
              <text x="114" y="80" fill="var(--nav-bg-color)" style="font-size: 10px;">Application</text>
              <path stroke="var(--nav-bg-color)" d="M170 100 L290 100"/>
              <text x="190" y="96" fill="var(--nav-bg-color)" style="font-size: 10px;">https</text>
              <text x="190" y="110" fill="var(--nav-bg-color)" style="font-size: 10px;">json data</text>
              <path stroke="var(--nav-bg-color)" d="M250 10 L250 210"/>
              <text x="270" y="30" fill="var(--nav-bg-color)" style="font-size: 10px;">Your Front End Appplication</text>
              <path  fill="var(--nav-bg-color)" d="M300 60 l0 50 q0 10 10 10 l10 0 q10 0 10 -10 l0 -50 q0 -10 -10 -10 l-10 0 q-10 0 -10 10"/>
              <path  fill="var(--nav-bg-shade-color)" d="M305 60 l0 50 l20 0 l0 -50 l-20 0"/>
              <path  fill="var(--nav-bg-shade-color)" d="M310 55 q-1 1 0 2 l10 0 q1 -1 0 -2 l-10 0"/>
              <circle cx="315" fill="var(--nav-bg-shade-color)" cy="115" r="2" />
              <path  fill="var(--nav-bg-color)" d="M300 150 l0 30 q0 10 10 10 l50 0 q10 0 10 -10 l0 -30 q0 -10 -10 -10 l-50 0 q-10 0 -10 10"/>
              <path  fill="var(--nav-bg-shade-color)" d="M305 145 l0 35 l60 0 l0 -35 l-60 0"/>
              <path  fill="var(--nav-bg-color)" d="M323 186 l-3 10 l30 0 l-3 -10 l-30 0"/>
              <path  fill="var(--nav-bg-color)" d="M340 60 l0 50 q0 10 10 10 l30 0 q10 0 10 -10 l0 -50 q0 -10 -10 -10 l-30 0 q-10 0 -10 10"/>
              <path  fill="var(--nav-bg-shade-color)" d="M345 60 l0 50 l40 0 l0 -50 l-40 0"/>
              <path  fill="var(--nav-bg-shade-color)" d="M360 55 q-1 1 0 2 l10 0 q1 -1 0 -2 l-10 0"/>
              <circle cx="365" fill="var(--nav-bg-shade-color)" cy="115" r="2" />
            </svg>
          </div>
          <div class="col-md-6">
            <h1>Api Virtual Database</h1>
            <p>Create <strong>any number of sql tables</strong> in each app. <strong>Variety of field data types</strong> are supported. You can create table as <strong>auth provider</strong> also. Variety of sql <strong>table modification options</strong> are supported. Automatic <strong>data type validation</strong> is supported when saving a record into sql table.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center align-middle">
      <div class="jumbotron">
        <div class="row">
          <div class="col-md-6 top_img">
            <svg width="100%" height="100%" fill="var(--nav-bg-color)" viewBox="0 0 410 220">
              <path stroke="var(--nav-bg-color)" d="M110 30 q0 100 100 190 q100 -90 100 -190 q-70 -25 -100 -25 q-25 0 -100 25 Z"/>
              <text x="180" y="130" fill="var(--nav-bg-shade-color)" style="font-size: 120px;">?</text>
            </svg>
          </div>
          <div class="col-md-6">
            <h1>User Defined Queries</h1>
            <p>You can create <strong>any number of queries</strong>. queries are similar to <strong>routing</strong> in any framework. queries achieve <strong>authentication</strong>. You can define your own <strong>validation</strong> super to basic datatype validation. You can customize the <strong>validation error messages</strong>.</p>
          </div>
          <div class="col-md-6 btm_img">
            <svg width="100%" height="100%" fill="var(--nav-bg-color)" viewBox="0 0 410 220">
              <path stroke="var(--nav-bg-color)" d="M110 30 q0 100 100 190 q100 -90 100 -190 q-70 -25 -100 -25 q-25 0 -100 25 Z"/>
              <text x="180" y="130" fill="var(--nav-bg-shade-color)" style="font-size: 120px;">?</text>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center align-middle">
      <div class="jumbotron">
        <div class="row">
          <div class="col-md-6">
            <svg fill="var(--nav-bg-color)" width="100%" height="100%" viewBox="0 0 410 220">
              <text x="60" y="30" fill="var(--nav-bg-color)" style="font-size: 10px;">HoneyWeb.Org</text>
              <path d="M20 50 l0 150 l140 0 l0 -150 l-140 0"/>
              <path fill="var(--nav-bg-shade-color)" d="M25 55 l0 140 l130 0 l0 -140 l-130 0"/>
              <path stroke="var(--nav-bg-color)" d="M35 80 l0 100 l70 0 l0 -70 l-30 -30  Z"/>
              <path stroke="var(--nav-bg-shade-color)" d="M45 70 l0 100 l70 0 l0 -70 l-30 -30 l0 30 l30 0 l-30 -30  Z"/>
              <text x="45" y="190" fill="var(--nav-bg-color)" style="font-size: 10px;">File Server</text>
              <text x="100" y="80" style="font-size: 10px;">Application</text>
              <path stroke="var(--nav-bg-color)" d="M290 110 l-175 0 l10 5 l0 -10 l-10 5 Z"/>
              <path stroke="var(--nav-bg-color)" d="M115 130 l175 0 l-10 -5 l0 10 l10 -5 Z"/>
              <path stroke="var(--nav-bg-color)" d="M115 160 l175 0 l-10 -5 l0 10 l10 -5 Z"/>
              <text x="165" y="106" style="font-size: 10px;">file upload</text>
              <text x="165" y="126" style="font-size: 10px;">get unique file url</text>
              <text x="161" y="156" style="font-size: 10px;">unique file url to serve file</text>
              <path stroke="var(--nav-bg-color)" d="M250 10 L250 210"/>
              <text x="270" y="30" style="font-size: 10px;">Your Front End Appplication</text>
              <path d="M300 60 l0 50 q0 10 10 10 l10 0 q10 0 10 -10 l0 -50 q0 -10 -10 -10 l-10 0 q-10 0 -10 10"/>
              <path fill="var(--nav-bg-shade-color)" d="M305 60 l0 50 l20 0 l0 -50 l-20 0"/>
              <path fill="var(--nav-bg-shade-color)" d="M310 55 q-1 1 0 2 l10 0 q1 -1 0 -2 l-10 0"/>
              <circle fill="var(--nav-bg-shade-color)" cx="315" cy="115" r="2" />
              <path d="M300 150 l0 30 q0 10 10 10 l50 0 q10 0 10 -10 l0 -30 q0 -10 -10 -10 l-50 0 q-10 0 -10 10"/>
              <path fill="var(--nav-bg-shade-color)" d="M305 145 l0 35 l60 0 l0 -35 l-60 0"/>
              <path d="M323 186 l-3 10 l30 0 l-3 -10 l-30 0"/>
              <path d="M340 60 l0 50 q0 10 10 10 l30 0 q10 0 10 -10 l0 -50 q0 -10 -10 -10 l-30 0 q-10 0 -10 10"/>
              <path fill="var(--nav-bg-shade-color)" d="M345 60 l0 50 l40 0 l0 -50 l-40 0"/>
              <path fill="var(--nav-bg-shade-color)" d="M360 55 q-1 1 0 2 l10 0 q1 -1 0 -2 l-10 0"/>
              <circle fill="var(--nav-bg-shade-color)" cx="365" cy="115" r="2" />
            </svg>
          </div>
          <div class="col-md-6">
            <h1>File Server</h1>
            <p>We provide <strong>simple file/s upload url</strong> and upon successfull upload we return <strong>unique link to download</strong> or render. You can <strong>see, upload, download, replace and preview</strong> all the files from <strong>control panel</strong>.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center align-middle">
      <div class="jumbotron">
        <div class="row">
          <div class="col-md-6 top_img">
            <svg width="100%" height="100%" fill="var(--nav-bg-color)" viewBox="0 0 410 220">
              <path stroke="var(--nav-bg-color)" d="M10 210 l0 -190 q3 -10 12 0 l182 182 q6 6 12 0 l184 -184 q10 -10 10 10 l0 182 q0 10 -10 10 l-390 0 q-10 0 -10 -10 l0 -200 q0 -10 10 -10 l400 0 l0 5 l-400 0 q-5 0 -5 5 l0 200 q0 5 5 5 l390 0 q5 0 5 -5 l0 -190 l-185 185 q-10 10 -20 0 l-185 -185 l0 190 Z" />
              <text x="160" y="80" fill="var(--nav-bg-color)" style="font-size: 30px;">E - Mail</text>
              <text x="130" y="110" fill="var(--nav-bg-color)" style="font-size: 20px;">@yourdomain.tld</text>
              <text x="160" y="140" fill="var(--nav-bg-color)" style="font-size: 20px;">gmail client</text>
            </svg>
          </div>
          <div class="col-md-6">
            <h1>Emails @ Your Domain</h1>
            <p>You can create <strong>any number of email address</strong> @ your domain.tld. <strong>Domain name verification</strong> is done to ensure you own your domain. You need to <strong>point MX record</strong> to our ip address. You can use <strong>gmail client</strong> to send and receive mails. You can also send mails using <strong>simple api request</strong> from you mobile app.</p>
          </div>
          <div class="col-md-6 btm_img">
            <svg width="100%" height="100%" fill="var(--nav-bg-color)" viewBox="0 0 410 220">
              <path stroke="var(--nav-bg-color)" d="M10 210 l0 -190 q3 -10 12 0 l182 182 q6 6 12 0 l184 -184 q10 -10 10 10 l0 182 q0 10 -10 10 l-390 0 q-10 0 -10 -10 l0 -200 q0 -10 10 -10 l400 0 l0 5 l-400 0 q-5 0 -5 5 l0 200 q0 5 5 5 l390 0 q5 0 5 -5 l0 -190 l-185 185 q-10 10 -20 0 l-185 -185 l0 190 Z" />
              <text x="160" y="80" fill="var(--nav-bg-color)" style="font-size: 30px;">E - Mail</text>
              <text x="130" y="110" fill="var(--nav-bg-color)" style="font-size: 20px;">@yourdomain.tld</text>
              <text x="160" y="140" fill="var(--nav-bg-color)" style="font-size: 20px;">gmail client</text>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center align-middle">
      <div class="jumbotron">
        <div class="row">
          <div class="col-md-6">
            <svg width="100%" height="100%" fill="var(--nav-bg-color)" viewBox="0 0 410 220">
              <path fill="var(--nav-bg-shade-color)" stroke="var(--nav-bg-shade-color)" d="M30 10 l350 0 l0 130 l-350 0 l0 -130 Z"/>
              <path stroke="var(--nav-bg-color)" d="M30 140 l350 0 l0 80 l-350 0 l0 -80 Z"/>
              <path fill="var(--nav-bg-shade-color)" stroke="var(--nav-bg-shade-color)" d="M40 150 l40 0 l0 40 l-40 0 l0 -40 Z"/>
              <text x="190" y="80" fill="var(--nav-bg-color)" style="font-size: 10px;">Image</text>
              <text x="50" y="173" fill="var(--nav-bg-color)" style="font-size: 10px;">Logo</text>
              <text x="90" y="163" fill="var(--nav-bg-shade-color)" style="font-size: 20px;">Title comes here</text>
              <text x="90" y="173" fill="var(--nav-bg-shade-color)" style="font-size: 10px;">Body text comes here</text>
              <text x="90" y="183" fill="var(--nav-bg-shade-color)" style="font-size: 7px;">[time] * You Website Name</text>
              <path fill="var(--nav-bg-shade-color)" stroke="var(--nav-bg-shade-color)" d="M230 190 l130 0 l0 20 l-130 0 l0 -20 Z"/>
              <text x="260" y="203" fill="var(--nav-bg-color)" style="font-size: 10px;">Action Button</text>
            </svg>
          </div>
          <div class="col-md-6">
            <h1>Push Notifications</h1>
            <p>You can <strong>send push notifications</strong> to your app subscribers from your web/mobile app in <strong>simple api request</strong>. You can also send push notifications from our <strong>control panel</strong>. You can send push notifications to <strong>only selected users</strong> also.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center align-middle">
      <div class="jumbotron">
        <div class="row">
          <div class="col-md-6 top_img">
            <svg width="100%" height="100%" fill="var(--nav-bg-color)" viewBox="0 0 410 220">
              <path stroke="var(--nav-bg-color)" d="M150 10 l0 210 Z"/>
              <path stroke="var(--nav-bg-color)" d="M150 80 l260 0 Z"/>
              <path stroke="var(--nav-bg-color)" d="M150 150 l260 0 Z"/>
              <path stroke="var(--nav-bg-color)" d="M50 85 l5 -10 l-10 0 l5 10 l0 -40 Z"/>
              <path stroke="var(--nav-bg-color)" d="M50 45 l200 0 l-10 5 l0 -10 l10 5 Z"/>
              <path stroke="var(--nav-bg-color)" d="M50 145 l-5 10 l10 0 l-5 -10 l0 40 Z"/>
              <path stroke="var(--nav-bg-color)" d="M50 185 l200 0 l-10 5 l0 -10 l10 5 Z"/>
              <path stroke="var(--nav-bg-color)" d="M80 115 l10 5 l0 -10 l-10 5 l170 0 l-10 5 l0 -10 l10 5 Z"/>
              <text x="55" y="40" fill="var(--nav-bg-color)" style="font-size: 10px;">Chat Message Flow</text>
              <text x="55" y="180" fill="var(--nav-bg-color)" style="font-size: 10px;">Chat Message Flow</text>
              <text x="100" y="110" fill="var(--nav-bg-color)" style="font-size: 10px;">Chat Message Flow</text>
              <text x="10" y="20" fill="var(--nav-bg-color)" style="font-size: 10px;">HoneyWeb.Org</text>
              <circle cx="50" cy="115" r="30" stroke="var(--nav-bg-color)" />
              <text x="15" y="145" fill="var(--nav-bg-color)" transform="rotate(-90 15,145)" style="font-size: 10px;">Chat Server</text>
              <path stroke="var(--nav-bg-color)" d="M255 30 l50 0 q5 0 5 5 l0 20 q0 5 -5 5 l-35 0 l-10 10 l0 -10 l-5 0 q-5 0 -5 -5 l0 -20 q0 -5 5 -5 Z"/>
              <text x="180" y="20" fill="var(--nav-bg-color)" style="font-size: 10px;">Client 1 (We provide you embed in your website)</text>
              <path stroke="var(--nav-bg-color)" d="M255 100 l50 0 q5 0 5 5 l0 20 q0 5 -5 5 l-35 0 l-10 10 l0 -10 l-5 0 q-5 0 -5 -5 l0 -20 q0 -5 5 -5 Z"/>
              <text x="180" y="90" fill="var(--nav-bg-color)" style="font-size: 10px;">Client 2 (We provide you embed in your website)</text>
              <path stroke="var(--nav-bg-color)" d="M255 170 l50 0 q5 0 5 5 l0 20 q0 5 -5 5 l-35 0 l-10 10 l0 -10 l-5 0 q-5 0 -5 -5 l0 -20 q0 -5 5 -5 Z"/>
              <text x="180" y="160" fill="var(--nav-bg-color)" style="font-size: 10px;">Customer Care (We provide you use it)</text>
            </svg>
          </div>
          <div class="col-md-6">
            <h1>Chat Messaging</h1>
            <p>We provide <strong>chat window</strong> to embed in you application. We also provide <strong>customer care application</strong> to engage your clients. Owner level user can monitor chat messages and requests from our <strong>control panel</strong>. You can <strong>configure</strong> many other options in our control panel.</p>
          </div>
          <div class="col-md-6 btm_img">
            <svg width="100%" height="100%" fill="var(--nav-bg-color)" viewBox="0 0 410 220">
              <path stroke="var(--nav-bg-color)" d="M150 10 l0 210 Z"/>
              <path stroke="var(--nav-bg-color)" d="M150 80 l260 0 Z"/>
              <path stroke="var(--nav-bg-color)" d="M150 150 l260 0 Z"/>
              <path stroke="var(--nav-bg-color)" d="M50 85 l5 -10 l-10 0 l5 10 l0 -40 Z"/>
              <path stroke="var(--nav-bg-color)" d="M50 45 l200 0 l-10 5 l0 -10 l10 5 Z"/>
              <path stroke="var(--nav-bg-color)" d="M50 145 l-5 10 l10 0 l-5 -10 l0 40 Z"/>
              <path stroke="var(--nav-bg-color)" d="M50 185 l200 0 l-10 5 l0 -10 l10 5 Z"/>
              <path stroke="var(--nav-bg-color)" d="M80 115 l10 5 l0 -10 l-10 5 l170 0 l-10 5 l0 -10 l10 5 Z"/>
              <text x="55" y="40" fill="var(--nav-bg-color)" style="font-size: 10px;">Chat Message Flow</text>
              <text x="55" y="180" fill="var(--nav-bg-color)" style="font-size: 10px;">Chat Message Flow</text>
              <text x="100" y="110" fill="var(--nav-bg-color)" style="font-size: 10px;">Chat Message Flow</text>
              <text x="10" y="20" fill="var(--nav-bg-color)" style="font-size: 10px;">HoneyWeb.Org</text>
              <circle cx="50" cy="115" r="30" stroke="var(--nav-bg-color)" />
              <text x="15" y="145" fill="var(--nav-bg-color)" transform="rotate(-90 15,145)" style="font-size: 10px;">Chat Server</text>
              <path stroke="var(--nav-bg-color)" d="M255 30 l50 0 q5 0 5 5 l0 20 q0 5 -5 5 l-35 0 l-10 10 l0 -10 l-5 0 q-5 0 -5 -5 l0 -20 q0 -5 5 -5 Z"/>
              <text x="180" y="20" fill="var(--nav-bg-color)" style="font-size: 10px;">Client 1 (We provide you embed in your website)</text>
              <path stroke="var(--nav-bg-color)" d="M255 100 l50 0 q5 0 5 5 l0 20 q0 5 -5 5 l-35 0 l-10 10 l0 -10 l-5 0 q-5 0 -5 -5 l0 -20 q0 -5 5 -5 Z"/>
              <text x="180" y="90" fill="var(--nav-bg-color)" style="font-size: 10px;">Client 2 (We provide you embed in your website)</text>
              <path stroke="var(--nav-bg-color)" d="M255 170 l50 0 q5 0 5 5 l0 20 q0 5 -5 5 l-35 0 l-10 10 l0 -10 l-5 0 q-5 0 -5 -5 l0 -20 q0 -5 5 -5 Z"/>
              <text x="180" y="160" fill="var(--nav-bg-color)" style="font-size: 10px;">Customer Care (We provide you use it)</text>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center align-middle">
      <div class="jumbotron">
        <div class="row">
          <div class="col-md-6">
            <svg width="100%" height="100%" fill="var(--nav-bg-color)" viewBox="0 0 410 220">
              <text x="20" y="20" fill="var(--nav-bg-color)" style="font-size: 10px;">HoneyWeb.Org</text>
              <path stroke="var(--nav-bg-color)" d="M50 30 L50 210 Z"/>
              <text x="280" y="20" fill="var(--nav-bg-color)" style="font-size: 10px;">Your digital downloads App</text>
              <path stroke="var(--nav-bg-color)" d="M360 30 l0 50 Z"/>
              <text x="320" y="110" fill="var(--nav-bg-color)" style="font-size: 10px;">Your software</text>
              <path stroke="var(--nav-bg-color)" d="M360 120 l0 90 Z"/>
              <text x="70" y="46" fill="var(--nav-bg-color)" style="font-size: 10px;">Request for license key and serial number</text>
              <path stroke="var(--nav-bg-color)" d="M360 50 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="66" fill="var(--nav-bg-color)" style="font-size: 10px;">Grants license key and serial number. Give it your client</text>
              <path stroke="var(--nav-bg-color)" d="M50 70 l310 0 l-10 -5 l0 10 l10 -5 Z"/>
              <text x="70" y="126" fill="var(--nav-bg-color)" style="font-size: 10px;">client uses license key and serial number to activate license</text>
              <path stroke="var(--nav-bg-color)" d="M360 130 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="146" fill="var(--nav-bg-color)" style="font-size: 10px;">client gets acknowledgement for activation</text>
              <path stroke="var(--nav-bg-color)" d="M50 150 l310 0 l-10 -5 l0 10 l10 -5 Z"/>
              <text x="70" y="186" fill="var(--nav-bg-color)" style="font-size: 10px;">client uses license key and serial number to de-activate license</text>
              <path stroke="var(--nav-bg-color)" d="M360 190 l-310 0 l10 5 l0 -10 l-10 5 Z"/>
              <text x="70" y="206" fill="var(--nav-bg-color)" style="font-size: 10px;">client gets acknowledgement for de-activation</text>
              <path stroke="var(--nav-bg-color)" d="M50 210 l310 0 l-10 -5 l0 10 l10 -5 Z"/>
            </svg>
          </div>
          <div class="col-md-6">
            <h1>Licenses To Softwares</h1>
            <p>We provide licensing service to your softwares. You can <strong>create</strong> licenses manually from <strong>control panel</strong> or from <strong>api request</strong> from your <strong>digital downloads</strong> website. You can <strong>activate and deactivate</strong> license in simple api request from your software. You can create <strong>server type license</strong> for many users. You can <strong>upgrade the license</strong> from control panel.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="footer" class="row text-center">
    <div class="col-md-4" id="fmission">
      <h3>Honeyweb Internet LLP</h3>
      <br><br><p><strong>Mission:</strong> Our mission is to serve our customers with cloud products to meet their needs in cost effective manner.</p>
      <br><br><p><strong>Vision:</strong> Our vision is to deliver best cloud based services to customers to meet their needs in cost effective manner without compromising quality.</p>
    </div>
    <div class="col-md-4">
      <h3>Navigational</h3>
      <p><a style="color:white" href="{{route('c.blog.home')}}"><i class="fa fa-edit"></i> Blog</a></p>
      <p><a style="color:white" href="{{route('c.docs.home')}}"><i class="fa fa-book"></i> Docs</a></p>
      <p><a style="color:white" href="{{route('register')}}"><i class="fa fa-user"></i> Sign Up</a></p>
      <p><a style="color:white" href="{{route('login')}}"><i class="fa fa-sign-in"></i> Login</a></p>
    </div>
    <div class="col-md-4" id="faddress">
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-1">
          <i class="fa-ficon fa fa-map-marker"></i>
        </div>
        <div class="col-md-9" style="margin-top: 10px">
          <p>#72, Kittaganahalli Village</p>
          <p>Bommasandra Industrial Area Post</p>
          <p>Bangalore, Karnataka, India</p>
        </div>
      </div><br>
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-1">
          <i class="fa-ficon fa fa-phone"></i>
        </div>
        <div class="col-md-9" style="margin-top: 10px">
          <p>+91 8904993723</p>
        </div>
      </div><br>
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-1">
          <i class="fa-ficon fa fa-envelope"></i>
        </div>
        <div class="col-md-9" style="margin-top: 10px">
          <p>info@honeyweb.org</p>
        </div>
      </div><br>
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-1">
          <i class="fa-ficon fa fa-clock-o"></i>
        </div>
        <div class="col-md-9" style="margin-top: 10px">
          <p> Saturday — Sunday</p>
          <p> 8:00am - 5:00pm</p>
        </div>
      </div><br><br>
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
          <a href="javascript:void(0)" class="fa-social fa fa-facebook"></a>
          <a href="javascript:void(0)" class="fa-social fa fa-twitter"></a>
          <a href="javascript:void(0)" class="fa-social fa fa-linkedin"></a>
          <a href="javascript:void(0)" class="fa-social fa fa-github"></a>
          <a href="javascript:void(0)" class="fa-social fa fa-pinterest"></a>
        </div>
      </div>
    </div>
  </div>
  <div id="fcopywrite" class="row text-center">
    <div class="col-md-12">
      <p>Copyright ©2019 All rights reserved | Honeyweb Internet LLP, Bangalore, Karnataka, India</p>
      <p>Note:- The website is under review please contact admin@honeyweb.org</p>
    </div>
  </div>
 {{--  <div class="row text-center align-middle">
    <p>Applications Buit On This Backend</p>
    <div class="col-md-6">
      <h1>Contact Forms For Agent</h1>
    </div>
  </div> --}}
</div>
{{-- <div class="row">
  <div class="col-md-12">
    <textarea></textarea>
  </div>
</div>
<div class="row">
  <button class="btn btn-default" onclick="testMark()">TestMark</button>
  <div class="col-md-12" id="html_mark">
  </div>
</div> --}}

<script src="public/js/register_sw.js"></script>
<script>
  register_sw.service_worker = "{{env('APP_URL')}}/public/js/service-worker.js";
  register_sw.save_subscription_url = "{{env('APP_URL')}}/push/save-subscription";
  register_sw.csrf_token = "{{csrf_token()}}";
  register_sw.execute();

  navigator.geolocation.getCurrentPosition(function(position){
    console.log(position.coords.latitude);
    console.log(position.coords.longitude);
  })
</script>

@endsection
