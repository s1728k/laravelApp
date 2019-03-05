<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Backend Solutions for Single Page Websites and Mobile Applications Licenses | Session | Database | Email | Assets Store | CDN | Chat | Cloud Messaging | Push Notifications | Code Obfuscation also Prebuild Applications">

  <title>HoneyWeb.Org - Delightful Web Creations</title>
  <base href="/">
  {{-- <link href="{{ asset('images/honeyweb.svg') }}" rel="icon" type="image/x-icon" >
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script> --}}
  <link rel="icon" type="image/x-icon" href="public/images/honeyweb.svg">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    tbody tr:hover{
      background: #f9f9f9;
    }
    .inputfile {
      width: 0.1px;
      height: 0.1px;
      opacity: 0;
      overflow: hidden;
      position: absolute;
      z-index: -1;
    }
    .link{
      font-weight: normal;
    }
    .is-invalid{
      background: #FAFFBD;
    }
    #doc_h_u{
      list-style: none;
    }
    .sublist{
      list-style: none;
      padding-left: 10px;
    }
    .doc_h{
      border-bottom: 1px dashed grey;
      color:grey;
      font-size: 15px;
    }
    .doc_h:hover{
      cursor: pointer;
      color:darkgrey;
    }
    .doc_h::after{
      content: "\f0da";
      font-family: "FontAwesome";
      width: 5px;
      height: 5px;
      margin-right: 5px;
      float: right;
    }
    .doc_h.is-active::after{
      content: "\f0d7";
    }
    .hidden{
      display: none;
    }
    @media screen and (max-width: 810px) {
      #doc_h_u  {
          margin-left:0px;
          padding-left: 0px;
      }
    }
    .loader {
        position: absolute;
        right:50%;
        top:40vh;
        display:none;
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span> 
        </button>
        <a href="{{route('c.welcome')}}"><svg height="45" width="250">
            <text x="0" y="28" fill="grey" style="font-size:27px; font-weight:bold; font-family:Arial, Helvetica, sans-serif">HoneyWeb.Org</text>
            <text x="0" y="42" fill="lightgrey" style="font-size:9px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; letter-spacing: .38rem;">Delightful Web Creations</text>
          HoneyWeb.Org
        </svg></a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-right">
        @guest
          <li><a href="{{route('c.docs.routemap')}}"><i class="fa fa-book"></i> Docs</a></li>
          <li><a href="{{route('register')}}"><i class="fa fa-user"></i> Sign Up</a></li>
          <li><a href="{{route('login')}}"><i class="fa fa-sign-in"></i> Login</a></li>
        @else
          <li><a href="{{route('c.app.list.view')}}"><i class="fa fa-desktop"></i> MyApps</a></li>
          @if(!in_array('Licenses', json_decode(\Auth::user()->hidden_modules,true)??[]))<li><a href="{{route('l.license.list.view')}}"><i class="fa fa-lock"></i> Licenses</a></li>@endif
          <li><a href="{{route('c.table.list.view')}}"><i class="fa fa-database"></i> Tables</a></li>
          <li><a href="{{route('c.query.list.view')}}"><i class="fa fa-search"></i> Queries</a></li>
          <li><a href="{{route('c.files.view')}}"><i class="fa fa-file"></i> Files</a></li>
          <li><a href="{{route('c.email.list.view')}}"><i class="fa fa-envelope"></i> Email</a></li>
          <li><a href="{{route('c.files.view')}}"><i class="fa fa-bullhorn"></i> WebPsuh</a></li>
          {{-- <li><a href="{{route('c.files.view')}}"><i class="fa fa-comments"></i> WebSockets</a></li>
          <li><a href="{{route('c.files.view')}}"><i class="fa fa-video-camera"></i> WebRTC</a></li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-code"></i> Obfuscator
            <span class="caret"></span></a>
            <ul class="dropdown-menu">  
              <li><a href="{{route('c.obfu.vba')}}">VBA</a></li>
            </ul>
          </li> --}}
          
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-user"></i> {{\Auth::user()->name}}
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="{{route('c.docs.routemap')}}"><i class="glyphicon glyphicon-education"></i> Docs</a></li>
              <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  <input type="hidden" name="_token" value="{{csrf_token()}}">
              </form>
            </ul>
          </li>
          @endguest
      </ul>
    </div>
</nav>
  
</div>
</div>

@yield("content")

<div class="loader"></div>

{{-- <script type="text/javascript">
    $(document).ready(function() {
        $('.multi-select-demo').multiselect({
            selectAllValue: 'multiselect-all',
            enableCaseInsensitiveFiltering: true,
            enableFiltering: true,
            maxHeight: '300',
            buttonWidth: '235',
            onChange: function(element, checked) {
                var brands = $('.multi-select-demo option:selected');
                var selected = [];
                $(brands).each(function(index, brand){
                    selected.push([$(this).val()]);
                });
                console.log(selected);
            }
        });
    });
</script> --}}
</body>
</html>