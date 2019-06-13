@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  <div class="row">
    <div class="col-md-12 text-center">
        Chat Settings | for the app id: {{\Auth::user()->active_app_id}}
        <div class="btn-group" style="float:right"> <a class="btn btn-default" href="{{route('c.chat.messages')}}">Back</a></div>
    </div>
  </div><hr>
	<div class="row">
    <div class="col-md-3"></div>
		<div class="col-md-6">
      @foreach($ap as $a)
      <div class="form-group row">
        <div class="col-md-1"></div>
        <div class="col-md-4">
          <label for="auth_providers">{{$a}}:</label>
        </div>
        <div class="col-md-6">
          <div class="well well-sm" id="a{{$a}}"></div>
          <div class="row">
            <div class="col-md-12">
            <p>{{$a}} can chat with all users of:-</p>
            @foreach($ap as $b)
              <div class="checkbox" style="display: inline-flex; margin-right: 10px"><label><input type="checkbox" onchange="ap('{{$a}}','{{$b}}')" @empty($ccw[$a.':'.$b]) @else @if($ccw[$a.':'.$b]=='*') checked @endif @endempty>{{$b}}</label></div>
            @endforeach
            </div>
          </div>
          <div class="well well-sm" id="s{{$a}}"></div>
          <div class="row">
            <div class="col-md-12">
            <p>{{$a}} can chat with only users listed in field:-</p>
            @foreach($ap as $b)
              <div class="checkbox" style="display: inline-flex; margin-right: 10px"><label><input type="checkbox" onchange="sp('{{$a}}','{{$b}}')" @empty($ccw[$a.':'.$b]) @else @if($ccw[$a.':'.$b]==1) checked @endif @endempty>chat_{{$b}}</label></div>
            @endforeach
            </div>
          </div>
        </div>      
      </div><hr>
      @endforeach
      <div class="form-group row">
        <div class="col-md-1"></div>
        <div class="col-md-4">
          <label for="chat_admins">Chat Admins:</label>
        </div>
        <div class="col-md-6">
          <div class="well well-sm" id="chat_admins"></div>
          <div class="row">
            <div class="col-md-12">
            @foreach($ap as $b)
              <div class="checkbox" style="display: inline-flex; margin-right: 10px"><label><input type="checkbox" onchange="ca('{{$b}}')" @empty($ccw['chat_admins']) @else @if(in_array($b, $ca)) checked @endif @endempty>{{$b}}</label></div>
            @endforeach
            </div>
          </div>
        </div>      
      </div><hr>
      <div class="form-group row">
        <div class="col-md-1"></div>
        <div class="col-md-4">
          <label for="group_chat">Group Chat:</label>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-12">
              <select id="group_chat" class="form-control">@foreach(array_merge(['-'],$ap) as $b)<option @empty($ccw['group_chat']) @else selected @endempty>{{$b}}</option>@endforeach</select>
            </div>
          </div>
        </div>      
      </div><hr>
      <div class="form-group row">
        <div class="col-md-1"></div>
        <div class="col-md-4">
          <label for="group_chat">Guest Chat:</label>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-12">
              <div class="checkbox"><label><input type="checkbox" id="guest_chat"> Allow Guest To Chat</label></div>
            </div>
          </div>
        </div>      
      </div><hr>
      <div class="form-group row">
        <div class="col-md-1"></div>
        <div class="col-md-4"></div>
        <div class="col-md-6">
          <button type="submit" class="btn btn-primary" onclick="save()">Save</button>
        </div>
      </div><hr>
		</div>
	</div>
</div>
<script>
  var can_chat_with = {!! json_encode($ccw)??'{}' !!};
  var auth_providers = {!! json_encode($ap) !!};
  var chat_admins = {!! json_encode($ca) !!};
  var aps = {};var sps = {};
  $("#chat_admins").html(chat_admins.join(', '));
  $("#group_chat").val(can_chat_with['group_chat']);
  $("#guest_chat").prop('checked', can_chat_with['guest']==1);
  for (var i = 0; i < auth_providers.length; i++) {
    $("#a"+auth_providers[i]).html('none');
    $("#s"+auth_providers[i]).html('none');
    aps[auth_providers[i]]=[];
    sps[auth_providers[i]]=[];
    for (var j = 0; j < auth_providers.length; j++) {
      if(can_chat_with[auth_providers[i]+':'+auth_providers[j]] == '*'){
        aps[auth_providers[i]].push(auth_providers[j]);
      }else if(can_chat_with[auth_providers[i]+':'+auth_providers[j]] == 1){
        sps[auth_providers[i]].push(auth_providers[j]);
      }
    };
    if(aps[auth_providers[i]].length != 0){
      $("#a"+auth_providers[i]).html(aps[auth_providers[i]].join(', '));
    }
    if(sps[auth_providers[i]].length != 0){
      $("#s"+auth_providers[i]).html(sps[auth_providers[i]].join(', '));
    }
  };

  Array.prototype.diff = function(a) {
      return this.filter(function(i) {return a.indexOf(i) < 0;});
  };
  function ap(k, v){
    if(aps[k].indexOf(v) != -1){
      aps[k].splice(aps[k].indexOf(v),1);
    }else{
      aps[k].push(v);
    }
    let diff = auth_providers.diff(aps[k]);
    diff = auth_providers.diff(diff);
    $("#a"+k).html(diff.join(", "));
  }
  function sp(k, v){
    if(sps[k].indexOf(v) != -1){
      sps[k].splice(sps[k].indexOf(v),1);
    }else{
      sps[k].push(v);
    }
    let diff = auth_providers.diff(sps[k]);
    diff = auth_providers.diff(diff);
    $("#s"+k).html(diff.join(", "));
  }
  function ca(v){
    if(chat_admins.indexOf(v) != -1){
      chat_admins.splice(chat_admins.indexOf(v),1);
    }else{
      chat_admins.push(v);
    }
    let diff = auth_providers.diff(chat_admins);
    diff = auth_providers.diff(diff);
    $("#chat_admins").html(diff.join(", "));
  }
  function save(){
    can_chat_with = {};
    Object.keys(aps).forEach(function(key) {
      for (var i = 0; i < aps[key].length; i++) {
        can_chat_with[key+":"+aps[key][i]]='*';
        can_chat_with[key] = 1;
      }
      for (var j = 0; j < sps[key].length; j++) {
        can_chat_with[key+":"+sps[key][j]]=1;
        can_chat_with[key] = 1;
      }
    });
    can_chat_with['chat_admins'] = chat_admins.join(', ');
    can_chat_with['group_chat'] = $("#group_chat").val();
    can_chat_with['guest'] = $("#guest_chat").prop('checked')?1:0;
    console.log(can_chat_with);
    $.post('{{ route('c.chat.ccw.submit') }}', {'_token':'{{csrf_token()}}','_method':'put','can_chat_with':can_chat_with},function(data, status){
      if(status == 'success'){
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Can chat with setting was successfully saved.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Can chat with setting was not saved.</div>');
      }
    });
  }
</script>
@endsection