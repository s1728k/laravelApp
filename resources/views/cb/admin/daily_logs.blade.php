@extends("cb.layouts.admin")

@section("content")
<div class="container-fluid">
	<div class="row">
    <div class="col-md-12 table-responsive">
      <table class="table">
        <form method="get" action="{{route('c.admin.daily.logs')}}" >
          <caption>Daily Logs | date:- {{$date}}
            <div class="input-group" style="float:right;position: relative;"> 
                <input style="width:170px;" type="date" name="date" value="{{$date}}" class="form-control">
                <input type="submit" class="btn btn-default" name="cmd" value="Open Log">
                <input type="submit" class="btn btn-default" name="cmd" value="SaveVisitors">
            </div>
          </caption>
        </form>
        <tbody>
          <tr>
            <td><textarea rows="10" style="width:100%; height:70vh">{{$data}}</textarea></td>
          </tr>
        </tbody>
      </table>
    </div>
	</div>
</div>
@endsection