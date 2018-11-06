@extends("cm.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>Email Accounts List | for the app id: {{\Auth::user()->active_app_id}}<div class="btn-group" style="float:right"> <a class="btn btn-default" onclick="addNewEmailAccount()">Add New Email Account</a><a class="btn btn-default" href="{{route('c.email.new.domain.view')}}">Add New Domain</a><a class="btn btn-default" href="http://mail.honeyweb.org" target="_blank">Goto Mails</a></caption>
				<thead>
					<tr>
						<th>Sr</th>
            <th>User Name</th>
            <th>Domain Alias</th>
						<th>Email Address</th>
            <th colspan="6">Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($emails as $key => $email)
          <tr id="r{{$email->id}}">
            <td>{{ ($key + 1) }}</td>
            <td>{{ $email->user }}</td>
            <td>{{ $email->domain }}</td>
            <td>{{ $email->email }}</td>
            <td><a style="cursor:pointer" onclick="deleteEmailAccount('{{$email->id}}')">Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{-- {{$tables->links()}} --}}
		</div>
	</div>
</div>

<script>
  function addNewEmailAccount(){
    $("#newEmailAccount").modal();
  }
  function deleteEmailAccount(id){
    $.post("{{route('c.email.delete.user')}}", {"id":id, "_token":"{{csrf_token()}}"}, function(data){
      if(data['status'] == 'success'){
        $("#r"+String(id)).remove();
        var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Email account has been deleted successfully!</div>';
        $('#alrt').html(ht);
      }else{
        console.log(data);
      }
    });
  }
</script>

<!-- Modal -->
<div id="newEmailAccount" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">New Email Account</h4>
        </div>
        <div class="modal-body">
          <form method="post" action="{{route('c.email.new.user.submit')}}" >
            <input type="hidden" name="_token" value="{{csrf_token()}}" />

            <div class="form-group row">
              <div class="col-md-4">
                <label for="name">User Name</label>
              </div>
              <div class="col-md-4">
                <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="Domain Name">
                @if($errors->has('name'))
                  <p style="color:red">{{$errors->first('name')}}</p> 
                @endif
              </div>
              <div class="col-md-4">
                <select class="form-control" id="domains" name="domain_id">
                  @foreach($domains as $domain)
                  <option value="{{$domain->id}}">{{$domain->name}}</option>
                  @endforeach
                </select>
                @if($errors->has('domain_id'))
                  <p style="color:red">{{$errors->first('domain_id')}}</p> 
                @endif
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-4">
                <label for="password">Password</label>
              </div>
              <div class="col-md-8">
                <input type="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" placeholder="Password">
                @if($errors->has('password'))
                  <p style="color:red">{{$errors->first('password')}}</p> 
                @endif
              </div>      
            </div>

            <div class="form-group row">
                <div class="col-md-4">
                    <label for="password-confirm">Confirm Password</label>
                </div>
                <div class="col-md-8">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required/>
                </div>
            </div>

            <div class="form-group row">
              <div class="col-md-4"></div>
              <div class="col-md-8">
                <button type="submit" class="btn btn-primary">Create</button>
              </div>      
            </div>
          </form>
        </div>
    </div>

  </div>
</div>
@endsection