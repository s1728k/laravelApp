@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('name'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('name')}}</div>@endif
  @if($errors->has('id'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('id')}}</div>@endif
  <div class="row">
    <div class="col-md-12">
      <div class="well well-sm"> My Recharge Offers | @if(\Auth::user()->recharge_balance == (null||0)) <i>Please recharge your account with one of the below offers to visit the control panel</i> @else <i>Your account balance is ₹ {{\Auth::user()->recharge_balance}}</i> @endif </div>
    </div>
    <div class="col-md-4">
      <div class="btn-group" style="float:right;">

      </div>
    </div>
  </div>
  <form method="post" action="{{ route('c.user.recharge') }}" >
  <input type="hidden" name="_token" value="{{csrf_token()}}">
  <div class="row">
    <div class="col-md-4">
      <div class="well well-sm">
        <table class="table">
          <thead>
            <tr><th>Trial ( ₹ 50 )</th></tr>
          </thead>
          <tbody>
            <tr><td>1paisa / api call</td></tr>
            <tr><td>1paisa / email</td>
            <tr><td>1paisa / push message</td>
            <tr><td>1paisa / chat message</td>
            <tr><td>28 days validity</td></tr>
            <tr><td>No refund for trial recharge</td></tr>
            <tr><td><button class="btn btn-default" name="plan" value="Trial">Recharge</button></td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-4">
      <div class="well well-sm">
        <table class="table">
          <thead>
            <tr><th>Monthly ( ₹ 250 )</th></tr>
          </thead>
          <tbody>
            <tr><td>1paisa / api call</td></tr>
            <tr><td>1paisa / email</td>
            <tr><td>1paisa / push message</td>
            <tr><td>1paisa / chat message</td>
            <tr><td>28 days validity</td></tr>
            <tr><td>₹ 200 refund option availabe within 10days</td></tr>
            <tr><td><button class="btn btn-default" name="plan" value="Monthly">Recharge</button></td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-md-4">
      <div class="well well-sm">
        <table class="table">
          <thead>
            <tr><th>Yearly ( ₹ 2000 )</th></tr>
          </thead>
          <tbody>
            <tr><td>1paisa / api call</td></tr>
            <tr><td>1paisa / email</td>
            <tr><td>1paisa / push message</td>
            <tr><td>1paisa / chat message</td>
            <tr><td>365 days validity</td></tr>
            <tr><td>₹ 1950 refund option availabe within 28days</td></tr>
            <tr><td><button class="btn btn-default" name="plan" value="Yearly">Recharge</button></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  </form>
</div>
<script>
  
</script>
@endsection