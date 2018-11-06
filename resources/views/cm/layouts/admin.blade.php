<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>HoneyWeb.Org - Delightful Web Creations</title>
  <base href="/">

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="public/images/honeyweb.svg">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
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
    div.ths {
        white-space: nowrap; 
        overflow: hidden;
        text-overflow:ellipsis;
    }
    div.ths:hover {
        overflow: visible;
        white-space: -moz-pre-wrap !important;  /* Mozilla, since 1999 */
        white-space: -webkit-pre-wrap; /*Chrome & Safari */ 
        white-space: -pre-wrap;      /* Opera 4-6 */
        white-space: -o-pre-wrap;    /* Opera 7 */
        white-space: pre-wrap;       /* css-3 */
        word-wrap: break-word;       /* Internet Explorer 5.5+ */
        word-break: break-all;
        white-space: normal;
    }
    .input-field .prefix.active {
       color: #1976D2;
     }
  </style>
</head>
<body>
<div class="row">
<ul id="dropdown1" class="dropdown-content">
  <li><a href="{{route('c.docs.routemap')}}"><span class="fa fa-book"></span> Docs</a></li>
  <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();"><span class="fa fa-sign-out"></span> Logout</a></li>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      <input type="hidden" name="_token" value="{{csrf_token()}}">
  </form>
</ul>
<div class="navbar-fixed">
  <nav class="blue darken-2">
      <div class="nav-wrapper">
        <a class="brand-logo hide-on-large-only" href="{{route('c.welcome')}}"><svg height="45" width="250">
            <text x="0" y="30" fill="yellow" style="font-size:27px; font-weight:bold; font-family:Arial, Helvetica, sans-serif">HoneyWeb.Org</text>
            <text x="0" y="44" fill="pink" style="font-size:9px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; letter-spacing: 3.8px;">Delightful Web Creations</text>
          HoneyWeb.Org
        </svg></a>
        <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only" style="float:right"><i class="material-icons">menu</i></a>
        <a class="brand-logo hide-on-med-and-down" href="{{route('c.welcome')}}"><svg height="45" width="250">
            <text x="20" y="30" fill="yellow" style="font-size:27px; font-weight:bold; font-family:Arial, Helvetica, sans-serif">HoneyWeb.Org</text>
            <text x="20" y="44" fill="pink" style="font-size:9px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; letter-spacing: 3.8px;">Delightful Web Creations</text>
          HoneyWeb.Org
        </svg></a>
        <ul class="right hide-on-med-and-down">
          @guest
            <li><a href="{{route('c.auth.admin.signup')}}"><i class="fa fa-user"></i> Admin Sign Up</a></li>
            <li><a href="{{route('c.auth.admin.login')}}"><i class="fa fa-sign-in"></i> Admin Login</a></li>
          @else
            <li><a href="{{route('c.admin.daily.logs')}}"><i class="fa fa-desktop"></i> Daily Logs</a></li>
            <li><a href="{{route('c.admin.visitors')}}"><i class="fa fa-desktop"></i> Visitors</a></li>
            <li><a href="{{route('c.admin.daily.logs')}}"><i class="fa fa-desktop"></i> Error Report</a></li>
            <li><a class="dropdown-trigger" href="#!" data-target="dropdown1">{{\Auth::user()->name}}<i class="material-icons right">arrow_drop_down</i></a></li>
            @endguest
        </ul>
      </div>
  </nav>
</div>
</div>
<ul id="slide-out" class="sidenav">
  <li><div class="user-view blue darken-2">
    <div class="background" style="position: relative; z-index: 10">
      <a class="brand-logo hide-on-large-only" href="{{route('c.welcome')}}"><svg height="45" width="250">
          <text x="30" y="30" fill="yellow" style="font-size:27px; font-weight:bold; font-family:Arial, Helvetica, sans-serif">HoneyWeb.Org</text>
          <text x="30" y="44" fill="pink" style="font-size:9px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; letter-spacing: 3.8px;">Delightful Web Creations</text>
        HoneyWeb.Org
      </svg></a>
    </div>
    @guest @else
    <div class="row">
      <div class="col s2">{{-- <img class="circle" src="https://via.placeholder.com/350x150"> --}}</div>
      <div class="col s8">
        <div class="row">
          <span class="white-text name">{{\Auth::user()->name}}</span>
          <span class="white-text email">{{\Auth::user()->email}}</span>
        </div>
      </div>
    </div>
    @endguest
  </div></li>
  @guest
    <li><a href="{{route('c.auth.admin.signup')}}"><i class="fa fa-user"></i> Admin Sign Up</a></li>
    <li><a href="{{route('c.auth.admin.login')}}"><i class="fa fa-sign-in"></i> Admin Login</a></li>
  @else
    <li><a href="{{route('c.docs.routemap')}}"><i class="fa fa-book"></i> Docs</a></li>
    <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> Logout</a></li>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
    </form>
    <li><div class="divider"></div></li>
    <li><a href="{{route('c.admin.daily.logs')}}"><i class="fa fa-desktop"></i> Daily Logs</a></li>
    <li><a href="{{route('c.admin.visitors')}}"><i class="fa fa-desktop"></i> Visitors</a></li>
    <li><a href="{{route('c.admin.daily.logs')}}"><i class="fa fa-desktop"></i> Error Report</a></li>
  @endguest
</ul>
@yield("content")
<div class="loader"></div>
<script type="text/javascript">
  $(document).ready(function(){
    $('select').formSelect();
  });
  $(".dropdown-trigger").dropdown();
  $('.modal').modal();
  $('.sidenav').sidenav();
  M.updateTextFields();
</script>
</body>
</html>