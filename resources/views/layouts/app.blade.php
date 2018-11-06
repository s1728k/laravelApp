<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
</head>
<body>
    <ul id="dropdown1" class="dropdown-content">
      <li><a href="/passport_clients">OAuth Client List</a></li>
      <li><a href="/passport_authorize_clients">OAuth2 Client Authorization</a></li>
      <li><a href="#!">three</a></li>
      <li class="divider"></li>
      <li>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
    <nav>
      <div class="nav-wrapper">
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
        <a href="{{ url('/') }}" class="brand-logo">Laravel Protocal Demo</a>
        <ul class="right hide-on-med-and-down">
            @guest
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @else
                <li><a href="{{ url('/home') }}">Home</a></li>
                <li><a class="dropdown-button" data-activates="dropdown1">{{ Auth::user()->name }}<i class="material-icons right">arrow_drop_down</i></a></li>
            @endguest
        </ul>
      </div>
    </nav>

    <ul id="slide-out" class="side-nav">
        @guest
        <li><a href="{{ route('login') }}"><i class="material-icons">vpn_key</i>Login</a></li>
        <li><a href="{{ route('register') }}"><i class="material-icons">add_box</i>Register</a></li>
        @else
        <li><div class="user-view">
          {{-- <div class="background">
            <img src="images/office.jpg">
          </div>
          <a href="#!user"><img class="circle" src="images/yuna.jpg"></a> --}}
          <a href="#!name"><span class="white-text name">{{ Auth::user()->name }}</span></a>
          <a href="#!email"><span class="white-text email">jdandturk@gmail.com</span></a>
        </div></li>
        <li><a href="{{ url('/home') }}"><i class="material-icons">home</i>Home</a></li>
        <li>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
        <li><div class="divider"></div></li>
        <li><a class="subheader">OAuth2 Implementation</a></li>
        <li><a href="/passport_clients">OAuth2 Clients</a></li>
        <li><a href="/passport_authorize_clients">OAuth2 Clients</a></li>
        @endguest
    </ul>
    @yield('content')

    <script>
        $(".dropdown-button").dropdown();
        $(".button-collapse").sideNav();
        $('.modal').modal();
    </script>
    <script>
        var path = "http://localhost:8003/";
        var app = angular.module('myApp', []);

        app.controller('passportCtrl', function($scope, $http) {
            $scope.clients = [];
            $scope.newClient = {
                name: '',
                redirect: ''
            },
            $scope.createForm = {
                errors: [],
                name: '',
                redirect: ''
            },

            $scope.editForm = {
                errors: [],
                id: '',
                name: '',
                redirect: ''
            }
            $scope.openDialogForEdit = function($client){
                console.log("fsdfdsfs");
                 $('#modal2').modal('open')
                 $scope.editForm.name = $client.name;
                 $scope.editForm.redirect = $client.redirect;
                 $scope.editForm.id = $client.id;
            }
            $scope.getall = function(){
                $http.get(path + "oauth/clients")
                .then(function (response) {
                    $scope.clients = response.data;
                    console.log($scope.clients);
                });
            }
            $scope.getall();
            $scope.addnew = function(){
                console.log($scope.newClient);
                var data = $scope.newClient;
                var config = {
                    headers : {
                        'Content-Type': 'application/json;'
                    }
                }
                $http.post(path + "oauth/clients", data, config)
                .then(function (response) {
                    $scope.clients.push(response.data);
                    console.log(response);
                });
            }
            $scope.edit = function(){
                console.log($scope.editForm);
                var data = {'name':$scope.editForm.name,'redirect':$scope.editForm.redirect};
                var config = {
                    headers : {
                        'Content-Type': 'application/json;'
                    }
                }
                $http.put(path + "oauth/clients/" + $scope.editForm.id, data, config)
                .then(function (response) {
                    // $scope.clients = response.data;
                    console.log(response);
                });
            }
            $scope.destroy = function($client){
                $http.delete(path + "oauth/clients/" + $client.id)
                .then(function (response) {
                    // $scope.clients = response.data;
                    console.log(response);
                });
            }
        });
    </script>
</body>
</html>
