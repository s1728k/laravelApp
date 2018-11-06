@extends("cb.layouts.admin")

@section("content")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 table-responsive">
			<table class="table">
                <form method="get" action="{{route('c.admin.visitors')}}">
                    <caption>Visitors 
                        <div class="btn-group" style="float:right"> 
                            <input type="submit" class="btn btn-default" name="cmd" value="Fill IP Details">
                            <input type="submit" class="btn btn-default" name="cmd" value="Stats">
                        </div>
                    </caption>
                </form>
				<thead>
					<tr>
						<th>Sr</th>
						<th>TimeStamp</th>
                        <th>IP</th>
                        <th>Page Visited</th>
                        <th>App Id</th>
                        <th>Count</th>
                        <th>Origin</th>
                        <th>Hostname</th>
                        <th>ISP</th>
                        <th>Continent</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        {{-- <th>PostalCode</th> --}}
                        <th>Latitude</th>
                        <th>Longitude</th>
						<th colspan="3">Actions</th>
					</tr>
				</thead>
				<tbody>
          @foreach($visits as $visit)
          <tr>
            <td>{{ ($loop->index + 1) + 10 * ($page-1)}}</td>
            <td>{{$visit->timestamp}}</td>
            <td>{{$visit->IP}}</td>
            <td>{{$visit->page_visited}}</td>
            <td>{{$visit->app_id}}</td>
            <td>{{$visit->no_of_times_visited}}</td>
            <td>{{$visit->Origin}}</td>
            <td>{{$visit->Hostname}}</td>
            <td>{{$visit->ISP}}</td>
            <td>{{$visit->Continent}}</td>
            <td>{{$visit->Country}}</td>
            <td>{{$visit->State}}</td>
            <td>{{$visit->City}}</td>
            {{-- <td>{{$visit->PostalCode}}</td> --}}
            <td>{{$visit->Latitude}}</td>
            <td>{{$visit->Longitude}}</td>
          </tr>
          @endforeach
				</tbody>
			</table>
            {{$visits->appends(request()->input())->links()}}
		</div>
	</div>
</div>
@endsection