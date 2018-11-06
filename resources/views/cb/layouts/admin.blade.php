<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>HoneyWeb.Org - Delightful Web Creations</title>
  <base href="/">

  <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <li><a href="{{route('c.auth.admin.signup')}}"><i class="glyphicon glyphicon-user"></i> Admin Sign Up</a></li>
            <li><a href="{{route('c.auth.admin.login')}}"><i class="glyphicon glyphicon-log-in"></i> Admin Login</a></li>
          @else
            <li><a href="{{route('c.admin.daily.logs')}}"><i class="fa fa-desktop"></i> Daily Logs</a></li>
            <li><a href="{{route('c.admin.visitors')}}"><i class="fa fa-desktop"></i> Visitors</a></li>
            <li><a href="{{route('c.admin.daily.logs')}}"><i class="fa fa-desktop"></i> Error Report</a></li>
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
    </div>
  </nav>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        @yield("content")
      </div>
    </div>
  </div>
</body>
</html>