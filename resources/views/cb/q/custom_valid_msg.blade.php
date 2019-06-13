@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  	<div id="alrt"></div>
	<div class="row">
		<div class="col-md-12 text-center">
			Customize Validation Messages <div class="input-group" style="float:right;">
				<a class="btn btn-default" href="{{route('c.query.list.view')}}">Back</a></div>
		</div>
	</div><hr>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr.No.</th>
						<th>Validation Rule</th>
						<th>Default Error Message</th>
						<th>Custom Error Message</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($crules as $rule)
					<tr>
						<td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
						<td>{{$rule->rule}}</td>
						<td>{{$rule->error_message}}</td>
						<td>{{$rules[$rule->rule]??''}}</td>
						<td><a style="cursor: pointer;" onclick="showDialog('{{$rule->error_message}}','{{$rule->rule}}')">Add Custom Error Message</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
			{{$crules->appends(request()->input())->links()}}
		</div>
	</div>
	<div class="alert alert-info"><strong>Info!</strong> Custom error messages supercedes default error messages</div>
</div>

<script>
	String.prototype.capitalize = function() {
	    return this.charAt(0).toUpperCase() + this.slice(1);
	}
	function showDialog(msg,rule){
		$("#rule").val(rule);
		$("#error_message").val(msg);
		$("#addCustomErrorMessage").modal();
	}
	function addCustomErrorMessage(){
		$.post("{{ route('c.query.valid.msg.submit') }}",{'_token':'{{csrf_token()}}','rule':$("#rule").val(), 'error_message':$("#error_message").val()}, function(data, status){
			$("#addCustomErrorMessage").modal('hide');
			if(status=='success'){
				if(data['status']=='success'){
					location.replace(window.location.href);
				}else{
					$('#alrt').html('<div class="alert alert-'+data['status']+'"><strong>'+data['status'].capitalize()+'!</strong> '+data['message']+'</div>');
				}
			}
		}).fail(function(e){
			if(e.status == 422){
				$("#addCustomErrorMessage").modal('hide');
				$('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> '+e.responseJSON.errors.custom_error_message[0]+'.</div>');
			}else{
				$("#addCustomErrorMessage").modal('hide');
				$('#alrt').html('<div class="alert alert-warning"><strong>Warning!</strong> failed.</div>');
			}
		});
	}
</script>

<!-- Modal -->
<div id="addCustomErrorMessage" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Custom Error Message</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
	        <input type="text" id="error_message" class="form-control">
	        <input type="hidden" id="rule" class="form-control">
	    </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default modal-close" onclick="addCustomErrorMessage()">Add</button>
      </div>
    </div>

  </div>
</div>
@endsection