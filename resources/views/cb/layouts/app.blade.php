@php
  $arr_fonts = ['ABeeZee', 'Bitter', 'Brawler', 'Buenard', 'Courgette', 'Delius', 'Fenix', 'Gudea', 'Halant', 'Heebo', 'Hind', 'K2D', 'Khula', 'Lora', 'Delius', 'Encode Sans', 'Esteban', 'Laila', 'Mukta', 'Patua One', 'Pavanam', 'Roboto', 'Sniglet', 'Strait'];
    $t = (strtotime(date("Y-m-d"))-strtotime("2010-01-01"))/86400 % count($arr_fonts);
    $font_family = $arr_fonts[$t];

  $color = ['background-color'=>'#F8F8F8','border-color'=>'#E7E7E7','default'=>'#777','hover'=>'#333','bhover'=>'#5E5E5E','active'=>'#555','active-background'=>'#D5D5D5', 'default-background'=>'white'];
  $color = ['background-color'=>'#31B0D5','border-color'=>'#7DC4F5','default'=>'white','hover'=>'#e1e1e1','bhover'=>'#5E5E5E','active'=>'#d3d3d3','active-background'=>'#006BFF', 'default-background'=>'white'];
@endphp

<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
  <meta name="description" content="Backend Solutions for Single Page Websites and Mobile Applications Session | Database | Email | Assets Store | CDN | Chat | Cloud Messaging | Push Notifications | Licenses also Prebuild Applications">

  <title>HoneyWeb.Org - Delightful Web Creations</title>
  <meta name="keywords" content="Licenses,Database,Email,Files,CDN,Chat,Push Notifications" />
  <meta name="robots" content="index,follow,archive" />

  <meta name="geo.position" content="," />
  <meta name="geo.placename" content="" />
  <meta name="geo.region" content="" />

  <base href="/">
  {{-- <link href="{{ asset('images/honeyweb.svg') }}" rel="icon" type="image/x-icon" >
  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script> --}}
  {{-- ABeeZee, Bitter, Brawler, Buenard, Courgette, Delius, Fenix, Gudea, Halant, Heebo, Hind, K2D, Khula, Lora --}}
  {{-- Delius, Encode Sans, Esteban, Laila, Mukta, Patua One, Pavanam, Roboto, Sniglet, Strait --}}
  <link rel="icon" type="image/x-icon" href="public/images/honeyweb_icon.png">
  <link href='https://fonts.googleapis.com/css?family={{$font_family}}' rel='stylesheet'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    body {
        font-family:{{$font_family}}; font-size: 15px;
    }
    a.btn, button.btn{
      border-radius: 0px;
    }
    a.btn-default, button.btn-default{
      background-color: {{$color['default']}};
      color:{{$color['background-color']}};
    }
    a.btn-default:hover, button.btn-default:hover{
      border-color: {{$color['background-color']}};
      background-color: {{$color['background-color']}};
      color:{{$color['default']}};
    }
    a.btn-default:active, button.btn-default:active{
      border-color: {{$color['background-color']}} !important;
      background-color: {{$color['background-color']}} !important;
      color:{{$color['default']}} !important;
    }
    a.btn-default:focus, button.btn-default:focus{
      border-color: {{$color['background-color']}} !important;
      background-color: {{$color['background-color']}} !important;
      color:{{$color['default']}} !important;
    }
    a.btn-default, button.btn-default, input.form-control, select.form-control, div.well, div.alert {
      border-radius: 0px;
      border:1px solid {{$color['border-color']}};
      box-shadow: 0px 0px 0.3px 0.02px {{$color['border-color']}};
    }
    div.well{
      background-color: {{$color['default']}};
    }

    .pagination {
      display: inline-block;
    }

    .pagination a:nth-child(1) {
      border-left: 1px solid {{$color['border-color']}};
    }

    .pagination a {
      color: black;
      float: left;
      padding: 8px 16px;
      text-decoration: none;
      transition: background-color .3s;
      border-right: 1px solid {{$color['border-color']}};
      border-top: 1px solid {{$color['border-color']}};
      border-bottom: 1px solid {{$color['border-color']}};
      font-size: 15px;
      color: {{$color['background-color']}};
      box-shadow: 0px 0px 0.3px 0.02px {{$color['border-color']}};
    }

    .pagination a.active {
      background-color: {{$color['background-color']}};
      color: {{$color['default']}};
      border: 1px solid {{$color['background-color']}};
    }

    .pagination a:hover:not(.active) {background-color: #ddd;}

    .pagination a.disabled {
      pointer-events: none;
      cursor: default;
      color: grey;
    }

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
    .avatar{
      width: 120px;
    }
    .padded-dropcap {
      padding-left: 5px;
      padding-right: 10px;
      float: left;
      position: relative;
      top: -0.0em;
      margin-bottom: -0.5em;
      margin-left: -6px;
      margin-right: 2px;
      font-size: 40px;
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

    @media screen and (min-width: 992px) {
      .top_img {
        display: none;
      }
      .btm_img {
        display: block;
      }
    }
    @media screen and (max-width: 992px) {
      .top_img {
        display: block;
      }
      .btm_img {
        display: none;
      }
    }

    /* navbar */
.navbar-default {
    background-color: {{$color['background-color']}};
    border-color: {{$color['border-color']}};
}
/* Title */
.navbar-default .navbar-brand {
    color: {{$color['default']}};
}
.navbar-default .navbar-brand:hover,
.navbar-default .navbar-brand:focus {
    color: {{$color['bhover']}};
}
/* Link */
.navbar-default .navbar-nav > li > a {
    color: {{$color['default']}};
}
.navbar-default .navbar-nav > li > a:hover,
.navbar-default .navbar-nav > li > a:focus {
    color: {{$color['hover']}};
}
.navbar-default .navbar-nav > .active > a,
.navbar-default .navbar-nav > .active > a:hover,
.navbar-default .navbar-nav > .active > a:focus {
    color: {{$color['active']}};
    background-color: {{$color['background-color']}};
}
.navbar-default .navbar-nav > .open > a,
.navbar-default .navbar-nav > .open > a:hover,
.navbar-default .navbar-nav > .open > a:focus {
    color: {{$color['active']}};
    background-color: {{$color['active-background']}};
}
/* Caret */
.navbar-default .navbar-nav > .dropdown > a .caret {
    border-top-color: {{$color['default']}};
    border-bottom-color: {{$color['default']}};
}
.navbar-default .navbar-nav > .dropdown > a:hover .caret,
.navbar-default .navbar-nav > .dropdown > a:focus .caret {
    border-top-color: {{$color['hover']}};
    border-bottom-color: {{$color['hover']}};
}
.navbar-default .navbar-nav > .open > a .caret,
.navbar-default .navbar-nav > .open > a:hover .caret,
.navbar-default .navbar-nav > .open > a:focus .caret {
    border-top-color: {{$color['active']}};
    border-bottom-color: {{$color['active']}};
}
/* Mobile version */
.navbar-default .navbar-toggle {
    border-color: #DDD;
}
.navbar-default .navbar-toggle:hover,
.navbar-default .navbar-toggle:focus {
    background-color: #DDD;
}
.navbar-default .navbar-toggle .icon-bar {
    background-color: #CCC;
}
@media (max-width: 767px) {
    .navbar-default .navbar-nav .open .dropdown-menu > li > a {
        color: {{$color['default']}};
    }
    .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover,
    .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus {
          color: {{$color['hover']}};
    }
}

  </style>
  @guest
  @else
  <link rel="stylesheet" href="public/hw_chat_window/2.0/css/hw_chat_window_.css">
  @endguest
{{--   <script>
    function loadFile(path, type){
      if (type=="js"){
        var fileref=document.createElement('script');
        fileref.setAttribute("type","text/javascript");
        fileref.setAttribute("src", path);
      }
      else if (type=="css"){
        var fileref=document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("type", "text/css");
        fileref.setAttribute("href", path);
      }
      document.getElementsByTagName("head")[0].appendChild(fileref);
    }
    function addStyle(css){
      head = document.head || document.getElementsByTagName('head')[0],
      style = document.createElement('style');

      head.appendChild(style);

      style.type = 'text/css';
      if (style.styleSheet){
        // This is required for IE8 and below.
        style.styleSheet.cssText = css;
      } else {
        style.appendChild(document.createTextNode(css));
      }
    }
    const arr_fonts = ['ABeeZee', 'Bitter', 'Brawler', 'Buenard', 'Courgette', 'Delius', 'Fenix', 'Gudea', 'Halant', 'Heebo', 'Hind', 'K2D', 'Khula', 'Lora', 'Delius', 'Encode Sans', 'Esteban', 'Laila', 'Mukta', 'Patua One', 'Pavanam', 'Roboto', 'Sniglet', 'Strait'];

    const today = new Date;
    console.log(today.getDay());
    console.log(today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDay());
    console.log(Date.parse(today.getFullYear()+'-'+today.getMonth()+'-'+today.getDay()));
    const t = Date.parse(today.getFullYear()+'-'+today.getMonth()+'-'+(today.getDay()+1))/86400000 % arr_fonts.length;
    const font_family = arr_fonts[parseInt(t)];
    loadFile('https://fonts.googleapis.com/css?family='+font_family,'css');
    addStyle('body { font-family: '+font_family+'; }');
    console.log(parseInt(t));
    console.log(font_family);
  </script> --}}
  @yield("custom_style")
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="btn btn-primary navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span> 
        </button>
        <a href="{{route('c.welcome')}}"><svg height="45" width="250">
            <text x="0" y="28" fill="yellow" style="font-size:27px; font-weight:bold;">HoneyWeb.Org</text>
            <text x="0" y="42" fill="pink" style="font-size:9px; font-weight:bold; letter-spacing: .38rem;">Delightful Web Creations</text>
          HoneyWeb.Org
        </svg></a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-right">
        @guest
          <li><a href="{{route('c.blog.home')}}"><i class="fa fa-edit"></i> Blog</a></li>
          <li><a href="{{route('c.docs.home')}}"><i class="fa fa-book"></i> Docs</a></li>
          <li><a href="{{route('register')}}"><i class="fa fa-user"></i> Sign Up</a></li>
          <li><a href="{{route('login')}}"><i class="fa fa-sign-in"></i> Login</a></li>
        @else
          <li><a href="{{route('c.app.list.view')}}"><i class="fa fa-desktop"></i> MyApps</a></li>
          @if(!in_array('Licenses', json_decode(\Auth::user()->hidden_modules,true)??[]))<li><a href="{{route('l.license.list.view')}}"><i class="fa fa-lock"></i> Licenses</a></li>@endif
          <li><a href="{{route('c.table.list.view')}}"><i class="fa fa-database"></i> Tables</a></li>
          <li><a href="{{route('c.query.list.view')}}"><i class="fa fa-search"></i> Queries</a></li>
          <li><a href="{{route('c.files.view')}}"><i class="fa fa-file"></i> Files</a></li>
          <li><a href="{{route('c.mail.list.view')}}"><i class="fa fa-envelope"></i> Email</a></li>
          <li><a href="{{route('c.push.messages')}}"><i class="fa fa-bullhorn"></i> Push</a></li>
          <li><a href="{{route('c.chat.messages')}}"><i class="fa fa-comments"></i> Chat</a></li>
          <li><a href="{{route('c.app.log')}}"><i class="fa fa-edit"></i> Log</a></li>
          {{-- <li><a href="{{route('c.files.view')}}"><i class="fa fa-video-camera"></i> WebRTC</a></li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-code"></i> Obfuscator
            <span class="caret"></span></a>
            <ul class="dropdown-menu">  
              <li><a href="{{route('c.obfu.vba')}}">VBA</a></li>
            </ul>
          </li> --}}
          
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user"></i> {{\Auth::user()->name}}
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
              @if(\Auth::user()->avatar)
              <li><a href=""><image class="avatar" src="{{\Auth::user()->avatar}}" /></a></li>
              @else
              <li><a href=""><image  src="https://via.placeholder.com/120" /></a></li>
              @endif
              <li data-toggle="modal" data-target="#avatarUrl"><a style="cursor: pointer;"><i class="fa fa-user-circle-o"></i> Avatar Url</a></li>
              <li><a href="{{route('c.docs.home')}}"><i class="fa fa-graduation-cap"></i> Docs</a></li>
              <li data-toggle="modal" data-target="#inviteFriend"><a style="cursor: pointer;"><i class="fa fa-handshake-o"></i> Invite Friend</a></li>
              <li><a href="{{route('c.user.recharge_history.view')}}"><i class="fa fa-money"></i> Recharge History</a></li>
              <li><a href="{{route('c.user.usage_report.view')}}"><i class="fa fa-line-chart"></i> Usage Report</a></li>
              <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"><span class="fa fa-sign-out"></span> Logout</a></li>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  <input type="hidden" name="_token" value="{{csrf_token()}}">
              </form>
            </ul>
          </li>
          @endguest
      </ul>
    </div>
</nav>

<div id="alert"></div>
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

@guest
@else
<div id="hw_chat_app"></div>
<script src="public/hw_chat_window/2.0/js/hw_chat_window_.js"></script>
<script>
  hwc.ws.host = "{{env('APP_WS_URL')}}";//temp
  hwc.logo = '<svg height="45" width="250"><text x="0" y="28" fill="grey" style="font-size:27px; font-weight:bold; font-family:Arial, Helvetica, sans-serif">HoneyWeb.Org</text><text x="0" y="42" fill="lightgrey" style="font-size:9px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; letter-spacing: .38rem;">Delightful Web Creations</text>HoneyWeb.Org</svg>';
  hwc.routes = 'web';
  hwc.csrf_token = "{{csrf_token()}}";
  hwc.app['tap'] = "users";
  hwc.execute();
</script>

<script>
  function avatarUrl(){
    $.post("{{route('c.user.avatar')}}",{'avatar':$("#avatar").val(),'_token':"{{csrf_token()}}"},function(data, status){
      $("#avatarUrl").modal("hide");
      if(data['status'] == 'success'){
        $('#alert').html('<div class="alert alert-success"><strong>Success!</strong> '+data['message']+'.</div>');
      }else{
        $('#alert').html('<div class="alert alert-warning"><strong>Warning!</strong> '+data['message']+'.</div>');
      }
    });
  }
  function inviteFriend(){
    $("#waiting_in").html("Please wait....");
    $.post("{{route('c.invite.friend')}}",{'email':$("#email").val(),'_token':"{{csrf_token()}}"},function(data, status){
      $("#waiting_in").html('');
      $("#inviteFriend").modal("hide");
      if(data['status'] == 'success'){
        $('#alert').html('<div class="alert alert-success"><strong>Success!</strong> '+data['message']+'.</div>');
      }else{
        $('#alert').html('<div class="alert alert-warning"><strong>Warning!</strong> '+data['message']+'.</div>');
      }
    }).fail(function(e){
      $("#waiting_in").html("");
      $("#inviteFriend").modal('hide');
      $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> failed.</div>');
    });
  }
</script>


<!-- Modal -->
<div id="avatarUrl" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter avatar url</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <input type="text" id="avatar" class="form-control" placeholder="Avatar Url">
            <div id="avatar_msg"></div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" onclick="avatarUrl()">Add Avatar</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="inviteFriend" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter email address of your friend</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <input type="email" id="email" class="form-control" placeholder="user@example.com">
            <p id="waiting_in"></p>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" onclick="inviteFriend()">Invite</button>
      </div>
    </div>

  </div>
</div>
@endguest

</body>
</html>