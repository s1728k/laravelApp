@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-9">
      @yield('index')
    </div>
  </div>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-9">
      @yield('docs')
    </div>
  </div>
</div>
<script>
  // $(".doc_h").click(function(){
  //   $(this).toggleClass('is-active');
  //   // $(this).parent('li').child('ul').css( "display", "none" );
  //   $(this).next('.sublist').toggleClass('hidden');
  // });
  // Notification.requestPermission().then(function(result) {
  //   console.log(result);
  // });
  // if (Notification.permission === "granted") {
  //   // If it's okay let's create a notification
  //   var notification = new Notification("Hi there!");
  // }
</script>
@endsection
