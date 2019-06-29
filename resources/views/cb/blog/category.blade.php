@extends("cb.layouts.blog")

@if($category == 'category')
@section("category")
<h4><a href="/blog">Catagory</a></h4>
@endsection
@section("blog")
<div class="jumbotron">
	<h1>Catagory</h1> 
	<ol>
		<li><a href="/blog/windows">Windows</a></li>
		<li><a href="/blog/ubuntu">Ubuntu</a></li>
		<li><a href="/blog/github">Github</a></li>
		<li><a href="/blog/excel">Excel</a></li>
		<li><a href="/blog/web">Web</a></li>
		<li><a href="/blog/android">Android</a></li>
	</ol>
</div>
@endsection
@endif

@if($category == 'github')
@section("category")
<h4><a href="/blog">Catagory</a> >> <a href="/blog/github">Github</a></h4>
@endsection
@section("blog")
<div class="jumbotron">
	<h1>Github</h1> 
	<ol>
		<li><a href="/blog/{{$category}}/basic-git-usage">Basic Git Usage</a></li>
	</ol>
</div>
@endsection
@endif

@if($category == 'licenses')
@section("category")
<h4><a href="/blog">Catagory</a> >> <a href="/blog/licenses">Licenses</a></h4>
@endsection
@section("blog")
<div class="jumbotron">
	<h1>Licenses</h1> 
	<ol>
		<li><a href="/blog/licenses/create_new_license">Create New License</a></li>
		<li><a href="/blog/licenses/edit_license">Edit License</a></li>
		<li><a href="/blog/licenses/license_detail">License Detail</a></li>
		<li><a href="/blog/licenses/activate_license">Activate License</a></li>
		<li><a href="/blog/licenses/deactivate_license">Deactivate License</a></li>
		<li><a href="/blog/licenses/test_bench">Test Bench</a></li>
	</ol>
</div>
@endsection
@endif

@if($category == 'tables')
@section("category")
<h4><a href="/blog">Catagory</a> >> <a href="/blog/tables">Tables</a></h4>
@endsection
@section("blog")
<div class="jumbotron">
	<h1>Tables</h1> 
	<ol>
		<li><a href="/blog/tables/create_new_table">Create New Table</a></li>
		<li><a href="/blog/tables/add_fields">Add Fields</a></li>
		<li><a href="/blog/tables/rename_field">Rename Field</a></li>
		<li><a href="/blog/tables/delete_field">Delete Field</a></li>
		<li><a href="/blog/tables/add_category">Add Catagory</a></li>
		<li><a href="/blog/tables/remove_category">Remove Catagory</a></li>
		<li><a href="/blog/tables/crud">CRUD</a></li>
		<li><a href="/blog/tables/rename_table">Rename Table</a></li>
		<li><a href="/blog/tables/truncate_table">Truncate Table</a></li>
		<li><a href="/blog/tables/delete_tab">Delete Table</a></li>
		<li><a href="/blog/tables/export_table">Export Table</a></li>
		<li><a href="/blog/tables/import_create">Import - Create</a></li>
		<li><a href="/blog/tables/import_update">Import - Update</a></li>
		<li><a href="/blog/tables/api_calls_for_tables">Api Calls</a></li>
	</ol>
</div>
@endsection
@endif

@if($category == 'queries')
@section("category")
<h4><a href="/blog">Catagory</a> >> <a href="/blog/queries">Queries</a></h4>
@endsection
@section("blog")
<div class="jumbotron">
	<h1>Queries</h1> 
	<p>Query is a record in our database which keeps all the query parameters of your url so you dont have to use any query in your url. You only need to mention the query id in your url. Typical url looks like this.</p> <pre>https://honeyweb.org/api/23</pre> 
	<p>Query list page shall display all queries created by user with two action links.</p>
	<h2>Title Section</h2>
	<ol>
		<li>datatable name</li>
		<li><a href="/blog/my_apps/activate_app_id">active app ID</a></li>
	</ol>
	<h2>Button Group</h2>
	<ol>
		<li><a href="/blog/queries/create_new_query">Create New Query</a></li>
		<li><a href="/blog/queries/validation">Validation</a></li>
		<li><a href="/blog/queries/customize_validation_message">Customize Validation Messages</a></li>
	</ol>
	<h2>Datatable Action Links</h2>
	<ol>
		<li><a href="/blog/queries/update_query">Update</a></li>
		<li><a href="/blog/queries/delete_query">Delete</a></li>
	</ol>
	<h2>Url For Api Calls</h2>
	<ol>
		<li><a href="/blog/queries/standard_url_form">Standard Form Of URL</a></li>
		<li><a href="/blog/queries/general_url_form">General Form Of URL</a></li>
	</ol>
	<h2>Api Calls Examples</h2>
	<ol>
		<li><a href="/blog/queries/jquery">JQuery</a></li>
		<li><a href="/blog/queries/readall">ReadAll</a></li>
		<li><a href="/blog/queries/create">Create</a></li>
		<li><a href="/blog/queries/read">Read</a></li>
		<li><a href="/blog/queries/update">Update</a></li>
		<li><a href="/blog/queries/delete">Delete</a></li>
		<li><a href="/blog/queries/signup">SignUp</a></li>
		<li><a href="/blog/queries/send_email_varification_code">SendEmailVerificationCode</a></li>
		<li><a href="/blog/queries/verify_email">VerifyEmail</a></li>
		<li><a href="/blog/queries/login">Login</a></li>
		<li><a href="/blog/queries/conditional_login">ConditionalLogin</a></li>
		<li><a href="/blog/queries/refresh_token">RefreshToken</a></li>
		<li><a href="/blog/queries/files_upload">FilesUpload</a></li>
		<li><a href="/blog/queries/send_mail">SendMail</a></li>
		<li><a href="/blog/queries/push_subscribe">PushSubscribe</a></li>
		<li><a href="/blog/queries/get_app_secret">GetAppSecret</a></li>
	</ol>
