@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('name'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('name')}}</div>@endif
  @if($errors->has('id'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('id')}}</div>@endif
  <div class="row">
    <div class="col-md-8">
      <div class="well well-sm"> My Recharge History | Balance: ₹ {{\Auth::user()->recharge_balance}}, Expiry Date: {{\Auth::user()->recharge_expiry_date}}</div>
    </div>
    <div class="col-md-4">
      <div class="btn-group" style="float:right;">
        <a class="btn btn-default" href="{{ route('c.user.recharge_offers.view') }}">New Recharge</a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="well well-sm">
        <table class="table">
          <thead>
            <tr>
              <th>Sr.</th>
              <th>Plan</th>
              <th>Status</th>
              <th>Expiry Date</th>
              <th>Recharge Date</th>
              <th>Recharge Amount</th>
              <th>Tax</th>
              <th>Top Up</th>
              <th colspan="3">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rh as $r)
            <tr>
              <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
              <td>{{$r->plan}}</td>
              <td>{{$r->status}}</td>
              <td>{{$r->expiry_date}}</td>
              <td>{{$r->recharge_date}}</td>
              <td>{{$r->recharge_amount}}</td>
              <td>{{$r->tax}}</td>
              <td>{{$r->top_up}}</td>
              <td><a href="{{ route('c.recharge.status', ['id'=> $r->id]) }}">Status</a></td>
              <td><a href="{{ route('c.refund.payment', ['id'=> $r->id]) }}">Refund</a></td>
              <td><a href="{{ route('c.refund.status', ['id'=> $r->id]) }}">Refund Status</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
        {{$rh->appends(request()->input())->links()}}
      </div>
    </div>
  </div>
</div>
<script>
  
</script>
@endsection