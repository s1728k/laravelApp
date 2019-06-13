@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
    <div id="alrt"></div>
    <div class="row">
        <div class="col-md-12">
            Log | for the app id: {{\Auth::user()->active_app_id}}<div class="btn-group" style="float:right"></div>
        </div>
    </div>
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Sr</th>
            <th>User Id</th>
            <th>User AP</th>
            <th>Query Id</th>
            <th>Author</th>
            <th>Query Nick Name</th>
            <th>Table</th>
            <th>Command</th>
            <th>IP Address</th>
            <th>DateTime</th>
					</tr>
				</thead>
				<tbody>
          @foreach($logs as $log)
          <tr>
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{$log->fid}}</td>
            <td>{{$log->fap}}</td>
            <td>{{$log->qid}}</td>
            <td>{{$log->auth_provider}}</td>
            <td>{{$log->query_nick_name}}</td>
            <td>{{$log->table_name}}</td>
            <td>{{$log->command}}</td>
            <td>{{$log->ip}}</td>
            <td>{{$log->created_at}}</td>
          </tr>
          @endforeach
				</tbody>
			</table>
      {{$logs->appends(request()->input())->links()}}
		</div>
	</div>
</div>
@endsection