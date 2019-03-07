@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
        <caption>Email Accounts List | for the user id: {{\Auth::user()->id}}<div class="btn-group" style="float:right"> <a class="btn btn-default" href="{{route('c.email.new.account')}}">Add New Email Account</a><a class="btn btn-default" href="{{route('c.domain.list.view')}}">My Domains</a><a class="btn btn-default" href="{{route('c.alias.list.view')}}">My Aliases</a><a class="btn btn-default" href="http://mail.honeyweb.org" target="_blank">Goto Mails</a></caption>
				<thead>
					<tr>
						<th>Sr</th>
						<th>Email Address</th>
            <th>Alias Addresses</th>
            <th colspan="6">Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($emails as $key => $email)
          <tr id="r{{$email->id}}">
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{ $email->email }}</td>
            <td>{{ $email->alias }}</td>
            <td><a style="cursor:pointer" onclick="d('{{$email->id}}')">Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$emails->links()}}
		</div>
	</div>
</div>

<script>
  function d(id){
    $.post("{{route('c.email.delete.user')}}", {"id":id, "_token":"{{csrf_token()}}", "_method":"DELETE"}, function(data){
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

@endsection