@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('domain_name'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('domain_name')}}</div>@endif
  <div class="row">
    <div class="col-md-5">
      My Domains List | for the user id: {{\Auth::user()->id}}
    </div>
    <div class="col-md-7">
      <div class="btn-group" style="float:right;position: relative;">
        <button class="btn btn-default" data-toggle="modal" data-target="#newdomain">Add New Domain Name</button>
        <a class="btn btn-default" href="{{route('c.mail.list.view')}}">Back</a>
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr</th>
						<th>Domain Name</th>
            <th>Verification Status</th>
            <th>Delete</th>
					</tr>
				</thead>
				<tbody>
          @foreach($domains as $key => $domain)
          @php
            $valid_domain = 'Not OK: Add TXT record with name:- mail.'.$domain->name.'; and value:- '. $domain->dns ;
            try{
              $dns_txt_arr = dns_get_record($domain->name, DNS_TXT)??[];
              if(is_array($dns_txt_arr)){
                foreach ($dns_txt_arr as $dns_txt) {
                  if($domain->dns == $dns_txt['txt']){
                    $valid_domain = 'DNS Verified OK';
                    break;
                  }
                }
              }
            }catch(Exception $e){
            }
          @endphp
          <tr id="r{{$domain->id}}">
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{ $domain->name }}</td>
            <td>{{$valid_domain}}</td>
            <td><a href="JavaScript:void(0);" onclick="d('{{$domain->id}}')">Delete</a></td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$domains->appends(request()->input())->links()}}
		</div>
	</div>
</div>

<script>
  function d(id) {
    $.post("{{ route('c.domain.delete') }}", {'_token':"{{csrf_token()}}", "id":id, '_method':"DELETE"}, function (data) {
      if(data['status'] == 'success'){
        $('#r'+id).remove();
        $('#alrt').html('<div class="alert alert-success"><strong>Success!</strong> Domain name address was successfully deleted.</div>');
      }else{
        $('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> Domain name address was not deleted.</div>');
      }
    })
  }
</script>


<!-- Modal -->
<div id="newdomain" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <form method="post" action="{{route('c.domain.add.new')}}">
      <input type="hidden" name="_token" value="{{csrf_token()}}" />
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter domain name</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <input type="text" name="domain_name" class="form-control" placeholder="example.com">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default">Add</button>
      </div>
      </form>
    </div>

  </div>
</div>
@endsection