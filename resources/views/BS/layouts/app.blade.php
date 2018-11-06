<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Backend Solutions') }}</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

{{--     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> --}}
    <style>
    	.container-fluid, .row, .col{
    		border:1px solid red
    	}
    </style>
</head>
<body>
	 
    
    <div class="divider"></div>
  <div class="section">
    <h5>Section 1</h5>
    <p>Stuff</p>
    <div class="row">
      <div class="col s1">1</div>
      <div class="col s1">fdsfds fdf df df dkfkd fkj dkfj ldjfd jflk jfkjd skfj dksfj sdklf js</div>
      <div class="col s6">
      	<div class="row">
      <div class="col s1">1</div>
      <div class="col s1">fdsfds fdf df df dkfkd fkj dkfj ldjfd jflk jfkjd skfj dksfj sdklf js</div>
      <div class="col s1">3</div>
      <div class="col s1">4</div>
      <div class="col s1">5</div>
      <div class="col s1">6</div>
      <div class="col s1">7</div>
      <div class="col s1">8</div>
      <div class="col s1">9</div>
      <div class="col s1">10</div>
      <div class="col s1">11</div>
      <div class="col s1">12</div>
    </div>
      </div>
      
    </div>
  </div>
  <div class="divider"></div>
  <div class="section">
    <h5>Section 2</h5>
    <p>Stuff</p>
    <div class="row">
      <div class="col s1">1</div>
      <div class="col s1">fdsfds fdf df df dkfkd fkj dkfj ldjfd jflk jfkjd skfj dksfj sdklf js</div>
      <div class="col s1">3</div>
      <div class="col s1">4</div>
      <div class="col s1">5</div>
      <div class="col s1">6</div>
      <div class="col s1">7</div>
      <div class="col s1">8</div>
      <div class="col s1">9</div>
      <div class="col s1">10</div>
      <div class="col s1">11</div>
      <div class="col s1">12</div>
    </div>
  </div>
  <div class="divider"></div>
  <div class="section">
    <h5>Section 3</h5>
    <p>Stuff</p>
  </div>
</body>
</html>