</div>
@endsection
@endif

@if($category == 'files')
@section("category")
<h4><a href="/blog">Catagory</a> >> <a href="/blog/files">Files</a></h4>
@endsection
@section("blog")
<div class="jumbotron">
	<h1>Files</h1> 
	<ol>
		<li><a href="/blog/files/create_new_app">Create New App</a></li>
		<li><a href="/blog/files/activate_app_id">Active App Id</a></li>
		<li><a href="/blog/files/activate_app">Activate App</a></li>
		<li><a href="/blog/files/update_app">Update App</a></li>
		<li><a href="/blog/files/permissions">Permissions</a></li>
		<li><a href="/blog/files/origins">Origins</a></li>
	</ol>
</div>
@endsection
@endif

@if($category == 'emails')
@section("category")
<h4><a href="/blog">Catagory</a> >> <a href="/blog/emails">Emails</a></h4>
@endsection
@section("blog")
<div class="jumbotron">
	<h1>Emails</h1> 
	<ol>
		<li><a href="/blog/emails/create_new_app">Create New App</a></li>
		<li><a href="/blog/emails/activate_app_id">Active App Id</a></li>
		<li><a href="/blog/emails/activate_app">Activate App</a></li>
		<li><a href="/blog/emails/update_app">Update App</a></li>
		<li><a href="/blog/emails/permissions">Permissions</a></li>
		<li><a href="/blog/emails/origins">Origins</a></li>
	</ol>
</div>
@endsection
@endif

@if($category == 'push_notifications')
@section("category")
<h4><a href="/blog">Catagory</a> >> <a href="/blog/push_notifications">Push Notifications</a></h4>
@endsection
@section("blog")
<div class="jumbotron">
	<h1>Push Notifications</h1> 
	<ol>
		<li><a href="/blog/push_notifications/create_new_app">Create New App</a></li>
		<li><a href="/blog/push_notifications/activate_app_id">Active App Id</a></li>
		<li><a href="/blog/push_notifications/activate_app">Activate App</a></li>
		<li><a href="/blog/push_notifications/update_app">Update App</a></li>
		<li><a href="/blog/push_notifications/permissions">Permissions</a></li>
		<li><a href="/blog/push_notifications/origins">Origins</a></li>
	</ol>
</div>
@endsection
@endif

@if($category == 'chat_messaging')
@section("category")
<h4><a href="/blog">Catagory</a> >> <a href="/blog/chat_messaging">Chat Messaging</a></h4>
@endsection
@section("blog")
<div class="jumbotron">
	<h1>Chat Messaging</h1> 
	<ol>
		<li><a href="/blog/chat_messaging/create_new_app">Create New App</a></li>
		<li><a href="/blog/chat_messaging/activate_app_id">Active App Id</a></li>
		<li><a href="/blog/chat_messaging/activate_app">Activate App</a></li>
		<li><a href="/blog/chat_messaging/update_app">Update App</a></li>
		<li><a href="/blog/chat_messaging/permissions">Permissions</a></li>
		<li><a href="/blog/chat_messaging/origins">Origins</a></li>
	</ol>
</div>
@endsection
@endif

@if($category == 'logs')
@section("category")
<h4><a href="/blog">Catagory</a> >> <a href="/blog/logs">Logs</a></h4>
@endsection
@section("blog")
<div class="jumbotron">
	<h1>Logs</h1> 
	<ol>
		<li><a href="/blog/logs/create_new_app">Create New App</a></li>
		<li><a href="/blog/logs/activate_app_id">Active App Id</a></li>
		<li><a href="/blog/logs/activate_app">Activate App</a></li>
		<li><a href="/blog/logs/update_app">Update App</a></li>
		<li><a href="/blog/logs/permissions">Permissions</a></li>
		<li><a href="/blog/logs/origins">Origins</a></li>
	</ol>
</div>
@endsection
@endif