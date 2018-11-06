@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2" id="myScrollspy">
      <ul id="doc_h_u">
        <li>
        <h2 class="doc_h " id="is-active"><a href="/docs/apps" >Apps</a></h2>
        <ul class="sublist" >
        <li><a href="/docs/apps/#apps1" id="apps1s">Create New App</a></li>
        <li><a href="/docs/apps/#apps2" id="apps2s">Active App Id</a></li>
        <li><a href="/docs/apps/#apps3" id="apps3s">Activate App</a></li>
        <li><a href="/docs/apps/#apps4" id="apps4s">Update App</a></li>
        <li><a href="/docs/apps/#apps5" id="apps5s">Permissions</a></li>
        <li><a href="/docs/apps/#apps6" id="apps6s">Origins</a></li>
        </ul>
        </li>
        <li>
        <h2 class="doc_h"><a href="/docs/tables" >Tables</a></h2>
        <ul class="sublist">
        <li><a href="/docs/tables/#create_new_table" id="create_new_table_s">Create New Table</a></li>
        <li><a href="/docs/tables/#add_fields" id="add_fields_s">Add Fields</a></li>
        <li><a href="/docs/tables/#rename_field" id="rename_field_s">Rename Field</a></li>
        <li><a href="/docs/tables/#delete_field" id="delete_field_s">Delete Field</a></li>
        <li><a href="/docs/tables/#add_index" id="add_index_s">Add Index</a></li>
        <li><a href="/docs/tables/#remove_index" id="remove_index_s">Remove Index</a></li>
        <li><a href="/docs/tables/#crud" id="crud_s">CRUD</a></li>
        <li><a href="/docs/tables/#rename_table" id="rename_table_s">Rename Table</a></li>
        <li><a href="/docs/tables/#truncate_table" id="truncate_table_s">Truncate Table</a></li>
        <li><a href="/docs/tables/#delete_table" id="delete_table">Delete Table</a></li>
        <li><a href="/docs/tables/#export_table" id="export_table_s">Export Table</a></li>
        <li><a href="/docs/tables/#import_create" id="import_create_s">Import - Create</a></li>
        <li><a href="/docs/tables/#import_update" id="import_update_s">Import - Update</a></li>
        <li><a href="/docs/tables/#api_calls_for_tables" id="api_calls_for_tables_s">Api Calls</a></li>
        </ul>
        </li>
        <li>
        <h2 class="doc_h"><a href="/docs/sessions" >Authentication</a></h2>
        <ul class="sublist">
        <li><a href="/docs/sessions/#sessions1" id="sessions1s">Authenticatable Table</a></li>
        <li><a href="/docs/sessions/#sessions2" id="sessions2s">Edit License</a></li>
        <li><a href="/docs/sessions/#sessions3" id="sessions3s">License Detail</a></li>
        <li><a href="/docs/sessions/#sessions4" id="sessions4s">Activate License</a></li>
        <li><a href="/docs/sessions/#sessions5" id="sessions5s">Deactivate License</a></li>
        <li><a href="/docs/sessions/#sessions6" id="sessions6s">Test Bench</a></li>
        </ul>
        </li>
        <li>
        <h2 class="doc_h"><a href="/docs/auth" >Authorisation</a></h2>
        <ul class="sublist">
        <li><a href="/docs/auth/#auth1" id="auth1s">Authenticatable Table</a></li>
        <li><a href="/docs/auth/#auth2" id="auth2s">Edit License</a></li>
        <li><a href="/docs/auth/#auth3" id="auth3s">License Detail</a></li>
        <li><a href="/docs/auth/#auth4" id="auth4s">Activate License</a></li>
        <li><a href="/docs/auth/#auth5" id="auth5s">Deactivate License</a></li>
        <li><a href="/docs/auth/#auth6" id="auth6s">Test Bench</a></li>
        </ul>
        </li>
        <li>
        <h2 class="doc_h"><a href="/docs/files" >Files & Assets</a></h2>
        <ul class="sublist">
        <li><a href="/docs/files/#files1" id="files1s">Authenticatable Table</a></li>
        <li><a href="/docs/files/#files2" id="files2s">Edit License</a></li>
        <li><a href="/docs/files/#files3" id="files3s">License Detail</a></li>
        <li><a href="/docs/files/#files4" id="files4s">Activate License</a></li>
        <li><a href="/docs/files/#files5" id="files5s">Deactivate License</a></li>
        <li><a href="/docs/files/#files6" id="files6s">Test Bench</a></li>
        </ul>
        </li>
        <li>
        <h2 class="doc_h"><a href="/docs/emails" >Email</a></h2>
        <ul class="sublist">
        <li><a href="/docs/emails/#emails1" id="emails1s">Authenticatable Table</a></li>
        <li><a href="/docs/emails/#emails2" id="emails2s">Edit License</a></li>
        <li><a href="/docs/emails/#emails3" id="emails3s">License Detail</a></li>
        <li><a href="/docs/emails/#emails4" id="emails4s">Activate License</a></li>
        <li><a href="/docs/emails/#emails5" id="emails5s">Deactivate License</a></li>
        <li><a href="/docs/emails/#emails6" id="emails6s">Test Bench</a></li>
        </ul>
        </li>
        {{-- <li>
        <h2 class="doc_h"><a href="/docs/apps" >CDN</a></h2>
        <ul class="sublist">
        <li><a href="/docs/5.4/authentication">Authentication</a></li>
        <li><a href="/docs/5.4/passport">API Authentication</a></li>
        <li><a href="/docs/5.4/authorization">Authorization</a></li>
        <li><a href="/docs/5.4/encryption">Encryption</a></li>
        <li><a href="/docs/5.4/hashing">Hashing</a></li>
        <li><a href="/docs/5.4/passwords">Password Reset</a></li>
        </ul>
        </li>
        <li>
        <h2 class="doc_h"><a href="/docs/apps" >Chat</a></h2>
        <ul class="sublist">
        <li><a href="/docs/5.4/artisan">Artisan Console</a></li>
        <li><a href="/docs/5.4/broadcasting">Broadcasting</a></li>
        <li><a href="/docs/5.4/cache">Cache</a></li>
        <li><a href="/docs/5.4/collections">Collections</a></li>
        <li><a href="/docs/5.4/events">Events</a></li>
        <li><a href="/docs/5.4/filesystem">File Storage</a></li>
        <li><a href="/docs/5.4/helpers">Helpers</a></li>
        <li><a href="/docs/5.4/mail">Mail</a></li>
        <li><a href="/docs/5.4/notifications">Notifications</a></li>
        <li><a href="/docs/5.4/packages">Package Development</a></li>
        <li><a href="/docs/5.4/queues">Queues</a></li>
        <li><a href="/docs/5.4/scheduling">Task Scheduling</a></li>
        </ul>
        </li>
        <li>
        <h2 class="doc_h"><a href="/docs/apps" >Cloud Messaging</a></h2>
        <ul class="sublist">
        <li><a href="/docs/5.4/database">Getting Started</a></li>
        <li><a href="/docs/5.4/queries">Query Builder</a></li>
        <li><a href="/docs/5.4/pagination">Pagination</a></li>
        <li><a href="/docs/5.4/migrations">Migrations</a></li>
        <li><a href="/docs/5.4/seeding">Seeding</a></li>
        <li><a href="/docs/5.4/redis">Redis</a></li>
        </ul>
        </li>
        <li>
        <h2 class="doc_h"><a href="/docs/apps" >Push Notifications</a></h2>
        <ul class="sublist">
        <li><a href="/docs/5.4/eloquent">Getting Started</a></li>
        <li><a href="/docs/5.4/eloquent-relationships">Relationships</a></li>
        <li><a href="/docs/5.4/eloquent-collections">Collections</a></li>
        <li><a href="/docs/5.4/eloquent-mutators">Mutators</a></li>
        <li><a href="/docs/5.4/eloquent-serialization">Serialization</a></li>
        </ul>
        </li>
        <li>
        <h2 class="doc_h"><a href="/docs/apps" >Code Obfuscation</a></h2>
        <ul class="sublist">
        <li><a href="/docs/5.4/testing">Getting Started</a></li>
        <li><a href="/docs/5.4/http-tests">HTTP Tests</a></li>
        <li><a href="/docs/5.4/dusk">Browser Tests</a></li>
        <li><a href="/docs/5.4/database-testing">Database</a></li>
        <li><a href="/docs/5.4/mocking">Mocking</a></li>
        </ul>
        </li>
        <li>
        <h2 class="doc_h"><a href="/docs/apps" >Prebuild Applications</a></h2>
        <ul class="sublist">
        <li><a href="/docs/5.4/billing">Cashier</a></li>
        <li><a href="/docs/5.4/envoy">Envoy</a></li>
        <li><a href="/docs/5.4/passport">Passport</a></li>
        <li><a href="/docs/5.4/scout">Scout</a></li>
        <li><a href="/docs/5.4/socialite">Socialite</a></li>
        </ul>
        </li> --}}
        <li>
        <h2 class="doc_h"><a href="/docs/licenses" >Licenses</a></h2>
        <ul class="sublist">
        <li><a href="/docs/licenses/#licenses1" id="licenses1s">Create New License</a></li>
        <li><a href="/docs/licenses/#licenses2" id="licenses2s">Edit License</a></li>
        <li><a href="/docs/licenses/#licenses3" id="licenses3s">License Detail</a></li>
        <li><a href="/docs/licenses/#licenses4" id="licenses4s">Activate License</a></li>
        <li><a href="/docs/licenses/#licenses5" id="licenses5s">Deactivate License</a></li>
        <li><a href="/docs/licenses/#licenses6" id="licenses6s">Test Bench</a></li>
        </ul>
        </li>
      </ul>
    </div>
    <div class="col-md-9">
      @yield('docs')
    </div>
  </div>
</div>
<script>
  // $(".doc_h").click(function(){
  //   $(this).toggleClass('is-active');
  //   // $(this).parent('li').child('ul').css( "display", "none" );
  //   $(this).next('.sublist').toggleClass('hidden');
  // });
  // Notification.requestPermission().then(function(result) {
  //   console.log(result);
  // });
  // if (Notification.permission === "granted") {
  //   // If it's okay let's create a notification
  //   var notification = new Notification("Hi there!");
  // }
</script>
@endsection
