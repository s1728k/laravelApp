@php
	$arr = ['ORDERID' => 'Order Id','MID'=>'Merchant Id','TXNAMOUNT'=>'Amount','CURRENCY'=>'Currency','STATUS'=>'Status','RESPMSG' => 'Message','BANKTXNID'=>'Bank Transaction Id','TXNID'=>'Transaction Id','TXNTYPE'=>'Transaction Type','GATEWAYNAME'=>'Gateway Name','BANKNAME'=>'Bank Name','PAYMENTMODE'=>'Payment Mode','REFUNDAMT'=>'Refund Amount','TXNDATE'=>'Transaction Date','ErrorCode'=>'Error Code','ErrorMsg'=>'Error Message'];
	$execpt = ['CHECKSUMHASH','RESPCODE'];
@endphp
@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<h3>Payment Status</h3>
			<table class="table">
				@foreach($res as $k => $v)
				@if(in_array($k, $execpt))
				@else
				<tr>
					<th>{{$arr[$k]}}</th>
					<td>{{$v}}</td>
				</tr>
				@endif
				@endforeach
			</table>
		</div>
	</div>
</div>
@endsection