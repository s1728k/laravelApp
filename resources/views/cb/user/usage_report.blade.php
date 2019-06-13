@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('name'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('name')}}</div>@endif
  @if($errors->has('id'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('id')}}</div>@endif
  <div class="row">
    <div class="col-md-8">
      <div class="well well-sm"> My Usage Report | Balance: ₹ {{\Auth::user()->recharge_balance}}, Expiry Date: {{\Auth::user()->recharge_expiry_date}}, Space Used: {{$size}} MB</div>
    </div>
    <div class="col-md-4">
      <div class="btn-group" style="float:right;">
        
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
              <th>App Id</th>
              <th>Date</th>
              <th>Api Calls</th>
              <th>Emails Sent</th>
              <th>Push Sent</th>
              <th>Chat Messages</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ur as $r)
            <tr>
              <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
              <td>{{$r->app_id}}</td>
              <td>{{$r->date}}</td>
              <td>{{$r->api_calls}}</td>
              <td>{{$r->emails_sent}}</td>
              <td>{{$r->push_sent}}</td>
              <td>{{$r->chat_messages}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        {{$ur->appends(request()->input())->links()}}
      </div>
    </div>
  </div>
</div>
<script>
  
</script>
@endsection