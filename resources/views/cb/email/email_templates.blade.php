@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div id="alrt"></div>
  <div class="row">
    <div class="col-md-12 text-center">
      Email Templates Test Mail Sender
      <div class="input-group" style="float:right;">
        <a id="back" class="btn btn-default" href="{{route('c.mail.list.view')}}">Back</a></div>
    </div>
  </div><hr>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <div class="table-responsive">
      <table width="100%" width="100%">
        <tr>
          <td><strong>postBody</strong>{{ ' = {' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>"to"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="to" class="form-control" placeholder="To emails seperated by comma"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"cc"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="cc" class="form-control" placeholder="CC emails seperated by comma"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"bcc"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="bcc" class="form-control" placeholder="BCC emails seperated by comma"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"from_email"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="from_email" class="form-control" placeholder="From Email"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"from_name"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="from_name" class="form-control" placeholder="From Name"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"subject"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="subject" class="form-control" placeholder="Subject"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"attach"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="attach" class="form-control" placeholder="Attachment Path"></td>
          <td>"</td>
          <td></td>
        </tr>
        <tr>
          <td>"template"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><select id="template" class="form-control"><option>common_mail</option></select></td>
          <td>"</td>
          <td></td>
        </tr>
        <tr>
          <td>"message"</td>
          <td style="width:20px; text-align:center">:</td>
          <td></td>
          <td><strong>message</strong></td>
          <td></td>
          <td>,</td>
        </tr>
        <tr>
          <td>"app_id"</td>
          <td style="width:20px; text-align:center">:</td>
          <td></td>
          <td style="word-break: break-all;">{{\Auth::user()->active_app_id}}</td>
          <td></td>
          <td>,</td>
        </tr>
        <tr>
          <td>"secret"</td>
          <td style="width:20px; text-align:center">:</td>
          <td></td>
          <td style="word-break: break-all;">{{$secret}}</td>
          <td></td>
          <td>,</td>
        </tr>
        <tr>
          <td>{{ '};' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </table>
      </div><hr>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <div class="table-responsive">
      <table width="100%" id="message">
        <tr>
          <td colspan="2"><strong>message</strong>{{ ' = {' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>"</td>
          <td>plain_text"</td>
          <td></td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><textarea rows="3" id="plain_text" class="form-control" placeholder="Plain Text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec non molestie arcu. Maecenas auctor elit dapibus, congue ante ut, euismod erat. Donec iaculis magna elit, et lobortis leo faucibus ac. Cras in dolor et tellus mattis rutrum quis in tortor. Morbi consequat non velit quis scelerisque. Phasellus dictum luctus molestie. Etiam quis nulla ac turpis mattis placerat in sed tellus. Etiam vitae libero risus. Mauris scelerisque vulputate orci, ut blandit dui. Aenean dolor velit, pellentesque quis felis sit amet, porttitor tempus lorem. Donec eu posuere lectus. Duis in ipsum orci. Fusce sit amet sagittis orci, a euismod tellus. Pellentesque rhoncus facilisis feugiat.

Duis sit amet rhoncus quam, sodales malesuada ex. Fusce consectetur purus sed dictum aliquam. Quisque ut libero a nibh faucibus mattis. In fermentum ac elit id consequat. Cras nec feugiat quam, vel semper mauris. Sed justo sem, tristique et sodales sit amet, pretium eu justo. Mauris tincidunt nec nibh vitae rutrum. Morbi et tellus eget velit accumsan malesuada. Sed porta magna justo, in efficitur est iaculis a. Aliquam varius ut odio quis aliquet.

Donec porttitor sapien ut dignissim posuere. Nulla elit urna, pharetra at ullamcorper non, aliquet venenatis justo. Nunc a orci tortor. Phasellus a felis quis sem hendrerit cursus at quis eros. Mauris ac odio sed nibh pharetra elementum. Donec erat risus, mollis ac imperdiet non, tincidunt eget mi. Duis eget elit consectetur, maximus purus non, varius metus. Suspendisse pellentesque, sapien at varius pulvinar, massa justo condimentum neque, quis tristique diam purus sit amet eros. Suspendisse non volutpat metus, vitae feugiat urna. Suspendisse id venenatis arcu. Pellentesque pulvinar purus cursus lectus venenatis, non lobortis neque blandit. Cras quis dignissim sapien. Donec hendrerit consequat augue vel pulvinar. Vivamus placerat interdum augue, non consectetur diam.

Aenean cursus orci ac dolor pharetra, vel vestibulum orci laoreet. Mauris ante arcu, accumsan id libero vitae, vestibulum varius enim. Cras nunc odio, ullamcorper id mollis fringilla, sodales sit amet leo. Vivamus ullamcorper ante lacus, vel placerat risus tempor ut. Donec nisl lacus, congue sodales neque non, tempus varius augue. Phasellus porttitor ipsum blandit blandit fringilla. Cras rutrum iaculis cursus. Curabitur id tellus non tellus hendrerit imperdiet.

Sed iaculis, nisi ut egestas consectetur, turpis justo pretium nunc, eget dictum elit quam vitae arcu. Phasellus vel nibh posuere, accumsan diam at, porta nisl. Aenean fringilla ex quis nibh rhoncus, sit amet maximus lorem posuere. Curabitur id pulvinar nibh, in vestibulum sem. Ut lectus ex, volutpat in viverra quis, luctus vel enim. Quisque augue nibh, fringilla sed sodales et, convallis ac arcu. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</textarea></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td>title"</td>
          <td></td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="title" class="form-control" placeholder="Message Title"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td><input type="text" id="key1" class="form-control" placeholder="key1" onfocusout="removeKVMMessage()" onfocus="addKVMessage(1)"></td>
          <td>"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="value1" class="form-control" placeholder="value1"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td><input type="text" id="key2" class="form-control" placeholder="key2" onfocusout="removeKVMMessage()" onfocus="addKVMessage(2)"></td>
          <td>"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="value2" class="form-control" placeholder="value2"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td><input type="text" id="key3" class="form-control" placeholder="key3" onfocusout="removeKVMMessage()" onfocus="addKVMessage(3)"></td>
          <td>"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="value3" class="form-control" placeholder="value3"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td><input type="text" id="table_field" class="form-control" placeholder="table variable name"></td>
          <td>"</td>
          <td style="width:20px; text-align:center">:</td>
          <td></td>
          <td><strong>table</strong></td>
          <td></td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td>embed"</td>
          <td></td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="embed" class="form-control" placeholder="Embed Attachment Path"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>{{ '};' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </table>
      </div><hr>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <div class="table-responsive">
      <table width="100%" id="table">
        <tr>
          <td colspan="2"><strong id="table_val">table</strong>{{ ' = [{' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>"</td>
          <td><input type="text" id="keyt1" class="form-control" placeholder="key1" onfocusout="removeKVMTable()" onfocus="addKVTable(1)"></td>
          <td>"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="valuet1" class="form-control" placeholder="value1"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td><input type="text" id="keyt2" class="form-control" placeholder="key2" onfocusout="removeKVMTable()" onfocus="addKVTable(2)"></td>
          <td>"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="valuet2" class="form-control" placeholder="value2"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td><input type="text" id="keyt3" class="form-control" placeholder="key3" onfocusout="removeKVMTable()" onfocus="addKVTable(3)"></td>
          <td>"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="valuet3" class="form-control" placeholder="value3"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td><input type="text" id="keyt4" class="form-control" placeholder="key4" onfocusout="removeKVMTable()" onfocus="addKVTable(4)"></td>
          <td>"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="valuet4" class="form-control" placeholder="value4"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td><input type="text" id="keyt5" class="form-control" placeholder="key5" onfocusout="removeKVMTable()" onfocus="addKVTable(5)"></td>
          <td>"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="valuet5" class="form-control" placeholder="value5"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>"</td>
          <td><input type="text" id="keyt6" class="form-control" placeholder="key6" onfocusout="removeKVMTable()" onfocus="addKVTable(6)"></td>
          <td>"</td>
          <td style="width:20px; text-align:center">:</td>
          <td>"</td>
          <td><input type="text" id="valuet6" class="form-control" placeholder="value6"></td>
          <td>"</td>
          <td>,</td>
        </tr>
        <tr>
          <td>{{ '}];' }}</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </table>
      </div><hr>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <div class="table-responsive">
      <table width="100%">
        <tr>
          <td><strong>url</strong> = "{{env('APP_URL')}}/api/email";</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </table>
      <p>Notes:-</p>
      <p>1. to, cc, bcc can be multiple number separated by comma.</p>
      <p>2. template are built in. and you can use only them. for new template contact us.</p>
      <p>3. if you have value for plain_text other parameters in the message dont take effect.</p>
      <p>4. table is an array and can have many objects. for simplicity here only one object is shown</p>
      <p>5. from_email should be valid domain email. and one of it will automatically appear if you have created.</p>
      <button id="send_mail" class="btn btn-primary" onclick="sendMail()">Send Mail</button>
      </div><hr>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <label>post response:</label>
      <textarea rows="4" id="result" class="form-control"></textarea>
    </div>
  </div><br><br><br><br>
</div>

<script>
  $("#to").val('{{$alias}}');
  $("#from_email").val('{{$email}}');
  $("#from_name").val('{{\Auth::user()->name}}');
  $("#subject").val('Test Mail');
  $("#attach").val('https://via.placeholder.com/150');

  $("#title").val('Invoice');
  $("#key1").val('Name');
  $("#key2").val('Total Price');
  $("#value1").val('{{\Auth::user()->name}}');
  $("#value2").val('200 usd');
  $("#table_field").val('report');
  $("#embed").val('https://via.placeholder.com/150');

  $("#keyt1").val('Sr.No.');
  $("#keyt2").val('Particular');
  $("#keyt3").val('Qty');
  $("#keyt4").val('Price Per Unit');
  $("#keyt5").val('Price');
  $("#valuet1").val('1');
  $("#valuet2").val('T-Shirt');
  $("#valuet3").val('2');
  $("#valuet4").val('100 usd');
  $("#valuet5").val('200 usd');

  var kvmi = 3; var kvti = 6;
  var kvmt = '<tr><td>"</td><td><input type="text" id="%key%" class="form-control" placeholder="%key%" onfocusout="removeKVMMessage()" onfocus="addKVMessage(%i%)"></td><td>"</td><td style="width:20px; text-align:center">:</td><td>"</td><td><input type="text" id="%val%" class="form-control" placeholder="%val%"></td><td>"</td><td>,</td></tr>';
  var kvtt = '<tr><td>"</td><td><input type="text" id="%key%" class="form-control" placeholder="%key%" onfocusout="removeKVMTable()" onfocus="addKVTable(%i%)"></td><td>"</td><td style="width:20px; text-align:center">:</td><td>"</td><td><input type="text" id="%val%" class="form-control" placeholder="%val%"></td><td>"</td><td>,</td></tr>';

  function addKVMessage(i){
    if($("#key"+(i)).val()==''){
      kvmi = kvmi + 1;
      kvm = kvmt.replace('%key%', 'key'+kvmi);
      kvm = kvm.replace('%key%', 'key'+kvmi);
      kvm = kvm.replace('%val%', 'value'+kvmi);
      kvm = kvm.replace('%val%', 'value'+kvmi);
      kvm = kvm.replace('%i%', kvmi);
      $("#message  tr:nth-child("+(2+kvmi)+")").after(kvm);
    }
  }

  function removeKVMMessage(){
    if($("#key"+(kvmi-1)).val()==''){
      kvm = kvmt.replace('%key%', 'key'+kvmi);
      kvm = kvm.replace('%key%', 'key'+kvmi);
      kvm = kvm.replace('%val%', 'value'+kvmi);
      kvm = kvm.replace('%val%', 'value'+kvmi);
      kvm = kvm.replace('%i%', kvmi);
      $("#message  tr:nth-child("+(3+kvmi)+")").remove();
      kvmi = kvmi - 1;
    }
  }

  function addKVTable(i){
    if($("#keyt"+(i)).val()==''){
      kvti = kvti + 1;
      kvt = kvtt.replace('%key%', 'keyt'+kvti);
      kvt = kvt.replace('%key%', 'key'+kvti);
      kvt = kvt.replace('%val%', 'valuet'+kvti);
      kvt = kvt.replace('%val%', 'value'+kvti);
      kvt = kvt.replace('%i%', kvti);
      $("#table  tr:nth-child("+(kvti)+")").after(kvt);
    }
  }

  function removeKVMTable(){
    if($("#keyt"+(kvti-1)).val()==''){
      kvt = kvtt.replace('%key%', 'keyt'+kvti);
      kvt = kvt.replace('%key%', 'key'+kvti);
      kvt = kvt.replace('%val%', 'valuet'+kvti);
      kvt = kvt.replace('%val%', 'value'+kvti);
      kvt = kvt.replace('%i%', kvti);
      $("#table  tr:nth-child("+(1+kvti)+")").remove();
      kvti = kvti - 1;
    }
  }

  function sendMail(){
    var table = {};
    var message = {
      'plain_text':$("#plain_text").val(),
      'title':$("#title").val(),
      'embed':$("#embed").val(),
    };
    var postBody = {
      "to":$("#to").val(),
      "cc":$("#cc").val(),
      "bcc":$("#bcc").val(),
      "from_email":$("#from_email").val(),
      "from_name":$("#from_name").val(),
      "subject":$("#subject").val(),
      "attach":$("#attach").val(),
      "template":$("#template").val(),
    };
    for (var i = 1; i < kvmi; i++) {
      if($('#key'+i).val()){
        message[$('#key'+i).val()]=$('#value'+i).val();
      }
    }
    for (var i = 1; i < kvti; i++) {
      if($('#keyt'+i).val()){
        table[$('#keyt'+i).val()]=$('#valuet'+i).val();
      }
    }
    message[$("#table_field").val()||'table'] = [table];
    postBody['message'] = message;
    postBody['secret'] = '{{$secret}}';
    postBody['app_id'] = '{{\Auth::user()->active_app_id}}';

    $("#result").val('');

    $("#send_mail").prop("disabled",true);
    $.post("{{env('APP_URL')}}/api/email", postBody, function(data, status){
      if(status='success'){
        $("#result").val(JSON.stringify(data));
        $("#send_mail").prop("disabled",false);
      }
    });
  }
</script>
@endsection