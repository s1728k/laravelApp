@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  <div class="row">
    <div class="col-md-5">
      My Mails | for the user id: {{\Auth::user()->id}}
    </div>
    <div class="col-md-7">
      <div class="btn-group" style="float:right"> 
        <a class="btn btn-default" href="{{route('c.domain.list.view')}}">My Domains</a>
        <a class="btn btn-default" href="{{route('c.alias.list.view')}}">My Aliases</a>
        <a class="btn btn-default" href="{{route('c.email.list.view')}}">My Email Accounts</a>
        <a class="btn btn-default" href="{{route('c.mail.add.new.view')}}">Create Mail</a>
        {{-- <a class="btn btn-default" href="http://mail.honeyweb.org" target="_blank">Goto Mails</a> --}}
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr</th>
						<th>To</th>
            <th>Cc</th>
            <th>Bcc</th>
            <th>From Eamil</th>
            <th>From Name</th>
            <th>Subject</th>
            <th>Attach</th>
            <th>Template</th>
            <th>Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($emails as $email_obj)
          @php
            $email = json_decode($email_obj->email, true);
          @endphp
          <tr id="r{{$email_obj->id}}">
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{ $email['to']??'' }}</td>
            <td>{{ $email['cc']??'' }}</td>
            <td>{{ $email['bcc']??'' }}</td>
            <td>{{ $email['from_email']??'' }}</td>
            <td>{{ $email['from_name']??'' }}</td>
            <td>{{ $email['subject']??'' }}</td>
            <td>{{ $email['attach']??'' }}</td>
            <td>{{ $email['template']??'' }}</td>
            <td><a id="a{{$email_obj->id}}" href="JavaScript:void(0);" onclick="sendMail('{{$email_obj->id}}')">Send Mail</a><br>
              <a href="{{ route('c.mail.update.view', ['id'=>$email_obj->id]) }}">Update</a><br>
              <a href="JavaScript:void(0);" onclick="copyMail('{{$email_obj->id}}')">Copy</a><br>
              <a href="JavaScript:void(0);" onclick="d('{{$email_obj->id}}')">Delete</a>
            </td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$emails->appends(request()->input())->links()}}
		</div>
	</div>
</div>

<script>
  function d(id){
    $.post("{{route('c.mail.delete')}}", {"id":id, "_token":"{{csrf_token()}}", "_method":"DELETE"}, function(data){
      if(data['status'] == 'success'){
        $("#r"+String(id)).remove();
        var ht = '<div class="alert alert-success text-center"><strong>Success!</strong> Email has been deleted successfully!</div>';
        $('#alrt').html(ht);
      }else{
        console.log(data);
      }
    });
  }

  function copyMail(id) {
    $.post("{{route('c.mail.copy')}}", {"id":id, "_token":"{{csrf_token()}}"}, function(data){
      location.reload();
    });
  }

  var boolSendMail = false;
  function sendMail(id){
    if(boolSendMail){
      return;
    }
    boolSendMail = true;
    $('#alrt').html('<div class="alert alert-info"><strong>Info!</strong> please wait...</div>');
    $.post("{{route('c.mail.send')}}", {"id":id, "_token":"{{csrf_token()}}"}, function(data){
      if(data['status'] == 'success'){
        var ht = '<div class="alert alert-success"><strong>Success!</strong> Email has been sent successfully!</div>';
        $('#alrt').html(ht);
        boolSendMail = false;
      }else{
        console.log(data);
      }
    });
  }
</script>

@endsection