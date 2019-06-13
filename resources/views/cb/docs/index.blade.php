@extends("cb.layouts.docs")

@if($index == 'index')
@section("index")
<h4><a href="/docs">Index</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>Index</h1> 
	<ol>
		<li><a href="/docs/routemap">Route Map</a></li>
		<li><a href="/docs/my_apps">My Apps</a></li>
		<li><a href="/docs/licenses">Licenses</a></li>
		<li><a href="/docs/tables">Tables</a></li>
		<li><a href="/docs/queries">Queries</a></li>
		<li><a href="/docs/files">Files</a></li>
		<li><a href="/docs/emails">Emails</a></li>
		<li><a href="/docs/push_notifications">Push Notifications</a></li>
		<li><a href="/docs/chat_messaging">Chat Messaging</a></li>
		<li><a href="/docs/logs">Logs</a></li>
	</ol>
</div>
@endsection
@endif

@if($index == 'my_apps')
@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/my_apps">My Apps</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>My Apps</h1> 
	<p>My apps menu page shall display apps list created by user. Fields that are displayed are App Id, App Name, <a href="/docs/my_apps/token_lifetoken">Token Lifetime</a>, <a href="/docs/my_apps/availability">Availability</a> and Actions.</p>
	<h2>Title Section</h2>
	<ol>
		<li>datatable name</li>
		<li><a href="/docs/my_apps/activate_app_id">active app ID</a></li>
		<li>active app name</li>
		<li>active app secret</li>
	</ol>
	<h2>Button Group</h2>
	<ol>
		<li>My Apps</li>
		<li><a href="/docs/my_apps/invited_apps">Invited Apps</a></li>
		<li><a href="/docs/my_apps/public_apps">Public Apps</a></li>
		<li><a href="/docs/my_apps/create_new_app">Create New App</a></li>
	</ol>
	<h2>Datatable Action Links</h2>
	<ol>
		<li><a href="/docs/my_apps/activate_app">Activate</a></li>
		<li><a href="/docs/my_apps/update_app">Update</a></li>
		<li><a href="/docs/my_apps/user_fields">User Fields</a></li>
		<li><a href="/docs/my_apps/origins">Origins</a></li>
		<li><a href="/docs/my_apps/invited_users">Invited Users</a></li>
		<li><a href="/docs/my_apps/export_db">ExportDB</a></li>
		<li><a href="/docs/my_apps/description">Description</a></li>
		<li><a href="/docs/my_apps/copy">Copy</a></li>
		<li><a href="/docs/my_apps/delete">Delete</a></li>
	</ol>
</div>
@endsection
@endif

@if($index == 'licenses')
@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/licenses">Licenses</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>Licenses</h1> 
	<ol>
		<li><a href="/docs/licenses/create_new_license">Create New License</a></li>
		<li><a href="/docs/licenses/edit_license">Edit License</a></li>
		<li><a href="/docs/licenses/license_detail">License Detail</a></li>
		<li><a href="/docs/licenses/activate_license">Activate License</a></li>
		<li><a href="/docs/licenses/deactivate_license">Deactivate License</a></li>
		<li><a href="/docs/licenses/test_bench">Test Bench</a></li>
	</ol>
</div>
@endsection
@endif

@if($index == 'tables')
@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/tables">Tables</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>Tables</h1> 
	<ol>
		<li><a href="/docs/tables/create_new_table">Create New Table</a></li>
		<li><a href="/docs/tables/add_fields">Add Fields</a></li>
		<li><a href="/docs/tables/rename_field">Rename Field</a></li>
		<li><a href="/docs/tables/delete_field">Delete Field</a></li>
		<li><a href="/docs/tables/add_index">Add Index</a></li>
		<li><a href="/docs/tables/remove_index">Remove Index</a></li>
		<li><a href="/docs/tables/crud">CRUD</a></li>
		<li><a href="/docs/tables/rename_table">Rename Table</a></li>
		<li><a href="/docs/tables/truncate_table">Truncate Table</a></li>
		<li><a href="/docs/tables/delete_tab">Delete Table</a></li>
		<li><a href="/docs/tables/export_table">Export Table</a></li>
		<li><a href="/docs/tables/import_create">Import - Create</a></li>
		<li><a href="/docs/tables/import_update">Import - Update</a></li>
		<li><a href="/docs/tables/api_calls_for_tables">Api Calls</a></li>
	</ol>
</div>
@endsection
@endif

