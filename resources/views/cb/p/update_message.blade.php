@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 text-center">
			Update Push Messsage	<div class="input-group" style="float:right;">
				<a class="btn btn-default" href="{{route('c.push.messages')}}">Back</a></div>
		</div>
	</div><hr>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<form method="post" action="{{route('c.push.update.msg.submit')}}" >
		        <input type="hidden" name="_token" value="{{csrf_token()}}" />
		        <input type="hidden" name="id" value="{{$message->id}}" />
		        <input type="hidden" name="app_id" value="{{\Auth::user()->active_app_id}}" />
		        <div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="id">id:</label>
					</div>
					<div class="col-md-6">
						<div class="well well-sm">{{$message->id}}</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="title">title:</label>
					</div>
					<div class="col-md-6">
						<input id="title" type="text" class="form-control" name="title" placeholder="title" value="{{old('title')}}">
						@if($errors->has('title'))
						<p style="color:red">{{$errors->first('title')}}</p> @endif
					</div>
				</div><hr>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="body">body:</label>
					</div>
					<div class="col-md-6">
						<textarea id="body" rows="3" class="form-control" name="body" placeholder="body"></textarea>
						@if($errors->has('body'))
						<p style="color:red">{{$errors->first('body')}}</p> @endif
					</div>
				</div><hr>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="icon">icon:</label>
					</div>
					<div class="col-md-6">
						<textarea id="icon" rows="3" class="form-control" name="icon" placeholder="icon"></textarea>
						@if($errors->has('icon'))
						<p style="color:red">{{$errors->first('icon')}}</p> @endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="image">image:</label>
					</div>
					<div class="col-md-6">
						<textarea id="image" rows="3" class="form-control" name="image" placeholder="image"></textarea>
						@if($errors->has('image'))
						<p style="color:red">{{$errors->first('image')}}</p> @endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="badge">badge:</label>
					</div>
					<div class="col-md-6">
						<textarea id="badge" rows="3" class="form-control" name="badge" placeholder="badge"></textarea>
						@if($errors->has('badge'))
						<p style="color:red">{{$errors->first('badge')}}</p> @endif
					</div>
				</div><hr>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="sound">sound:</label>
					</div>
					<div class="col-md-6">
						<textarea id="sound" rows="3" class="form-control" name="sound" placeholder="sound"></textarea>
						@if($errors->has('sound'))
						<p style="color:red">{{$errors->first('sound')}}</p> @endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="vibrate">vibrate:</label>
					</div>
					<div class="col-md-6">
						<textarea id="vibrate" rows="3" class="form-control" name="vibrate" placeholder="vibrate"></textarea>
						@if($errors->has('vibrate'))
						<p style="color:red">{{$errors->first('vibrate')}}</p> @endif
					</div>
				</div><hr>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="dir">dir:</label>
					</div>
					<div class="col-md-6">
						<input id="dir" type="text" class="form-control" name="dir" placeholder="dir" value="{{old('dir')}}">
						@if($errors->has('dir'))
						<p style="color:red">{{$errors->first('dir')}}</p> @endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="tag">tag:</label>
					</div>
					<div class="col-md-6">
						<input id="tag" type="text" class="form-control" name="tag" placeholder="tag" value="{{old('tag')}}">
						@if($errors->has('tag'))
						<p style="color:red">{{$errors->first('tag')}}</p> @endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="data">data:</label>
					</div>
					<div class="col-md-6">
						<input id="data" type="text" class="form-control" name="data" placeholder="data" value="{{old('data')}}">
						@if($errors->has('data'))
						<p style="color:red">{{$errors->first('data')}}</p> @endif
					</div>
				</div><hr>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="requireInteraction">requireInteraction:</label>
					</div>
					<div class="col-md-6">
						<input id="requireInteraction" type="number" class="form-control" name="requireInteraction" placeholder="requireInteraction" value="{{old('requireInteraction')}}">
						@if($errors->has('requireInteraction'))
						<p style="color:red">{{$errors->first('requireInteraction')}}</p> @endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="renotify">renotify:</label>
					</div>
					<div class="col-md-6">
						<input id="renotify" type="number" class="form-control" name="renotify" placeholder="renotify" value="{{old('renotify')}}">
						@if($errors->has('renotify'))
						<p style="color:red">{{$errors->first('renotify')}}</p> @endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="silent">silent:</label>
					</div>
					<div class="col-md-6">
						<input id="silent" type="number" class="form-control" name="silent" placeholder="silent" value="{{old('silent')}}">
						@if($errors->has('silent'))
						<p style="color:red">{{$errors->first('silent')}}</p> @endif
					</div>
				</div><hr>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="actions">actions:</label>
					</div>
					<div class="col-md-6">
						<div class="well well-sm" id="afields" style="word-break: break-all">none</div>
						<input id="aid" type="hidden" name="actions" />
						@if($errors->has('actions'))
						<p style="color:red">{{$errors->first('actions')}}</p> @endif
						<div class="form-group row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<input id="aa" type="text" class="form-control" placeholder="action">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<input id="at" type="text" class="form-control" placeholder="title">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
							<textarea id="ai" rows="3" class="form-control" placeholder="icon url"></textarea>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<a class="btn btn-info" onclick="a()">Toggle</a>
							</div>			
						</div>
					</div>
				</div><hr>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="timestamp">timestamp:</label>
					</div>
					<div class="col-md-6">
						<input id="timestamp" type="number" class="form-control" name="timestamp" placeholder="timestamp" value="{{old('timestamp')}}">
						@if($errors->has('timestamp'))
						<p style="color:red">{{$errors->first('timestamp')}}</p> @endif
					</div>
				</div><hr>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4">
						<label for="lang">lang:</label>
					</div>
					<div class="col-md-6">
						<input id="lang" type="text" class="form-control" name="lang" placeholder="lang" value="{{old('lang')}}">
						@if($errors->has('lang'))
						<p style="color:red">{{$errors->first('lang')}}</p> @endif
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-1"></div>
					<div class="col-md-4"></div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-primary">Update Push Message</button>
					</div>			
				</div><hr>
		    </form>
		</div>
	</div>
</div>
<script>
	$("#title").val('{{$message->title}}');
	$("#body").val('{{$message->body}}');

	$("#icon").val('{{old('icon')??$message->icon}}');
	$("#image").val('{{old('image')??$message->image}}');
	$("#badge").val('{{old('badge')??$message->badge}}');

	$("#sound").val('{{old('sound')??$message->sound}}');
	$("#vibrate").val('{{old('vibrate')??$message->vibrate}}');

	$("#dir").val('{{$message->dir}}');
	$("#tag").val('{{$message->tag}}');
	$("#data").val('{{$message->data}}');
	
	$("#requireInteraction").val('{{$message->requireInteraction}}');
	$("#renotify").val('{{$message->renotify}}');
	$("#silent").val('{{$message->silent}}');

	$("#afields").html('{{old('actions')??$message->actions??"none"}}');
	$("#actions").val('{{old('actions')??$message->actions}}');
	$("#timestamp").val('{{$message->timestamp}}');
	$("#lang").val('{{$message->lang}}');
</script>
<script>
	var ac = {!! json_encode(old('actions')??$message->actions?explode('|', old('actions')??$message->actions):[]) !!}; 
	function a(){
		const v = $("#aa").val() + "," + $("#at").val() + "," + $("#ai").val();
		if(ac.indexOf(v) != -1){
			ac.splice(ac.indexOf(v),1);
		}else{
			ac.push(v);
		}
		$("#afields").html(ac.join("|"));
		$("#aid").val(ac.join("|"));
	}
</script>
@endsection