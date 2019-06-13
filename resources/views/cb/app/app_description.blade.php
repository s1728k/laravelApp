@extends("cb.layouts.app")

@section("custom_style")
<script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.9.0/showdown.min.js"></script>
@endsection
@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  @if($errors->has('name'))<div class="alert alert-warning"><strong>Warning!</strong> {{$errors->first('name')}}</div>@endif
  <div class="row">
    <div class="col-md-6">
      <div class="well well-sm">App Description | for app id: {{$id}} </div>
    </div>
    <div class="col-md-6">
      <div class="btn-group" style="float:right"> 
        <a class="btn btn-default" href="{{url()->previous()}}">Back</a>
      </div>
    </div>
  </div>
	<div class="row">
		<div class="col-md-6">
			<textarea class="form-control" onkeyup="markToHtml()" style="min-height: 400px">{{$desc}}</textarea>
		</div>
    <div class="col-md-6">
      <div class="well well-sm" id="html_mark" style="min-height: 400px"></div>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6"><button class="btn btn-default" onclick="saveDescription()">Save Description</button></div>
	</div>
</div>
<script>
  // const el = document.querySelector('textarea');
    // const stackedit = new Stackedit();

    // // Open the iframe
    // stackedit.openFile({
    //   name: 'Filename', // with an optional filename
    //   content: {
    //     text: el.value // and the Markdown content.
    //   }
    // });

    // // Listen to StackEdit events and apply the changes to the textarea.
    // stackedit.on('fileChange', (file) => {
    //   el.value = file.content.text;
    // });

  function saveDescription(){
    $.post("{{ route('c.app.desc.submit') }}", {'description':$("textarea").val(),'_token':'{{csrf_token()}}','id':{{$id}}}, function(data, status){
        if(status='success'){
          $('#alrt').html('<div class="alert alert-'+data['status']+'"><strong>'+data['status']+'!</strong> '+data['message']+'</div>');
          document.getElementById("alrt").scrollIntoView();
        }
      });
  }
  function markToHtml(){
    const el = document.querySelector('textarea');
    var converter = new showdown.Converter();
    converter.setFlavor('github');
    let text      = el.value;
    let html      = converter.makeHtml(text);
    $("#html_mark").html(html);
    $("textarea").height($("#html_mark").height());
  }
  
  if($("textarea").val()==''){
    let t = "# {{$name}}\n\n\n\n**Lorem Ipsum** is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
    t = t + '';
    $("textarea").val(t);
  }
  markToHtml();
</script>


@endsection