@if($index == 'queries')
@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/queries">Queries</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>Queries</h1> 
	<p>Query is a record in our database which keeps all the query parameters of your url so you dont have to use any query in your url. You only need to mention the query id in your url. Typical url looks like this.</p> <pre>https://honeyweb.org/api/23</pre> 
	<p>Query list page shall display all queries created by user with two action links.</p>
	<h2>Title Section</h2>
	<ol>
		<li>datatable name</li>
		<li><a href="/docs/my_apps/activate_app_id">active app ID</a></li>
	</ol>
	<h2>Button Group</h2>
	<ol>
		<li><a href="/docs/queries/create_new_query">Create New Query</a></li>
		<li><a href="/docs/queries/validation">Validation</a></li>
		<li><a href="/docs/queries/customize_validation_message">Customize Validation Messages</a></li>
	</ol>
	<h2>Datatable Action Links</h2>
	<ol>
		<li><a href="/docs/queries/update_query">Update</a></li>
		<li><a href="/docs/queries/delete_query">Delete</a></li>
	</ol>
	<h2>Url For Api Calls</h2>
	<ol>
		<li><a href="/docs/queries/standard_url_form">Standard Form Of URL</a></li>
		<li><a href="/docs/queries/general_url_form">General Form Of URL</a></li>
	</ol>
	<h2>Api Calls Examples</h2>
	<ol>
		<li><a href="/docs/queries/jquery">JQuery</a></li>
		<li><a href="/docs/queries/readall">ReadAll</a></li>
		<li><a href="/docs/queries/create">Create</a></li>
		<li><a href="/docs/queries/read">Read</a></li>
		<li><a href="/docs/queries/update">Update</a></li>
		<li><a href="/docs/queries/delete">Delete</a></li>
		<li><a href="/docs/queries/signup">SignUp</a></li>
		<li><a href="/docs/queries/send_email_varification_code">SendEmailVerificationCode</a></li>
		<li><a href="/docs/queries/verify_email">VerifyEmail</a></li>
		<li><a href="/docs/queries/login">Login</a></li>
		<li><a href="/docs/queries/conditional_login">ConditionalLogin</a></li>
		<li><a href="/docs/queries/refresh_token">RefreshToken</a></li>
		<li><a href="/docs/queries/files_upload">FilesUpload</a></li>
		<li><a href="/docs/queries/send_mail">SendMail</a></li>
		<li><a href="/docs/queries/push_subscribe">PushSubscribe</a></li>
		<li><a href="/docs/queries/get_app_secret">GetAppSecret</a></li>
	</ol>
</div>
@endsection
@endif

@if($index == 'files')
@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/files">Files</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>Files</h1> 
	<ol>
		<li><a href="/docs/files/create_new_app">Create New App</a></li>
		<li><a href="/docs/files/activate_app_id">Active App Id</a></li>
		<li><a href="/docs/files/activate_app">Activate App</a></li>
		<li><a href="/docs/files/update_app">Update App</a></li>
		<li><a href="/docs/files/permissions">Permissions</a></li>
		<li><a href="/docs/files/origins">Origins</a></li>
	</ol>
</div>
@endsection
@endif

@if($index == 'emails')
@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/emails">Emails</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>Emails</h1> 
	<ol>
		<li><a href="/docs/emails/create_new_app">Create New App</a></li>
		<li><a href="/docs/emails/activate_app_id">Active App Id</a></li>
		<li><a href="/docs/emails/activate_app">Activate App</a></li>
		<li><a href="/docs/emails/update_app">Update App</a></li>
		<li><a href="/docs/emails/permissions">Permissions</a></li>
		<li><a href="/docs/emails/origins">Origins</a></li>
	</ol>
</div>
@endsection
@endif

@if($index == 'push_notifications')
@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/push_notifications">Push Notifications</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>Push Notifications</h1> 
	<ol>
		<li><a href="/docs/push_notifications/create_new_app">Create New App</a></li>
		<li><a href="/docs/push_notifications/activate_app_id">Active App Id</a></li>
		<li><a href="/docs/push_notifications/activate_app">Activate App</a></li>
		<li><a href="/docs/push_notifications/update_app">Update App</a></li>
		<li><a href="/docs/push_notifications/permissions">Permissions</a></li>
		<li><a href="/docs/push_notifications/origins">Origins</a></li>
	</ol>
</div>
@endsection
@endif

@if($index == 'chat_messaging')
@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/chat_messaging">Chat Messaging</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>Chat Messaging</h1> 
	<ol>
		<li><a href="/docs/chat_messaging/create_new_app">Create New App</a></li>
		<li><a href="/docs/chat_messaging/activate_app_id">Active App Id</a></li>
		<li><a href="/docs/chat_messaging/activate_app">Activate App</a></li>
		<li><a href="/docs/chat_messaging/update_app">Update App</a></li>
		<li><a href="/docs/chat_messaging/permissions">Permissions</a></li>
		<li><a href="/docs/chat_messaging/origins">Origins</a></li>
	</ol>
</div>
@endsection
@endif

@if($index == 'logs')
@section("index")
<h4><a href="/docs">Index</a> >> <a href="/docs/logs">Logs</a></h4>
@endsection
@section("docs")
<div class="jumbotron">
	<h1>Logs</h1> 
	<ol>
		<li><a href="/docs/logs/create_new_app">Create New App</a></li>
		<li><a href="/docs/logs/activate_app_id">Active App Id</a></li>
		<li><a href="/docs/logs/activate_app">Activate App</a></li>
		<li><a href="/docs/logs/update_app">Update App</a></li>
		<li><a href="/docs/logs/permissions">Permissions</a></li>
		<li><a href="/docs/logs/origins">Origins</a></li>
	</ol>
</div>
@endsection
@endif